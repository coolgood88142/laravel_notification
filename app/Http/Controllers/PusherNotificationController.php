<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\SendMessage;
use App\Events\AddArticles;
use App\Models\Articles;
use Pusher\Pusher;
use Carbon\Carbon;

class PusherNotificationController extends Controller
{

    public $title;
    public $id;

    public function notification()
    {
        $options = array(
			'cluster' => 'ap3',
			'encrypted' => true
		);

        $pusher = new Pusher(
			'408cd422417d5833d90d',
			'2cb040ab9efbb676ed8b',
			'1243356', 
			$options
		);

        try{
            $date = Carbon::now()->setTimezone('Asia/Taipei')->toDateString();
            $articles = Articles::where('online_date', '=', $date)
                                ->where('send_notice', '=', 'Y')    
                                ->get();
            foreach($articles as $article){
                set_time_limit(1200);
                
                $this->title = '您有一篇新訊息【' . $article->title . '】';
                $this->id = $article->id;
                // $sendNotice = $article->send_notice;

                $data['message'] = $this->title;
                $data['userData'] =  [
                    'articleId' => $this->$id,
                    'isRead' => 'N',
                    'status' => 'addArticle'
                ];

                // event(new PusherNotification($data));

                \App\User::chunk(10000, function($users)
                {   
                    event(new AddArticles($users, $this->title, $this->$id));

                    $data['users'] = $users;
                    // dd($data);
                    $pusher->trigger('article-channel', 'App\\Events\\SendMessage', $data);
                    event(new RedisMessage($data));
                });

                // if($sendNotice == 'Y'){
                    

                    // foreach (\App\User::cursor() as $user) {
                    //     $notification = $user->notifications()->where('data->status', '=', 'addArticle')->first();
                    
                    //     $data['message'] = '您有一篇新訊息【' . $title. '】';
                    //     $data['userData'] =  [
                    //         'userId' => Auth::id(),
                    //         'articleId' => $id,
                    //         'notificationId' => $notification->id,
                    //         'isRead' => 'N',
                    //         'status' => 'addArticle'
                    //     ];

                    //     $pusher->trigger('article-channel', 'App\\Events\\SendMessage', $data);
                    // };
                // }
                
            }
        }catch(Exception $e){
            dd($e);
        }
        
    }
}
