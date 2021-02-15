<?php

namespace App\Jobs;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInvitationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /* @var Invitation */
    public $invitation;

    public function __construct($invitation)
    {
        $this->invitation = $invitation;
    }

    public function handle()
    {
        Mail::to($this->invitation->email)->send(
            new InvitationMail($this->invitation->user)
        );
    }
}
