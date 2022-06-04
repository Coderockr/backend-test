<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Investment;

class InvestmentService {

    /**
     * Reset hours, minutes and seconds from datetime.
     *
     * @param  Carbon  $date
     * @return Carbon
     */
    private function resetHoursMinutesAndSecondsFromDateTime(Carbon $date) : Carbon {
        return $date->hour(0)->minute(0)->second(0);
    }

    /**
     * Use the compound formula for calculate the gains of an investment.
     *
     * @param  float  $investedAmount
     * @param  float  $gainValue
     * @param  integer  $numberOfMonths
     * @return float
     */
    private function calculateCompoundGains(float $investedAmount, float $gainValue, int $numberOfMonths) : float {
        return $investedAmount * pow((1 + $gainValue), $numberOfMonths);
    }

    /**
     * Get the difference of dates in months.
     *
     * @param  Carbon  $investmentInsertedAt
     * @param  Carbon  $currentDate
     * @return int
     */
    private function getDifferenceOfDatesInMonths(Carbon $investmentInsertedAt, Carbon $currentDate) : int {
        return $investmentInsertedAt->diffInMonths($currentDate);
    }

    /**
     * Get the compound gains.
     *
     * @param  \App\Models\Investment  $investment
     * @param  float  $gainValue
     * @return int
     */
    public function getCompoundGains(Investment $investment, float $gainValue) : float {
        $investedAmount = $investment['amount'];

        $investmentInsertedAt = $this->resetHoursMinutesAndSecondsFromDateTime(Carbon::createFromFormat('Y-m-d', $investment['inserted_at']));
        $currentDate = $this->resetHoursMinutesAndSecondsFromDateTime(Carbon::now());

        $numberOfMonths = $this->getDifferenceOfDatesInMonths($investmentInsertedAt, $currentDate);

        $gainValue = Investment::GAIN_VALUE;
        $expectedBalance = $this->calculateCompoundGains($investedAmount, $gainValue, $numberOfMonths);

        return $expectedBalance;
    }
}