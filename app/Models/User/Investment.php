<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Casts\{ Money, PreventFutureDate };

class Investment extends Model
{

    const UPDATED_AT = null;

    protected $fillable = [
        'created_at',
        'name',
        'value',
    ];

    protected $casts = [
        'created_at' => PreventFutureDate::class,
        'value' => Money::class,
    ];

}
