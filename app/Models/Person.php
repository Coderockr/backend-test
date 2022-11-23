<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email'
    ];

    public function investmests()
    {
        return $this->hasMany(Investment::class);
    }
}
