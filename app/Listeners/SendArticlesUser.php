<?php

namespace App\Listeners;

use App\Events\AddArticles;
use App\Events\AddComment;
use App\Events\DeleteArticles;
use App\Notifications\NewNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendArticlesUser implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param   $event
     * @return void
     */
    public function handle($event)
    {
        $id = '';
        $users = $event->user;

        foreach($users as $user){
            if($event->status != 'addChannel'){
                $id = $event->articlesId;
            }else{
                $id = $event->channelsId; 
            }
    
            Notification::send($user, new NewNotification($user, $event->title, $id, $event->status));
        }

       
    }
}
