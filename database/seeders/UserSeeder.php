<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // populating users database
         DB::table('users')->insert([
            ['name' => 'Investor 1',
            'email' => 'investor1@test.com',
            'password' => Hash::make('12345678')],
            ['name' => 'Investor 2',
            'email' => 'investor2@test.com',
            'password' => Hash::make('12345678')],
            ['name' => 'Investor 2',
            'email' => 'investor3@test.com',
            'password' => Hash::make('12345678')]
        ]);
    }
}
