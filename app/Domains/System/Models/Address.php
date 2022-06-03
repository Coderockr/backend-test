<?php

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'public.address';

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cep', 'street', 'number', 'complement', 'district', 'city', 'uf'
    ];
}