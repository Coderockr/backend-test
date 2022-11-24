<?php

namespace App\Services;

use App\Models\Investment;
use Carbon\Carbon;

class InvestmentWithdrawnService{

    CONST LESS_THAN_A_YEAR = 0.225;
    CONST BETWEEN_ONE_TWO_YEAR = 0.185;
    CONST OLDER_THAN_TWO_YEARS = 0.15;

    public function isWithdrawnDateValid(Investment $investment, $withdrawn_at) : bool{

        $created_at = $investment->created_at;
        $withdrawn_at = Carbon::parse($withdrawn_at);
        $today = Carbon::now();

        if($withdrawn_at->lt($created_at)){
            return false;
        }else if($withdrawn_at->gt($today)){
            return false;
        }

        return true;

    }

    public function getTaxByInvestmentTime(Investment $investment, $withdrawn_at) : float{

        $created_at = $investment->created_at;
        $withdrawn_at = Carbon::parse($withdrawn_at);

        if($created_at->diffInYears($withdrawn_at) < 1){
            return self::LESS_THAN_A_YEAR;
        }else if($created_at->diffInYears($withdrawn_at) > 0 and $created_at->diffInYears($withdrawn_at) < 2){
            return self::BETWEEN_ONE_TWO_YEAR;
        }

        return self::OLDER_THAN_TWO_YEARS;
    }

    public function calculateWithdrawnTaxes(Investment $investment, $withdrawn_at) : float {
        return floatval($investment->investment_profit * $this->getTaxByInvestmentTime($investment, $withdrawn_at));
    }

}