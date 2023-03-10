<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $casts = [
        'actual_balance' => \App\Casts\Money::class . ':actual_balance,currency',
        'final_balance' => \App\Casts\Money::class . ':final_balance,currency',
        'actual_investment_balance' => \App\Casts\Money::class . ':actual_investment_balance,currency',
        'final_investment_balance' => \App\Casts\Money::class . ':final_investment_balance,currency',
        'type' => \App\Casts\TypeTransaction::class . ':type',
        'from' => \App\Casts\OriginTransaction::class . ':from',
    ];

    protected $fillable = [
        'from',
        'type',
        'actual_balance',
        'final_balance',
        'actual_investment_balance',
        'final_investment_balance',
        'rate_applied'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
