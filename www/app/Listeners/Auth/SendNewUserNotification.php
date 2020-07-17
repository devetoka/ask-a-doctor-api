<?php

namespace App\Listeners\Auth;

use App\Events\Auth\Validated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewUserNotification
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
    public function handle(Validated $event)
    {
        //
    }
}
