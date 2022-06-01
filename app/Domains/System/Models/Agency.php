<?php

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'public.agencies';

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name'
    ];
}