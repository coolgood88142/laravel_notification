<?php

namespace App\Listeners;

use App\Events\AddChannels;
use App\Events\DeleteArticles;
use App\Events\AddComment;
use App\Notifications\NewNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;


class NewArticle implements ShouldQueue
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
     * @param $event
     * @return void
     */
    public function handle($event)
    {
        Notification::send($event->user, new NewNotification($event->user, $event->title, $event->typeId, $event->type));
    }
}
