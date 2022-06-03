<?php

namespace App\Domains\Log\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log.logs';

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'event'
    ];

    protected $casts = [
        'event' => 'array'
    ];
}