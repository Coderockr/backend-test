<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
