<?php

namespace App\Listeners\Auth;

use App\Mail\Authentication\VerificationMail;
use App\Utilities\Enum\Encryption\Encryption;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmailNotification implements ShouldQueue
{
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
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        //
        $token = Encryption::encryptString($event->user->email);
        Mail::to($event->user)->queue(new VerificationMail($event->user, $token));
    }
}
