<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\Profit;
use Illuminate\Console\Command;

class GenerateProfitToInvestment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:profit {investmentId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate profit(s) to investment(s)';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $investmentId = $this->argument('investmentId');

        if ($investmentId) {
            $investment = Investment::query()->find($investmentId);
            $this->generateProfitsToInvestment($investment);
        } else {
            Investment::query()->each(function ($investment) {
                $this->generateProfitsToInvestment($investment);
            });
        }

        $this->line('Profits Generated!');
    }

    public function generateProfitsToInvestment($investment): void
    {
        $nextProfit = $investment->date->addMonth()->startOfDay();

        while ($nextProfit->lt(now()->startOfDay())) {
            if (
                !Profit::query()
                    ->where('investment_id', $investment->id)
                    ->whereMonth('date', $nextProfit->month)
                    ->exists()
            ) {
                $profitAmount = ($investment->getTotalAmount() / 100) * 0.52;

                $investment->profits()->create([
                    'date' => $nextProfit,
                    'amount' => $profitAmount
                ]);
            }

            $nextProfit = $nextProfit->addMonth();
        }
    }
}
