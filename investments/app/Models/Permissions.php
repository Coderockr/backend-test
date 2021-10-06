<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Permissions extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'role_id',
    ];

    public $timestamps = true;

    protected $primaryKey = ['user_id', 'role_id'];
}
