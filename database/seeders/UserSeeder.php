<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Person\Models\User;

class UserSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $query = User::factory()
                    ->count(10);
        $query->create();
    }
}
