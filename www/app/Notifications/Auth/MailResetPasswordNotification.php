<?php

namespace App\Notifications\Auth;

use App\Mail\Auth\SentResetPasswordLinkMail;
use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailResetPasswordNotification extends Notification
{
    use Queueable;
    private static $toMailCallback;
    private $user;
    private $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @param $user
     */
    public function __construct($token, $user)
    {
        //
        $this->token = $token;
        $this->user = $user;

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
     * @return SentResetPasswordLinkMail
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }
        $url = url(env('FRONTEND_URL', ''). '?token='. $this->token . '&email='.$notifiable->getEmailForPasswordReset());
        $expiration = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return new SentResetPasswordLinkMail($this->user->email,$url,$expiration);

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
