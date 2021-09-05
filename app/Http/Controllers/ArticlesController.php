<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\LazyCollection;
use App\Events\AddArticles;
use App\Events\DeleteArticles;
use App\Events\RedisMessage;
use App\Models\Articles;
use App\Models\Channels;
use App\Models\Notifications;
use App\User;
use App\Models\Author;
use App\Models\Comment;
use Carbon\Carbon;
use Pusher\Pusher;
use Auth;

class ArticlesController extends Controller
{
    public $title;
    public $id;

    //文章列表-顯示所有文章、頻道
    public function showAritcles(Request $request)
    {   
        $id = Auth::id();
        $user = \App\User::where('id', '=', $id)->first();
        $count = 10;
        $notifications = $user->notifications->take($count);
        $datetime = Carbon::now()->setTimezone('Asia/Taipei')->toDateTimeString();
        $notificationsArray = [];
        $articles = Articles::orderBy('id')->get();
        $channels = Channels::orderBy('id')->get();
    
        $data = [
            'notifications' => $notificationsArray,
            'notificationsCount' => $count,
            'articles' => $articles,
            'channels' => $channels,
            'userId' => $id,
            'datetime' => $datetime,
            'urlData' => [
                'article' => route('showArticleContent'),
                'channel' => route('showChannelContent'),
                'getNotification' => route('getNotificationDataCount'),
                'read' => route('readArticles')
            ]
        ];

        // dd($data['urlData']);

        return view('articles', $data);
    } 


    //新增一篇文章
    public function addArticles(Request $request)
    {
        $articles = new Articles();
        $articles->title = $request->InputTitle;
        $articles->content = $request->InputContent;
        $articles->author_id = $request->userId;
        $articles->online_date = $request->onlineDate;
        $sendNotice = $request->sendNotice;

        if($sendNotice == null){
            $sendNotice = 'N';
        }

        $articles->send_notice = $sendNotice;
        $articles->save();
        $title = $articles->title;
        $id = $articles->id;
        $this->title = '您有一篇新訊息【新文章:' . $title . '】';
        $this->id = $id;

        //判斷新增後的title和id不為null或空白
        if($title != null && $title != '' && $id != null && $id != ''){
            //執行事件傳訊息，顯示有一篇新文章
            set_time_limit(0);
            \App\User::chunk(10000, function($users)
            {   
                $title = $this->title;
                $id = $this->id;
                event(new AddArticles($users, $title, $id));
            });
        }

        return redirect()->route('showAritcles');
    }

    //已閱讀通知 or 已閱讀全部
    public function readArticles(Request $request)
    {
        $id = $request->id;
        $userId = $request->userId;
        $status = 'success';
        try{
            if($id != ''){
                //已閱讀通知
                $this->readNotifications($id, $userId);
            }else{
                //已閱讀全部
                $this->readNotificationsAll($userId);
            }
            
        }catch(Exception $e){
            $status = 'error';
        }
        return $status;
    }

    //已閱讀通知
    public function readNotifications($id, $userId){
        // $userId =  $request->userId;
        // $id =  $request->id;
        $user = User::where('id', '=', $userId)->first();
        $data = $user->unreadNotifications->where('id', '=', $id)->first()->markAsRead();
    }

    //已閱讀全部
    public function readNotificationsAll($userId){
        // $userId =  $request->userId;
        $user = User::where('id', '=', $userId)->first();
        $data = $user->unreadNotifications->markAsRead();
    }

    //顯示單篇文章內容
    public function showArticleContent(Request $request)
    {
        $id = $request->id;
        $userId = $request->userId;
        $notificationId = $request->notificationId;
        $isAdd = $request->isAdd;
        $articles = Articles::where('id', '=', $id)->first();
        $user = User::where('id', '=', $articles->author_id)->first();
        $comment =  DB::table('users')
            ->select('users.name', 'comment.text')
            ->join('comment', 'users.id', '=', 'comment.user_id')
            ->where([
                ['articles_id', '=', $id]
            ])
            ->get();

        // if($notificationId != null && $userId != null && $isRead == 'N'){
        //     //點選後直接做已閱讀
        //     $this->readNotifications($notificationId, $userId);
        // }
        

        return view('edit', [
            'articleId' => $id,
            'title' => $articles->title,
            'content' => $articles->content,
            'onlineDate' => $articles->online_date,
            'sendNotice' => $articles->send_notice,
            'userId' => $userId,
            'userName' => $user->name,
            'comment' => $comment,
            'isAdd' => $isAdd
        ]);
    }

