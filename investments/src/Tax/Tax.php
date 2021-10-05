<?php

namespace Investment\Tax;

use Investment\Investment;

use Investment\Tax\TaxOneYear;
use Investment\Tax\TaxTwoYears;
use Investment\Tax\TaxMorerThanTwoYears;
use Investment\Tax\TaxAmount;
use Carbon\Carbon;

class Tax
{
    static function calculate($investmentDate, $withdrawalDate, $investmentAmount, $investmentGain, $balance, $withdrawalAmount)
    {
        $taxAmount = self::tax($investmentDate, $withdrawalDate, $investmentAmount, $investmentGain, $balance, $withdrawalAmount);
        $taxPercentage = self::taxPercentage($investmentDate, $withdrawalDate);

        return [
            'taxGain'        => $taxAmount['taxGain'],
            'applyTaxOn'     => $taxAmount['applyTaxOn'],
            'taxPercentage'  => $taxPercentage,
            'taxAmountToPay' => self::applytaxPercentage($taxAmount['applyTaxOn'], $taxPercentage)
        ];
    }

    static function applytaxPercentage($amount, $percent)
    {
        return formatFloat(divide100(($amount * $percent)));
    }

    static function tax($investmentDate, $withdrawalDate, $investmentAmount, $investmentGain, $balance, $withdrawalAmount)
    {
        return (new TaxAmount)->calculate($investmentDate, $withdrawalDate, $investmentAmount, $investmentGain, $balance, $withdrawalAmount);
    }

    static function taxPercentage($investmentDate, $withdrawalDate)
    {
        $initialDate = Carbon::create($investmentDate);
        $finalDate = Carbon::create($withdrawalDate);

        if ($initialDate->gte($finalDate)) {
            throw new \Exception('Initial date greater than final date');
        }

        if ($finalDate->lte($initialDate)) {
            throw new \Exception('final date  lower than initial date');
        }

        $lessThanYear = new TaxOneYear($initialDate, $finalDate);
        $moreTwoYears = new TaxMorerThanTwoYears($initialDate, $finalDate);
        $taxTwoyears = new TaxTwoYears($initialDate, $finalDate);

        $moreTwoYears->setNext($taxTwoyears);
        $lessThanYear->setNext($moreTwoYears);

        return $lessThanYear->handle();
    }
}
