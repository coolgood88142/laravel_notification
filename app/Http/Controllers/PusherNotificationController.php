<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\SendMessage;
use App\Models\Articles;
use Pusher\Pusher;
use Carbon\Carbon;

class PusherNotificationController extends Controller
{
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
            $articles= Articles::where('online_date', '=', $date)->get();
            foreach($articles as $article){
                set_time_limit(1200);
                
                $title = $article->title;
                $id = $article->id;
                $sendNotice = $article->send_notice;

                if($sendNotice == 'Y'){
                    \App\User::chunk(10000, function($users)
                    {   
                        $title = $this->title;
                        $id = $this->id;
                        event(new AddArticles($users, $title, $id));
                        
                        $data['message'] = '您有一篇新訊息【' . $title. '】';
                        $data['userData'] =  [
                            'articleId' => $id,
                            'isRead' => 'N',
                            'status' => 'addArticle'
                        ];

                        $data['users'] = $users;
                        $pusher->trigger('article-channel', 'App\\Events\\SendMessage', $data);
                        event(new RedisMessage($data));
                    });

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
                }
                
            }
        }catch(Exception $e){
            dd($e);
        }
        
    }
}
