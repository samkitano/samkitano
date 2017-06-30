<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmRegistration extends Notification
{
    use Queueable;

    /** @var \App\RegistrationToken */
    public $registration_token;


    /**
     * Create a new notification instance.
     *
     * @param $registration_token
     */
    public function __construct($registration_token)
    {
        $this->token = $registration_token->token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->line('Thank you for registering! Just click the button below to confirm.')
            ->action('Confirm Registration', route('register.confirm', $this->token))
            ->line('If you did not registered at samkitano.com, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
