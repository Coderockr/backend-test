<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'investment_id', 'gain_real', 'updated_value', 'created_at'
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