    //儲存文章
    public function saveArticles(Request $request)
    {
        $id = $request->id;
        $status = 'success';
        try{
            $articles = Articles::where('id', '=', $id)->first();
            $articles->title = $request->title;
            $articles->content = $request->content;
            $articles->save();
        }catch(Exception $e){
            $status = 'error';
        }

        return $status;
    }

    //刪除文章
    public function deleteArticles(Request $request)
    {
        $id = $request->id;
        $isEven = $request->isEven;
        $status = 'success';
        try{
            $articles = Articles::where('id', '=', $id)->first();
            $title = '您有一篇新訊息【' . $articles->title . '已刪除】';
            $articles->delete();
            $even = $isEven ? '0' : '1';

            $pusher = new Pusher(
                '408cd422417d5833d90d',
                '2cb040ab9efbb676ed8b',
                '1243356', 
                array(
                    'cluster' => 'ap3',
                    'encrypted' => true
                )
            );

            // $user =  User::where('id % 2', '=', $even);
            
            set_time_limit(0);
            \App\User::chunk(10000, function($users)
            {   
                $title = $this->title;
                $id = $this->id;
                event(new DeleteArticles($users, $title, $id));
                
                $data['message'] =  $title;
                $data['userData'] =  [
                    'id' => $id,
                    'type' => 'deleteArticle'
                ];

                // $data['users'] = $users;

                foreach($users as $user){
                    $notification = $user->notifications()->first();
                    $data['broadcast'] = $notification;
                    $pusher->trigger('article-channel' . $user->id, 'App\\Events\\SendMessage', $data);
                }

                event(new RedisMessage($data));
            });
        }catch(Exception $e){
            $status = 'error';
        }

        return $status;
    }

    //通知列表-顯示全部的通知
    public function showNotification(Request $request){
        //分頁、新增多少件數
        $page = $request->page;
        $count = $request->count;

        if($page != null && $page != ''){
            $nowCount = ($page - 1) * 3 + 1;
        }else{
            $nowCount = $request->nowCount;
        }

        //計算目前的user，要顯示多少筆通知列表資料
        $id = Auth::id();
        $user = \App\User::where('id', '=', $id)->first();
        $newNotification = $user->notifications->skip($nowCount)->take($count);
        return $newNotification;
    }

    public function getNotificationDataCount(Request $request){
        $id = Auth::id();
        $user = User::where('id', '=', $id)->first();
        $notification = $user->unreadNotifications()->count();

        return $notification;
    }

    public function sendNotification(){
        $date = Carbon::now()->setTimezone('Asia/Taipei')->toDateString();
        $articles= Articles::where('online_date', '=', $date)->get();
        foreach($articles as $article){
            set_time_limit(1200);
            
            $title = $article->title;
            $id = $article->id;
            $sendNotice = $article->send_notice;

            if($sendNotice == 'Y'){
                foreach (\App\User::cursor() as $user) {
                    event(new AddArticles($user, '您有一篇新訊息【新文章:' . $title . '】', $id));
                };
            }
            
        }
        
    }
    
    public function showAritclesRedis(Request $request){
        $id = Auth::id();
        $user = \App\User::where('id', '=', $id)->first();
        $count = 10;
        $notifications = $user->notifications->take($count);
        $datetime = Carbon::now()->setTimezone('Asia/Taipei')->toDateTimeString();
        $notificationsArray = [];
        
        $articles = Articles::orderBy('id')->get();
        $channels = Channels::orderBy('id')->get();
    
        $data = [
            'notifications' => $notificationsArray,
            'notificationsCount' => $count,
            'articles' => $articles,
            'channels' => $channels,
            'userId' => $id,
            'datetime' => $datetime,
        ];

        return view('articlesRedis', $data);
    }

    //收到推播資料時，查詢通知的相關資料，組元素用的
    public function getNotificationData(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', '=', $id)->first();
        $notification = $user->notifications()->first();

        return [
            'userId' => $user->id,
            'notificationId' => $notification->id,
            'read_at' => $user->read_at
        ];
    }
    
    //顯示新增文章畫面
    public function showAdd(Request $request)
    {
        $userId = $request->userId;

        return view('add', [
            'userId' => $userId,
        ]);
    }

    public function changeOption(Request $request){
        // dd($request);
        $sort = $request->sort;
        $category = $request->category;

        if($sort == '' || $sort == null){
            $sort = '1';
        }

        if($category == '' || $category == null){
            $category = '1';
        }
        // dd($option);
        return view('select', [
            'sort' => $sort,
            'category' => $category,
        ]);
    }
}
