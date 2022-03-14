<?php

namespace App\Models;

use CodeIgniter\Model;

class Withdrawal extends Model
{
    protected $table            = 'withdrawal';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'id',
        'investment_id',
        'valor_investido',
        'total_meses',
        'taxa_sobre_lucro',
        'lucro',
        'valor_descontado_do_lucro',
        'lucro_ja_taxado',
        'saldo_final',
        'transaction_date',
    ];

}