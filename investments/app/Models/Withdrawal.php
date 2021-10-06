<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Withdrawal extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id', 'amount', 'date'
    ];

    public $timestamps = true;


}
