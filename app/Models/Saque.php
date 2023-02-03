<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saque extends Model
{
    use HasFactory, UUID;
    protected $guarded = [];

    public function investimento()
    {
        return $this->belongsTo(Investimento::class, "investimento_id");
    }
}