<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne as EloquentHasOne;
use App\Traits\HasCompoundInterestCalc;
use Carbon\Carbon;
use App\Models\User\Investment\WithdralTax;

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

        static::created(function($model){
            $model->withdralTaxes()->create();
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

    public function withdralTaxes(): EloquentHasOne
    {
        return $this->hasOne(WithdralTax::class, 'investment_id', 'id');
    }

    private function verifyWithdralTaxPercentage(): float
    {
        return  $this->withdralTaxes->withdralTaxPercentage($this->ageInMonths);
    }
}
