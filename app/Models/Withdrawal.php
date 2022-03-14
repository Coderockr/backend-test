<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'investiment_id','value', 'value_no_tax', 'income', 'withdraw_date'];

    public function user()
    {
        return $this->hasbelogsTo(User::class);
    }

    public function investiment()
    {
        return $this->hasbelogsTo(Investiment::class);
    }

}
