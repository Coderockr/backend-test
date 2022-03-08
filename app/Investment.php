<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{

    protected $fillable = [
        'investor_id',
        'amount_start',
        'amount_total',
        'date_creation',
        'date_withdraw',
        'withdrawn',
        'gain',
        'tax',
        'amount_total',
        'amount_withdrawn'
    ];

    protected $hidden = [];

}