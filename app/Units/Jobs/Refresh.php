<?php

namespace App\Units\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Refresh implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new Refresh instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the Refresh.
     *
     * @return void
     */
    public function handle()
    {
        
    }
}
