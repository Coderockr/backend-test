<?php

namespace App\Services;

use App\Models\Investment;
use Carbon\Carbon;

class InvestmentService{

    public function getNumberOfGainsByInvestmentInitialDate(Investment $investment){

        $today = Carbon::now()->format('Y-m-d H:i:s');

        return $investment->created_at->diffInMonths($today);
    }
    public function calculateInvestmentGain(Investment $investment, float &$balance){

        $gain = floatval( $balance * floatval($investment->gain / 100) );
        $balance = $balance + $gain;

        return $gain;
    }
}