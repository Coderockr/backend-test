<?php

namespace App\Domains\Person\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'public.accounts';

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number', 'person_id'
    ];

}
