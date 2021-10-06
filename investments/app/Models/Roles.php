<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Roles extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public $timestamps = true;

}
