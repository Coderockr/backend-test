<?php

namespace Database\Seeders;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
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

                Friend::create([
                    'user_id' => $user->id,
                    'friend_id' => $friendId,
                ]);
            }
        }
    }
}
