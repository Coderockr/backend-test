<?php

namespace App\Domains\Investment\Models;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    protected $table = 'investment.moves';

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active','type', 'number', 'person_id', 'company_id'
    ];
}
