<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\Investment;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        \App\Models\User::factory(10)
        ->create()
        ->each(function($user) {
            $user->investments()->saveMany(Investment::factory(1)->make());
        });
    }
}
