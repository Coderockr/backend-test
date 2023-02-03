<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{
    use HasFactory, UUID;
    protected $guarded = [];

    public function investidor()
    {
        return $this->belongsTo(Investidor::class, "investidor_id");
    }

    public function saque()
    {
        return $this->hasMany(Saque::class, "investimento_id");
    }
}
