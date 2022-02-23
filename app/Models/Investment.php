<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $table = 'investments';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'initial_investment',
        'investor_id',
        'investment_reference_date',
        'created_at',
        'updated_at'
    ];
}
