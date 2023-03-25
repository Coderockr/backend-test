<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{
    use HasFactory;

    protected $table = "investimento";

    protected $fillable = [
        'investimento',
        'investidor_id',
        'valor_inicial',
        'valor_final',
        'data_criacao',
        'data_retirada',
        'valor_retirada',
        'valor_imposto',
        'taxa_imposto'
    ];

    public $timestamps = false;

    protected $dates = ['data_criacao'];

    // Relacionamentos
    public function investidor()
    {
        return $this->belongsTo(User::class, 'investidor_id');
    }
}
