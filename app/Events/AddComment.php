<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddComment
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $title;
    public $articlesId;
    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $title, $articlesId)
    {
        $this->user = $user;
        $this->title = $title;
        $this->articlesId = $articlesId;
        $this->status = 'addComment';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
