<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Casts\{ Money, PreventFutureDate, WithdrawnDate };

class Investment extends Model
{

    const UPDATED_AT = null;

    const MONTHLY_GAIN_PERCENT = 0.0052; //0.52% montly gain

    const WITHDRAWN_TAX_PERCENTAGE = [
        'LESS_THAN_ONE'         => 22.5, 
        'BETWEEN_ONE_AND_TWO'   => 18.5,
        'OLDER_THAN_TWO'        => 15
    ];

    protected $fillable = [
        'created_at',
        'name',
        'value',
    ];

    protected $casts = [
        'created_at'    => PreventFutureDate::class,
        'value'         => Money::class,
        'withdrawn_at'  => WithdrawnDate::class,
    ];

    protected $appends = [
        'age_in_months',
        'current_value',
        'real_gain',
        'withdrawn_value',
        'withdrawn_tax_percentage'
    ];

    public function getAgeInMonthsAttribute(): int
    {
        $ageParam = $this->withdrawn ? $this->withdrawn_at : Carbon::now()->toDateTimeString();
        return Carbon::parse($this->attributes['created_at'])->diffInMonths($ageParam);
    }

    public function getCurrentValueAttribute(): string
    {
        return number_format($this->calcCurrentValue(), 2, ',', '.');
    }

    public function getRealGainAttribute(): string
    {
        return number_format($this->calcRealGain(), 2, ',', '.');
    }

    public function getWithdrawnValueAttribute(): string
    {
        return number_format($this->calcWithdrawnValue(), 2, ',', '.');
    }

    public function getWithdrawnTaxPercentageAttribute(): string
    {
       return $this->verifyWithdrawnTaxPercentage().'%';
    }
    
    public function setAsWithdrawn(?string $dateTime = null): bool
    {
        if($this->withdrawn)
            return false;

        $this->withdrawn = true;
        $this->withdrawn_at = $dateTime ?? Carbon::now()->toDateTimeString();

        return $this->save();
    }

    private function calcCurrentValue(): float
    {
        return $this->attributes['value'] * pow((1 + self::MONTHLY_GAIN_PERCENT), $this->ageInMonths);
    }

    private function calcRealGain(): float
    {
        return $this->calcCurrentValue() - $this->attributes['value'];
    }

    private function calcWithdrawnValue(): float
    {
        return $this->calcCurrentValue() - $this->calcWithdrawnTaxes();        
    }

    private function calcWithdrawnTaxes(): float
    {
        return ($this->verifyWithdrawnTaxPercentage() / 100) * $this->calcRealGain();
    }

    private function verifyWithdrawnTaxPercentage(): float
    {
        $age = $this->ageInMonths;
        $taxes = self::WITHDRAWN_TAX_PERCENTAGE;
        $currentTax = null;

        switch ($age) {

            case $age >= 24 :
                $currentTax = $taxes['OLDER_THAN_TWO'];
                break;

            case $age >= 12 && $age < 24:
                $currentTax = $taxes['BETWEEN_ONE_AND_TWO'];
                break;

            case $age < 12:
                $currentTax = $taxes['LESS_THAN_ONE'];
                break;
        }

        return $currentTax;
    }
}
