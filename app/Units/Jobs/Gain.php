<?php

namespace App\Units\Jobs;

use App\Domains\Investment\Services\MoveService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Gain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $account_id;
    private $move_id;

    /**
     * Create a new Gain instance.
     *
     * @return void
     */
    public function __construct(int $account_id, int $move_id)
    {
        $this->account_id = $account_id;
        $this->move_id = $move_id;
    }

    /**
     * Execute the Gain.
     *
     * @return void
     */
    public function handle(MoveService $service)
    {
        $response = $service->gain($this->account_id, $this->move_id);
        if($response['statusCode'] === 400){
            $this->fail();
            $this->release(30);
        }
    }
}
