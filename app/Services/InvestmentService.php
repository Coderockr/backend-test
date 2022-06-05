<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Investment;

class InvestmentService {

    /**
     * Truncate value to two decimal places
     *
     * @param  float  $number
     * @return string
     */   
    public function truncateValueToTwoDecimalPlaces(float $number) : float {
        return floor($number * 100) / 100; 
    }

    /**
     * Reset hours, minutes and seconds from datetime.
     *
     * @param  Carbon  $date
     * @return Carbon
     */
    public function resetHoursMinutesAndSecondsFromDateTime(Carbon $date) : Carbon {
        return $date->hour(0)->minute(0)->second(0);
    }

    /**
     * Get the difference of dates in months.
     *
     * @param  Carbon  $firstDate
     * @param  Carbon  $secondDate
     * @return int
     */
    public function getDifferenceOfDatesInMonths(Carbon $firstDate, Carbon $secondDate) : int {
        return $firstDate->diffInMonths($secondDate);
    }

    /**
     * Get the difference of dates in dates.
     *
     * @param  Carbon  $firstDate
     * @param  Carbon  $secondDate
     * @return int
     */
    public function getDifferenceOfDatesInYears(Carbon $firstDate, Carbon $secondDate) : int {
        return $firstDate->diffInYears($secondDate);
    }

    /**
     * Use the compound formula for calculate the gains of an investment.
     *
     * @param  float  $investedAmount
     * @param  float  $monthGainValue
     * @param  integer  $numberOfMonths
     * @return float
     */
    public function calculateExpectedBalance(float $investedAmount, float $monthGainValue, int $numberOfMonths) : float {
        return $this->truncateValueToTwoDecimalPlaces($investedAmount * pow((1 + $monthGainValue), $numberOfMonths));
    }

    /**
     * Get the compound gains.
     *
     * @param  \App\Models\Investment  $investment
     * @param  float $monthGainValue
     * @param  Carbon $date
     * @return int
     */
    public function getExpectedBalance(Investment $investment, float $monthGainValue, Carbon $date) {//}: float {
        $investedAmount = $investment->amount;

        $investmentInsertedAt = $this->resetHoursMinutesAndSecondsFromDateTime(Carbon::createFromFormat('Y-m-d', $investment->inserted_at));
        $date = $this->resetHoursMinutesAndSecondsFromDateTime($date);

        $numberOfMonths = $this->getDifferenceOfDatesInMonths($investmentInsertedAt, $date);

        $expectedBalance = $this->calculateExpectedBalance($investedAmount, $monthGainValue, $numberOfMonths);

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
     * @param float $gainValue
     * @param Carbon $date
     * @return float
     */
    public function getInvestmentReturn(Investment $investment, float $gainValue, Carbon $date) {//}: array {
        $investedAmount = $this->truncateValueToTwoDecimalPlaces($investment->amount);

        $totalValue = $this->getExpectedBalance($investment, $gainValue, $date);
        
        // Only the gain value
        $gainValue =  $this->truncateValueToTwoDecimalPlaces($totalValue - $investedAmount);
        
        $investmentInsertedAt = $this->resetHoursMinutesAndSecondsFromDateTime(Carbon::createFromFormat('Y-m-d', $investment->inserted_at));
        $currentDate = $this->resetHoursMinutesAndSecondsFromDateTime($date);
        $numberOfYears = $this->getDifferenceOfDatesInYears($investmentInsertedAt, $currentDate);

        $taxPercentInDecimal = $this->getTaxValue($numberOfYears);

        $moneyToReduce = $this->calculateMoneyToReduceFromGain($taxPercentInDecimal, $gainValue);
        
        $totalValue = $this->calculateInvestmentReturnValue($totalValue, $moneyToReduce);

        return $totalValue;
    }
}