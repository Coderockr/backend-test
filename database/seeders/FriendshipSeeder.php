<?php

namespace Database\Seeders;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($index = 1; $index <= 5; $index++) {
                $friendId = null;

                while (!$friendId || $friendId === $user->id) {
                    $friendId = rand(1, $users->count());
                }

                Friendship::create([
                    'user_id' => $user->id,
                    'friend_id' => $friendId,
                ]);
            }
        }
    }
}
