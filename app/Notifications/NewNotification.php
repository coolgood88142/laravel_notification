<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $title, $id, $status)
    {
        $this->user = $user;
        $this->title = $title;
        $this->articlesId = $id;
        $this->channelsId = $id;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $status = $this->status;
        $data = [
            'name' => $notifiable->name,
            'email' => $notifiable->email,
            'title' => $this->title,
            'status' => $this->status
        ];

        if($status != 'addChannel'){
            $data['articlesId'] = $this->articlesId;
        }else{
            $data['channelsId'] = $this->channelsId;
        }

        return $data;
    }
}
