<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    public $timestamps = false;

    protected $table = 'movement';
    protected $fillable = [
        'investment_id', 'real_gain', 'updated_value'
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
