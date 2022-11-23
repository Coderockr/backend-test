<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'investment_id',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];
}
