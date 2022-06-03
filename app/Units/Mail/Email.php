<?php

namespace App\Units\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    private $mail;
    private $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $mail = null, array $path = null)
    {
        $this->mail = $mail;
        $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject($this->mail['subject']);
        $this->to($this->mail['to']);
        $this->cc($this->mail['cc']);
        if($this->path){
            $this->attach(public_path().'/'.$this->path['url'], [
                'as' => $this->path['name'],
                'mime' => $this->path['extension'],
            ]);
        }
        return $this->markdown('templates.mail.notify', ['data' => $this->mail['data']]);
    }
}
