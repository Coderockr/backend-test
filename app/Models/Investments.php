<?php

namespace App\Models;

use CodeIgniter\Model;

class Investments extends Model
{
    protected $table            = 'investments';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;

    protected $allowedFields    = [
        'id',
        'transaction_user_id',
        'transaction_investiment_id',
        'transaction_type',
        'transaction_ammount',
        'transaction_date',
    ];


}
