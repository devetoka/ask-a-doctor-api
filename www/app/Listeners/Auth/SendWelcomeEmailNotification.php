<?php

namespace App\Listeners\Auth;

use App\Events\Auth\Validated;
use App\Mail\Authentication\WelcomeMail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Monolog\Logger;

class SendWelcomeEmailNotification
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
     * @param  Validated  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        //
        \logger('helo');
        Mail::to($event->user)->queue(new WelcomeMail($event->user));
    }
}
