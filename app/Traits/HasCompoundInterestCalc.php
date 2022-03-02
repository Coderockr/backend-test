<?php

namespace App\Traits;

trait HasCompoundInterestCalc
{

    public function getAgeInMonthsAttribute(): int
    {
        return 0;
    }

    private function verifyWithdrawnTaxPercentage(): float
    {
        return 0.00;
    }

    private function valueField(): float
    {
        return $this->attributes['value'];
    }

    private function interestRateField(): float
    {
        return $this->attributes['interest_rate'];
    }

    private function calcCurrentValue(): float
    {
        return $this->valueField() * pow((1 + ($this->interestRateField() / 100)), $this->ageInMonths);
    }

    private function calcInterestIncome(): float
    {
        return $this->calcCurrentValue() - $this->valueField();
    }

    private function calcWithdrawnValue(): float
    {
        return $this->calcCurrentValue() - $this->calcWithdrawnTaxes();        
    }

    private function calcWithdrawnTaxes(): float
    {
        return ($this->verifyWithdrawnTaxPercentage() / 100) * $this->calcInterestIncome();
    }
}