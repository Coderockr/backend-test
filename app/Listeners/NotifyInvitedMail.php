<?php

namespace App\Listeners;

use App\Events\InvitationCreated;
use App\Jobs\SendInvitationMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyInvitedMail implements ShouldQueue
{
    public function handle(InvitationCreated $event)
    {
        dispatch(new SendInvitationMail($event->invitation));
    }
}
