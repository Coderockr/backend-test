<?php

namespace App\Observers;

use App\Models\Investment;
use App\Models\Movement;
use App\Services\InvestmentServices;

class InvestmentObserver
{
    public function __construct(
        private InvestmentServices $service
    )
    {
    }

    /**
     * Handle the Investment "created" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function created(Investment $investment)
    {
        $months = $this->service->getMonthsOfInvestmentDate($investment);
        $investmentValue = $investment->initial_value;

        for ($i = 0; $i < $months; $i++) {
            $movement = $this->service->createCompoundGain($investment, $investmentValue);

            $investmentValue = $movement->updated_value;
        }
    }

    /**
     * Handle the Investment "updated" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function updated(Investment $investment)
    {

    }

    /**
     * Handle the Investment "deleted" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function deleted(Investment $investment)
    {
        //
    }

    /**
     * Handle the Investment "restored" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function restored(Investment $investment)
    {
        //
    }

    /**
     * Handle the Investment "force deleted" event.
     *
     * @param  \App\Models\Investment  $investment
     * @return void
     */
    public function forceDeleted(Investment $investment)
    {
        //
    }
}
