<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentMovement extends Model
{

    CONST TYPE_INITIAL = 1;
    CONST TYPE_GAIN = 2;
    CONST TYPE_TAX = 3;
    CONST TYPE_WITHDRAWN = 4;

    protected $fillable = ['id', 'investment_id', 'description', 'value', 'movement_at', 'type'];

    protected $dates = ['movement_at'];

    public $timestamps = false;

    public function investment(){
        return $this->belongsTo(Investment::class, 'investment_id');
    }
}
