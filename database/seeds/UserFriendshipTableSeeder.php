<?php

use App\Models\Friendship;
use App\Models\FriendshipInvitation;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserFriendshipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the test user
        $user = User::where('email', 'user@test.dev')->first();
        // $faker = Faker\Factory::create('pt_BR');
        $seeded = false;

        if ($user) {
            // get other five users
            $users = User::select('id')->where('id', '<>', $user->id)->orderBy('id')->get()->take(5);

            if ($users->count() == 5) {
                $now = now();

                $friendship_data = [];
                // Format friendship data with three of the other users for the user
                foreach ($users->take(3) as $friend) {
                    $friendship_data[] = [
                        'user_id' => $user->id,
                        'friend_id' => $friend->id,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }

                // Store the user test friendship
                Friendship::insert($friendship_data);


                $invitation_data = [];
                // Format pending friendship invitation data with the other two users for the user
                foreach ($users->sortByDesc('id')->take(2) as $friend) {
                    $invitation_data[] = [
                        'guest_id' => $user->id,
                        'user_id' => $friend->id,
                        'status' => 'pending',
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }

                // Store the user test friendship pending invitations
                FriendshipInvitation::insert($invitation_data);

                $seeded = true;
            }
        }

        if ($seeded) {
            $this->command->info('UserFriendshipTableSeeder seeded.');
        } else {
            $this->command->error('UserFriendshipTableSeeder not seeded.');
        }
    }

}
