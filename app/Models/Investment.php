<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'id', 'person_id', 'description', 'gain', 'created_at', 'is_withdrawn', 'withdrawn_at', 'initial_investment'
    ];

    protected $dates = ['created_at', 'withdrawn_at'];

    public $timestamps = false;

    public function person(){
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function movements(){
        return $this->hasMany(InvestmentMovement::class, 'investment_id');
    }

    public function scopeWithdrawn($q)
    {
        return $q->where('is_withdrawn', 1);
    }

    public function getWithdrawn()
    {
        return $this->withdrawn()->get();
    }

    public function getExpectedBalanceAttribute(){

        if($this->is_withdrawn){
            return floatval($this->initial_investment - $this->movements()->sum('value'));
        }

        return floatval($this->movements()->sum('value'));
    }

    public function getInvestmentProfitAttribute(){
        return floatval($this->initial_investment - $this->movements()->sum('value'));
    }
}
