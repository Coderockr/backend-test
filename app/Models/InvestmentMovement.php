<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentMovement extends Model
{
    protected $fillable = ['id', 'investment_id', 'description', 'value', 'movement_at'];

    protected $dates = ['movement_at'];

    public $timestamps = false;

    public function investment(){
        return $this->belongsTo(Investment::class, 'investment_id');
    }
}
