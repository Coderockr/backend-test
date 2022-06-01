<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Person\Models\Role;

class RoleSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $query = Role::factory()
                    ->count(1);
        $query->create();
    }
}
