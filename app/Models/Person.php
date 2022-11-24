<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';

    protected $fillable = ['id', 'first_name', 'last_name', 'ssn', 'email'];

    protected $dates = ['created_at', 'updated_at'];

    public function investments(){
        return $this->hasMany(Investment::class, 'person_id');
    }
}
