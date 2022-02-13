<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{
    use HasFactory;

    protected $table = 'investimento';
    protected $fillable = [
        'id',
        'valor',
        'cpf_investidor',
        'data',
        'resgatado'
    ];

    public function rendimento() {
        return $this->hasMany(Investimento::class, 'investimento_id', 'id');
    }
}
