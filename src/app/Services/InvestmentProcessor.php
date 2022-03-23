<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;

class InvestmentProcessor {


    public static function proccessInvestment($intValue, \DateTime $initialDate, \DateTime $withdrawDate) {
        //The investment will pay 0.52% every month in the same day of the investment creation.
        //Given that the gain is paid every month, it should be treated as compound gain, which means that every new period (month) the amount gained will become part of the investment balance for the next payment.
        
        if($withdrawDate==null) {
            return null;
        }
        $value = doubleval($intValue);
        
        Log::debug("InvestmentProcessor->proccessInvestment->Initial value: " . $intValue);

        $months  = ($withdrawDate->getTimestamp()  - $initialDate->getTimestamp())/60/60/24/30;

        Log::debug("InvestmentProcessor->proccessInvestment->Months passed: " . $months);

        for ($m = 1; $m <= $months; $m++) {
            $value = $value * 1.0052;
        }
        
        Log::debug("InvestmentProcessor->proccessInvestment->Processed value: " . $value);

        return intval($value);
    }
}