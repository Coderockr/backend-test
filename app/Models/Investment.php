<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'user_id'
    ];

    public function profits(): HasMany
    {
        return $this->hasMany(Profit::class);
    }

    public function getTotalAmount()
    {
        $profits = $this->profits()->sum('amount');

        return $this->amount + $profits;
    }
}
