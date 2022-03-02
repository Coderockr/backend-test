<?php

namespace App\Models\User\Investment;

use Illuminate\Database\Eloquent\Model;

class WithdralTax extends Model
{

    protected $table = 'investment_withdral_taxes';

    const WITHDRAWN_TAX_PERCENTAGE = [
        'LESS_THAN_ONE'         => 22.5, 
        'BETWEEN_ONE_AND_TWO'   => 18.5,
        'OLDER_THAN_TWO'        => 15
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {

            $taxes = self::WITHDRAWN_TAX_PERCENTAGE;

            $model->less_than_one       = $taxes['LESS_THAN_ONE'];
            $model->between_one_and_two = $taxes['BETWEEN_ONE_AND_TWO'];
            $model->older_than_two      = $taxes['OLDER_THAN_TWO'];
        });
    }

    public function withdralTaxPercentage(int $ageInMonths): float
    {
        $age = $ageInMonths;
        $currentTax = null;

        switch ($age) {

            case $age >= 24 :
                $currentTax = $this->older_than_two;
                break;

            case $age >= 12 && $age < 24:
                $currentTax = $this->between_one_and_two;
                break;

            case $age < 12:
                $currentTax = $this->less_than_one;
                break;
        }

        return $currentTax;
    }

}
