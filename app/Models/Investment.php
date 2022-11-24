<?php

namespace App\Models;

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

    public function getInvestmentProfitAttribute(){

        if($this->movements()->count() > 0){
            $profit = $this->movements()->where('type', '=',InvestmentMovement::TYPE_GAIN)
                ->sum('value');

            return floatval($profit);
        }

        return 0;

    }

    public function getBalanceAttribute(){

        if($this->is_withdrawn){
            return $this->investment_profit;
        }

        return floatval($this->movements()->sum('value'));
    }

}
