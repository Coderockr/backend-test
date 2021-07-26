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
        // Create primary user account for testing.
        User::create([
            'name' => 'User Test',
            'email' => 'user@test.dev',
            'password' => bcrypt('password'),
            'city' => 'Santa Teresa',
            'state' => 'ES'
        ]);

        // Create another five user accounts.
        factory(User::class, 5)->create();

        $this->command->info('Users table seeded.');
    }
}
