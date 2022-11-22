<?php

namespace App\Observers;

use App\Models\Investment;
use App\Models\InvestmentMovement;
use App\Services\InvestmentService;
use Illuminate\Support\Collection;

class InvestmentObserver
{
    protected $investmentService;

    public function __construct(){
        $this->investmentService = new InvestmentService();
    }
    /**
     * Handle the Investment "created" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function created(Investment $investment)
    {

        $movements = new Collection();
        $numberOfGains = $this->investmentService->getNumberOfGainsByInvestmentInitialDate($investment);

        $movements->push([
            'description' => 'Initial Investment Amount',
            'value' => $investment->initial_investment,
            'movement_at' => $investment->created_at,
            'type' => InvestmentMovement::TYPE_INITIAL,
        ]);

        if($numberOfGains > 0){

            $balance = $investment->initial_investment;

            for($i = 0; $i < $numberOfGains; $i++){
                $movements->push([
                    'description' => 'Investment Gain',
                    'value' => $this->investmentService->calculateInvestmentGain($investment, $balance),
                    'movement_at' => $investment->created_at->addMonth($i+1),
                    'type' => InvestmentMovement::TYPE_GAIN,
                ]);
            }
        }

        $investment->movements()->createMany($movements);
    }

    /**
     * Handle the Investment "updated" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function updated(Investment $investment)
    {

        $movements = new Collection();
        $numberOfGains = $this->investmentService->getNumberOfGainsByInvestmentInitialDate($investment);

        $movements->push([
            'description' => 'Initial Investment Amount',
            'value' => $investment->initial_investment,
            'movement_at' => $investment->created_at,
            'type' => InvestmentMovement::TYPE_INITIAL,
        ]);

        if($numberOfGains > 0){

            $balance = $investment->initial_investment;

            for($i = 0; $i < $numberOfGains; $i++){
                $movements->push([
                    'description' => 'Investment Gain',
                    'value' => $this->investmentService->calculateInvestmentGain($investment, $balance),
                    'movement_at' => $investment->created_at->addMonth($i+1),
                    'type' => InvestmentMovement::TYPE_GAIN,
                ]);
            }
        }

        $investment->movements->each(function($movments){
            $movments->delete();
        });

        $investment->movements()->createMany($movements);

    }
}
