<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Investment extends Model
{
    use HasUuids;
    use HasFactory;

    protected $casts = [
        'balance' => \App\Casts\Money::class . ':balance,currency',
        'investment_balance' => \App\Casts\Money::class . ':investment_balance,currency',
        'expected_balance' => \App\Casts\Money::class . ':expected_balance,currency',
        'create_date' => \App\Casts\Carbon::class . ':create_date',
        'last_applied_rate' => \App\Casts\Carbon::class . ':last_applied_rate',
        'status' => \App\Casts\InvestmentStatus::class . ':status'
    ];

    protected $fillable = [
        'name',
        'status',
        'create_date',
        'last_applied_rate',
        'balance',
        'investment_balance',
        'expected_balance',
        'investor_id'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function investor(): HasOne
    {
        return $this->hasOne(Investor::class, 'id', 'investor_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
