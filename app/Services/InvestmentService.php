<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Investment;

class InvestmentService {

    /**
     * Truncate value to two decimal places
     *
     * @param  float  $number
     * @return float
     */   
    function truncateValueToTwoDecimalPlaces(float $number) : float {
        return (float) number_format($number , 2);
    }

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
     * @param  integer  $numberOfMonths
     * @return float
     */
    private function calculateExpectedBalance(float $investedAmount, int $numberOfMonths) : float {
        $investedAmount = $this->truncateValueToTwoDecimalPlaces($investedAmount);
        
        return $this->truncateValueToTwoDecimalPlaces($investedAmount * pow((1 + Investment::MONTH_GAIN_VALUE), $numberOfMonths));
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
     * Get the difference of dates in dates.
     *
     * @param  Carbon  $investmentInsertedAt
     * @param  Carbon  $currentDate
     * @return int
     */
    private function getDifferenceOfDatesInYears(Carbon $investmentInsertedAt, Carbon $currentDate) : int {
        return $investmentInsertedAt->diffInYears($currentDate);
    }

    /**
     * Get the compound gains.
     *
     * @param  \App\Models\Investment  $investment
     * @return int
     */
    public function getExpectedBalance(Investment $investment) : float {
        $investedAmount = $investment->amount;

        $investmentInsertedAt = $this->resetHoursMinutesAndSecondsFromDateTime(Carbon::createFromFormat('Y-m-d', $investment->inserted_at));
        $currentDate = $this->resetHoursMinutesAndSecondsFromDateTime(Carbon::now());

        $numberOfMonths = $this->getDifferenceOfDatesInMonths($investmentInsertedAt, $currentDate);

        $expectedBalance = $this->calculateExpectedBalance($investedAmount, $numberOfMonths);

        return $expectedBalance;
    }

    /**
     * Get the tax percent.
     *
     * @param  int $numberOfYears
     * @return float
     */
    public function getTaxValue(int $numberOfYears) : float {
        /*
            If it is less than one year old, the percentage will be 22.5% (tax = 45.00).
            If it is between one and two years old, the percentage will be 18.5% (tax = 37.00).
            If older than two years, the percentage will be 15% (tax = 30.00).
        */
        $taxPercentInDecimal = match (true) {
            $numberOfYears < 1 => Investment::TAX_INVESTMENT_LESS_THAN_ONE_YEAR,
            $numberOfYears >= 1 && $numberOfYears < 2 => Investment::TAX_INVESTMENT_BETWEEN_ONE_AND_TWO_YEARS,
            $numberOfYears >= 2 => Investment::TAX_INVESTMENT_MORE_THAN_TWO_YEARS,
            default => 1.0
        };

        return $taxPercentInDecimal;
    }

    /**
     * Apply the tax to the gain portion of a investment.
     *
     * @param  float $taxPercentInDecimal
     * @param  float $gainValue
     * @return float
     */
    public function calculateMoneyToReduceFromGain(float $taxPercentInDecimal, float $gainValue) : float {
        return $this->truncateValueToTwoDecimalPlaces($taxPercentInDecimal * $gainValue);
    }

    /**
     * Subtracts the gain portion with tax applied to the total value of a investment.
     *
     * @param  float $totalValue
     * @param  float $moneyToReduce
     * @return float
     */
    public function calculateInvestmentReturnValue(float $totalValue, float $moneyToReduce) : float {
        return $this->truncateValueToTwoDecimalPlaces($totalValue - $moneyToReduce);
    }

    /**
     * Calculate the investment return.
     *
     * @param  \App\Models\Investment $investment
     * @return float
     */
    public function getInvestmentReturn(Investment $investment) : float {
        $investedAmount = $this->truncateValueToTwoDecimalPlaces($investment->amount);

        $gainValue = Investment::MONTH_GAIN_VALUE;
        $totalValue = $this->getExpectedBalance($investment, $gainValue);

        // Only the gain value
        $gainValue =  $this->truncateValueToTwoDecimalPlaces($totalValue - $investedAmount);
        
        $investmentInsertedAt = $this->resetHoursMinutesAndSecondsFromDateTime(Carbon::createFromFormat('Y-m-d', $investment->inserted_at));
        $currentDate = $this->resetHoursMinutesAndSecondsFromDateTime(Carbon::now());
        $numberOfYears = $this->getDifferenceOfDatesInYears($investmentInsertedAt, $currentDate);

        $taxPercentInDecimal = $this->getTaxValue($numberOfYears);

        $moneyToReduce = $this->calculateMoneyToReduceFromGain($taxPercentInDecimal, $gainValue);

        $totalValue = $this->calculateInvestmentReturnValue($totalValue, $moneyToReduce);

        return $totalValue;
    }
}