<?php

namespace App\Units\Jobs;

use App\Units\Mail\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    private $mail;
    private $path;

    /**
     * Create a new SendEmail instance.
     *
     * @return void
     */
    public function __construct(array $mail = null, array $path = null)
    {
        $this->mail = $mail;
        $this->path = $path;
    }

    /**
     * Execute the SendEmail.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send(new Email($this->mail, $this->path));
    }
}
