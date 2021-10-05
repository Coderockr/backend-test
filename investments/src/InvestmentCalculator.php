<?php

namespace Investment;

use Investment\ProfitCalculator;
use investment\Investment;

class InvestmentCalculator
{
    public function investmentBalance(Investment $investment)
    {
        $profit = new ProfitCalculator();
        //// Qual é o balance?

        $balance = $profit->calculate(
            $investmentDate,
            $investmentAmount
        );

        ///// se o withdrawamount for maior q o balance lance erro

        //// se não vamos registrar o withdraw
    }

    
}

