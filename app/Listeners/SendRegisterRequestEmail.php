<?php

namespace App\Listeners;

use App\Events\RegisterRequestCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRegisterRequestEmail
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
     * @param  RegisterRequestCreated  $event
     * @return void
     */
    public function handle(RegisterRequestCreated $event)
    {
        $registerRequest = $event->getRegisterRequestModel();
        logger('Email sent to: ' . $registerRequest->email);

        // Code to send the email to the $registerRequest->email
    }
}
