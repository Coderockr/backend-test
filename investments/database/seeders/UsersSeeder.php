<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
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
        $faker = Faker::create();
        $usersIds = [];
        foreach (range(1, $total) as $index) {
            $usersIds [] = User::create(
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'email_verified_at' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
                    'password' => $faker->password,
                    'created_at' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
                    'updated_at' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
                ]
            );
        }

        return $usersIds;
    }
}

