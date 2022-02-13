<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rendimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'investimento_id',
        'data',
        'valor_rendimento'
    ];

    public function investimento() {
        return $this->belongsTo(Rendimento::class, 'investimento_id');
    }
}
