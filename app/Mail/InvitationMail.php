<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /* @var User */
    public $inviter;

    public function __construct($inviter)
    {
        $this->inviter = $inviter;
    }

    public function build()
    {
        return $this->from('contato@coderock.com.br')
            ->markdown('emails.invitation', [
                'url' => '#',
            ]);
    }
}
