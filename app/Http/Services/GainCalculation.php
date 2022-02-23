<?php

namespace App\Http\Services;

use Carbon\Carbon;

class GainCalculation
{
    private $investment_date;

    public function __construct($investment_date)
    {
        $this->investment_date = $investment_date;
    }

    public function updateAmount($value)
    {
        $newAmount = $value;
        for ($i = 0; $i < $this->calulateDiffToDates($this->investment_date); $i++) {
            $newAmount += ($newAmount * .052);
        }
        return $newAmount;
    }

    private function calulateDiffToDates($date)
    {
        $date_pattern = Carbon::parse($date);
        $now = Carbon::now();
        return $date_pattern->diffInMonths($now);
    }
}
