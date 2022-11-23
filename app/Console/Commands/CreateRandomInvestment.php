<?php

namespace App\Console\Commands;

use App\Models\Investment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateRandomInvestment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:investment {amount} {user_id} {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a random investment';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $amount = $this->argument('amount');
        $user_id = $this->argument('user_id');
        $date = Carbon::createFromFormat('Y-m-d', $this->argument('date'));

        DB::table('investments')->insert([
            'amount' => $amount,
            'user_id' => $user_id,
            'date' => $date
        ]);

    }
}
