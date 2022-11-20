<?php

namespace App\Models;

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
}
