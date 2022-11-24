<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    public $timestamps = true;
    protected $table = 'withdraw';
    protected $fillable = [
        'investment_id', 'date', 'tax', 'tax_value'
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
