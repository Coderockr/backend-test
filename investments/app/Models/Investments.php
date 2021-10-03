<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Investments extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'users_id', 'amount','date', 'status'
    ];

    public $timestamps = true;




}
