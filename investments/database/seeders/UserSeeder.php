<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Permissions;
use Illuminate\Support\Facades\Hash;
use App\Models\Roles;
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
        $this->createUsers();
        // $this->createFakeUser(10);
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


    public function createUsers()
    {
        $faker = Faker::create();

        foreach (['admin', 'customer'] as $role) {
            $userId = User::create(
                [
                    'name' => $role,
                    'email' => $role . '@email.com',
                    'email_verified_at' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
                    'password' => Hash::make('passwd123'),
                    'created_at' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
                    'updated_at' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
                ]
            );

            $roleId = (Roles::select('id')->where('name', '=', $role)->get()->toArray())[0];

            if ($roleId) {
                Permissions::insert(
                    ['users_id' => $userId->id, 'roles_id' => $roleId['id']]
                );
            }
        }
    }
}

