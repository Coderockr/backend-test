<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use Faker\Factory as Faker;
use App\Models\Roles;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createFakeUser(10);
    }
    
    public function createFakeUser($total)
    {
        Roles::insertOrIgnore(
            [
                ['name' => 'admin', 'description'=> 'Administrator of accounts'],
                ['name' => 'customer', 'description' => 'User'],
            ]
        );
    }
}

