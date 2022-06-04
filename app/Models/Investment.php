<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    const GAIN_VALUE = 0.0052;

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
