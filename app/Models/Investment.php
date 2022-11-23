<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'user_id',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    protected $appends = [
        'total_balance'
    ];

    public function profits(): HasMany
    {
        return $this->hasMany(Profit::class);
    }

    public function withdraws(): HasOne
    {
        return $this->hasOne(Withdraw::class);
    }

    public function getTotalAmount()
    {
        $profits = $this->profits()->sum('amount');

        return $this->amount + $profits;
    }

    public function getTotalBalanceAttribute()
    {
        $profits = $this->profits()->sum('amount');

        return $this->amount + $profits;
    }
}
