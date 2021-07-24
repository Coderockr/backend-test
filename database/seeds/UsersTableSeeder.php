<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // table data reset
        \DB::table('users')->delete();

        // Insert a user
        factory(User::class)->create(
            [
                'name' => 'Alex Junior Gutler',
                'email' => 'alex@email.com',
                'password' => bcrypt('321456'),
                'city' => 'Santa Teresa',
                'state' => 'ES'
            ]
        );

        // Insert a user
        factory(User::class)->create(
            [
                'name' => 'Simone Traspadini',
                'email' => 'simone@email.com',
                'password' => bcrypt('654123'),
                'city' => 'Santa Maria de Jetibá',
                'state' => 'ES'
            ]
        );
    }
}
