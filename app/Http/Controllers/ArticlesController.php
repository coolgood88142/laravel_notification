<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use App\Events\AddArticles;
use App\Models\Articles;
use App\Models\Notifications;
use App\User;
use App\Models\Author;
use App\Models\Comment;
use Carbon\Carbon;
use Auth;

class ArticlesController extends Controller
{
    public $title;
    public $id;
    public function showAritcles(Request $request)
    {   
        $id = Auth::id();
        $user = \App\User::where('id', '=', $id)->first();
        $notifications = $user->notifications;
        $datetime = Carbon::now()->setTimezone('Asia/Taipei')->toDateTimeString();
        $notificationsArray = [];
        foreach($user->notifications as $notification){
            $array = [ 
                'id' => $notification->id,
                'title' => $notification->data['title'],
                'status' => $notification->data['status'],
                'articlesId' => $notification->data['articlesId'],
                'read' => $notification->read(),
                'notThreeDay' => ceil((strtotime($notification->created_at) - strtotime($datetime))/86400) < 3
            ];
            array_push($notificationsArray, $array);
        }
        $articles = Articles::orderBy('id')->get();
    
        $data = [
            'notifications' => $notificationsArray,
            'notificationsCount' => count($notificationsArray),
            'articles' => $articles,
            'userId' => $id,
            'datetime' => $datetime,
        ];

        return view('articles', $data);
    } 


    public function addArticles(Request $request)
    {
        //新增一篇文章
        $articles = new Articles();
        $articles->title = $request->InputTitle;
        $articles->content = $request->InputContent;
        $articles->author_id = $request->userId;
        $articles->save();
        $title = $articles->title;
        $id = $articles->id;
        // $this->title = $title;
        // $this->id = $id;

        //判斷新增後的title和id不為null或空白
        if($title != null && $title != '' && $id != null && $id != ''){
            //執行事件傳訊息，顯示有一篇新文章
            //設定執行時間為10分鐘
            set_time_limit(1200);

            // \App\User::chunk(100, function($users)
            // {   
            //     foreach($users as $user)
            //     {
            //         $title = $this->title;
            //         $id = $this->id;
            //         event(new AddArticles(\App\User::all(), '新文章:' . $title, $id, 'add'));
            //     }
            // });

            foreach (\App\User::cursor() as $user) {
                // $title = $this->title;
                // $id = $this->id;
                event(new AddArticles($user, '新文章:' . $title, $id, 'add'));
            };

            
        }

        return redirect()->route('showAritcles');
    }

    public function readArticles(Request $request)
    {
        $id = $request->id;
        $userId = $request->userId;
        // $notifications = Notifications::Where('id', '=', $id);
        // $notifications->created_at = Carbon::now();
        // $notifications->save();
        $status = 'success';
        // $id = 'e093a6e5-e181-4829-a6ed-e7183f93220c';
        try{
            $this->readNotifications($id, $userId);
        }catch(Exception $e){
            $status = 'error';
        }
        return $status;
    }

    public function readNotifications($id, $userId){
        $user = User::where('id', '=', $userId)->first();
        $data = $user->unreadNotifications;
            
        if($id != ''){
            foreach($data as $key => $value){
                $dataId = $value->id;
                if($dataId == $id){
                    $value->markAsRead();
                        break;
                }
            }
        }else{
            $data->markAsRead();
        }
    }

    public function showArticleContent(Request $request)
    {
        $id = $request->id;
        $userId = $request->userId;
        $notificationId = $request->notificationId;
        $isAdd = $request->isAdd;
        $isRead = $request->isRead;
        $articles = Articles::where('id', '=', $id)->first();
        $user = User::where('id', '=', $articles->author_id)->first();
        $comment =  DB::table('users')
            ->select('users.name', 'comment.text')
            ->join('comment', 'users.id', '=', 'comment.user_id')
            ->where([
                ['articles_id', '=', $id]
            ])
            ->get();

        if($notificationId != null && $userId != null && $isRead == 'N'){
            //點選後直接做已閱讀
            $this->readNotifications($notificationId, $userId);
        }
        

        return view('edit', [
            'articleId' => $id,
            'title' => $articles->title,
            'content' => $articles->content,
            'userId' => $userId,
            'userName' => $user->name,
            'comment' => $comment,
            'isAdd' => $isAdd
        ]);
    }

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

    public function deleteArticles(Request $request)
    {
        //刪除一篇文章
        $id = $request->id;
        $isEven = $request->isEven;
        $status = 'success';
        try{
            $articles = Articles::where('id', '=', $id)->first();
            $title = $articles->title . '已刪除';
            $articles->delete();
            $even = $isEven ? '0' : '1';

            // $user =  User::where('id % 2', '=', $even);
            
            $user = DB::table('users')->select('select * from users where id % 2 = '. $even);
            event(new AddArticles($user, $title, $id, 'delete'));
        }catch(Exception $e){
            $status = 'error';
        }

        return $status;
    }
    
    public function showAdd(Request $request)
    {
        $userId = $request->userId;

        return view('add', [
            'userId' => $userId,
        ]);
    }

    public function testAdd(){
        $articles = \App\Models\Articles::factory()->count(1)->create(); 
        $array = $articles->toArray();
        $title = $array[0]['title'];

        //判斷新增後的title不為null或空白
        if($title != null || $title != ''){
            //執行事件傳訊息，顯示有一篇新文章
            event(new AddArticles(\App\User::all(), '新文章:' . $title));
        }
    }
}
