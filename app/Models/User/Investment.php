<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasCompoundInterestCalc;
use Carbon\Carbon;
use App\Casts\{ 
    Money, 
    PreventFutureDate, 
    WithdrawnDate,
    WithdrawnStatus,
};

class Investment extends Model
{

    use HasFactory, HasCompoundInterestCalc;

    const UPDATED_AT = null;

    const INTEREST_RATE = 0.52; // %

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
        'withdrawn'     => WithdrawnStatus::class,
    ];

    protected $appends = [
        'age_in_months',
        'current_value',
        'interest_income',
        'withdral_value',
        'withdral_tax_percentage',
        'withdral_tax',
        'interest_rate_percent',
    ];

    protected $hidden = [
        'interest_rate'
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            $model->interest_rate = $model::INTEREST_RATE;
        });
    }

    public function getAgeInMonthsAttribute(): int
    {
        $ageParam = $this->withdrawn ? $this->withdrawn_at : Carbon::now()->toDateTimeString();
        return Carbon::parse($this->attributes['created_at'])->diffInMonths($ageParam);
    }

    public function getCurrentValueAttribute(): string
    {
        return number_format($this->calcCurrentValue(), 2, ',', '.');
    }

    public function getInterestIncomeAttribute(): string
    {
        return number_format($this->calcInterestIncome(), 2, ',', '.');
    }

    public function getWithdralValueAttribute(): string
    {
        return number_format($this->calcWithdralValue(), 2, ',', '.');
    }

    public function getWithdralTaxPercentageAttribute(): string
    {
       return $this->verifyWithdralTaxPercentage().'%';
    }

    public function getWithdralTaxAttribute(): string
    {
        return number_format($this->calcWithdralTaxes(), 2, ',', '.');
    }

    public function getInterestRatePercentAttribute(): string
    {
        return $this->interest_rate.'%';
    }
    
    public function setAsWithdrawn(?string $dateTime = null): bool
    {
        if($this->withdrawn)
            return false;

        $this->withdrawn = true;
        $this->withdrawn_at = $dateTime ?? Carbon::now()->toDateTimeString();

        return $this->save();
    }

    private function verifyWithdralTaxPercentage(): float
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
