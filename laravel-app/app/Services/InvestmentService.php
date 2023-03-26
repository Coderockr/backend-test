<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Investment;

class InvestmentService
{
    private $investment;

    private const PERCENT_GAIN = 0.0052;

    private const RATES = [
        'LESS_THAN_A_YEAR' => 0.225,
        'BETWEEN_ONE_AND_TWO_YEARS' => 0.185,
        'OVER_TWO_YEARS' => 0.15,
    ];

    public function __construct(Investment $investment)
    {
        $this->investment = $investment;
    }

    public function calculateGain()
    {
        $investmentDate = Carbon::createFromDate($this->investment->investment_date);

        $day = $investmentDate->day;

        $numberOfMonths = $investmentDate->diffInMonths(Carbon::createFromDate(date("Y-m-{$day}")));

        $expectedBalance = $this->investment->invested_amount;

        for ($i=0; $i < $numberOfMonths; $i++):
            $expectedBalance += ($expectedBalance * self::PERCENT_GAIN);
        endfor;

        return round($expectedBalance, 2);
    }

    public function applyRate()
    {
        $investment = $this->investment;

        $investmentDate = Carbon::createFromDate($investment->investment_date);

        $gainWithDiscount = $investment->expected_balance - $investment->invested_amount;

        switch ($investmentDate->diffInYears()) {
            case 0:
                $gainWithDiscount -=  $gainWithDiscount * self::RATES['LESS_THAN_A_YEAR'];
                break;
            case 1:
                $gainWithDiscount -=  $gainWithDiscount * self::RATES['BETWEEN_ONE_AND_TWO_YEARS'];
                break;
            case 2:
                $gainWithDiscount -=  $gainWithDiscount * self::RATES['OVER_TWO_YEARS'];
                break;
            default:
                $gainWithDiscount -=  $gainWithDiscount * self::RATES['OVER_TWO_YEARS'];
                break;
        }

        return round($gainWithDiscount, 2);
    }
}
