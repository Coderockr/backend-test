<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email'
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
