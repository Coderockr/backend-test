<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class User extends Seeder
{
    public function run()
    {
        helper('apoio');

        $data = [
            'id' => getToken(4),
            'user_name' => 'rockr',
            'user_email'    => 'rockr@example.com',
            'user_pass'    => sha1('admin'),
            'user_balance'    => 10000,
        ];
        $this->db->table('users')->insert($data);
    }
}
