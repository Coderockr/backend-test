<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'person_id', 'initial_value', 'created_at', 'gain', 'withdraw'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function moviments()
    {
        return $this->hasMany(Movement::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdraw::class);
    }
}
