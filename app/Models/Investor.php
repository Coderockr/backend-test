<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investor extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'name',
        'email'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }
}
