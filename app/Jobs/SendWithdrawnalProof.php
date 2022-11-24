<?php

namespace App\Jobs;

use App\Mail\InvestmentWithdrawnal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWithdrawnalProof implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $investment;

    public function __construct($investment)
    {
        $this->investment = $investment;
    }

    public function handle()
    {
        Mail::to($this->investment->person->email)->send(new InvestmentWithdrawnal($this->investment));
    }
}