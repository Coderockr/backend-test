<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Movement;
use Carbon\Carbon;

class InvestmentServices
{
    public function getMonthsOfInvestmentDate(Investment $investment)
    {
        $now = Carbon::now();
        $investmentDate = Carbon::parse($investment->date);

        return $investmentDate->diffInMonths($now);
    }

    public function createCompoundGain(Investment $investment, $amount)
    {
        $gainTx = $investment->gain;
        $realGain = $amount * $gainTx;
        $updatedValue = $realGain + $amount;

        return Movement::create([
            'investment_id' =>  $investment->id,
            'real_gain' =>  $realGain,
            'updated_value' =>  $updatedValue,
        ]);
    }
}
