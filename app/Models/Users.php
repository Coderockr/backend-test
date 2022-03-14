<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $useTimestamps = true;
    protected $allowedFields    = [
        'id',
        'user_name',
        'useruser_email_name',
        'user_pass',
        'user_balance',
    ];

}
