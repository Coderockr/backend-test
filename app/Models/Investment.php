<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use SoftDeletes;

    const MONTH_GAIN_VALUE = 0.0052;

    const TAX_INVESTMENT_LESS_THAN_ONE_YEAR = 0.225;
    const TAX_INVESTMENT_BETWEEN_ONE_AND_TWO_YEARS = 0.185;
    const TAX_INVESTMENT_MORE_THAN_TWO_YEARS = 0.15;

    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'inserted_at'
    ];

    /**
     * Get the user that owns the investment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
