<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    protected function details(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => [
                'initial_amount' =>  $attributes['initial_amount'],
                'final_amount' => $attributes['final_amount'],
                'creation_date' => $attributes['creation_date'],
                'gains_at_the_moment' => $attributes['gains']
            ]
        );
    }
}
