<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'email_verified_at' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
                'password' => $faker->password,
                'created_at' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
                'updated_at' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
            ]);
        }
    }
}

