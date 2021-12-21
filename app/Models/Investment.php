<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'investment_amount', 'investment_date', 'withdrawal_amount', 'withdrawal_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
