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

		$date = Carbon::now()->setTimezone('Asia/Taipei')->toDateString();
        $articles= Articles::where('online_date', '=', $date)->get();
        foreach($articles as $article){
            set_time_limit(1200);
            
            $title = $article->title;
            $id = $article->id;
            $sendNotice = $article->send_notice;

            if($sendNotice == 'Y'){
                foreach (\App\User::cursor() as $user) {
                    $data['message'] = '您有一篇新訊息【' . $title. '】';
					$data['user'] = $user;
        			$pusher->trigger('article-channel', 'App\\Events\\SendMessage', $data);
                };
            }
            
        }
    }
}
