<?php

namespace Investment\Tax;


class TaxAmount
{
    public function calculate($investmentDate, $withdrawalDate, $investmentAmount, $investmentGain, $balance, $withdrawalAmount)
    {
        $taxGain = ($investmentGain / $balance);

        return [
            'taxGain' => $taxGain,
            'applyTaxOn' => $withdrawalAmount * $taxGain
        ];
    }
}
