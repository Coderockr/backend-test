<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'Coderockr',
            'email' => 'demo@coderockr.com.br',
            'password' => bcrypt('1SafePassword*!!'),
        ]);

        User::factory()->count(50)->create();
        Event::factory()->count(250)->create();

        $this->call(
            FriendSeeder::class
        );
    }
}
