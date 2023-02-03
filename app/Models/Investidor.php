<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investidor extends Model
{
    use HasFactory, UUID;
    protected $guarded = [];

    public function investimentos()
    {
        return $this->hasMany(Investimento::class, 'investidor_id');
    }
}