<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SentResetPasswordLinkMail extends Mailable
{
    use Queueable, SerializesModels;
    public $url;
    public $expiration;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $url
     * @param $expire
     */
    public function __construct($email, $url, $expiration)
    {
        //
        $this->url = $url;
        $this->email = $email;
        $this->expiration = $expiration;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.auth.send-reset-link')
            ->to($this->email)
            ->subject('Reset Password Notification')
            ->bcc('francis.dretoka@gmail.com');

    }
}
