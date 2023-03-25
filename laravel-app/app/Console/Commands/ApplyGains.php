<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Enums\InvestmentStatus;
use Illuminate\Console\Command;
use App\Services\InvestmentService;

class ApplyGains extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'cron:applygains';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {
        $investments = Investment::where('status', InvestmentStatus::ACTIVE)->where('expected_balance', 0);

        foreach ($investments->get() as $investment) {
            try {
                $investmentService = new InvestmentService($investment);
                $investment->expected_balance = $investmentService->calculateGain();
                $investment->save();
            } catch (\Exception $e) {
                return Command::FAILURE;
            }
        }
        return Command::SUCCESS;
    }
}
