<?php

namespace App\Models;

use App\Models\Owner;
use App\Enums\InvestmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investment extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'status' => InvestmentStatus::class
    ];

    public function owner()
    {
        return $this->hasOne(Owner::class,'id','owner_id');
    }
}

