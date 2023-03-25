<?php

namespace App\Models;

use App\Models\Investment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function investments()
    {
        return $this->hasMany(Investment::class, 'owner_id');
    }
}
