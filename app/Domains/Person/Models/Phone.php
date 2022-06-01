<?php

namespace App\Domains\Person\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = 'public.phones';

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
