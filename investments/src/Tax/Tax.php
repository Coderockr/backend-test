<?php

namespace Investment\Tax;

use Investment\Investiment;

use Investment\Tax\TaxLessthanYear;
use Investment\Tax\TaxOlderThanTwoYears;
use Investment\Tax\TaxTwoYears;
use Carbon\Carbon;

class Tax
{
    static function calculate($investmentDate, $withdrawalDate)
    {
        $initialDate = Carbon::create($investmentDate);
        $finalDate = Carbon::create($withdrawalDate);

        $lessThanYear = new TaxLessthanYear($initialDate, $finalDate);
        $twoYears = new TaxOlderThanTwoYears($initialDate, $finalDate);
        $taxTwoyears = new TaxTwoYears($initialDate, $finalDate);

        $lessThanYear->setNext(
            $twoYears->setNext(
                $taxTwoyears
            )
        );

        return $lessThanYear->handle();
    }
}
