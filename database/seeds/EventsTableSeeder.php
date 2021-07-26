<?php

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
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

        $seeded = false;
        if ($user) {
            $faker = Faker\Factory::create('pt_BR');
            $invitations_status = array_keys((new EventInvitation())->getStatusArray());

            // generate the events and invitations for the user test
            $this->generateUserEvents($user, $faker, $invitations_status, 3);

            // verify if the user test have friends
            $user_friends = $user->friendsIdsArray;
            if ($user_friends) {
                // get a friend of the user
                $otheUser = User::where('id', '<>', $user->id)->whereIn('id', $user_friends)->first();

                if ($otheUser) {
                    // generate the events and invitations for the user test friend
                    $this->generateUserEvents($otheUser, $faker, $invitations_status, 2);
                }
            }

            $seeded = true;
        }

        if ($seeded) {
            $this->command->info('EventsTableSeeder seeded.');
        } else {
            $this->command->error('EventsTableSeeder not seeded.');
        }
    }

    /**
     * Generate events and invitations for the user.
     *
     * @param User $user
     * @param \Faker\Generator $faker
     * @param array $invitations_status
     * @param int $num_events
     */
    public function generateUserEvents(User $user, Faker\Generator $faker, array $invitations_status, $num_events = 1)
    {
        $user_friends = $user->friendsIdsArray;

        factory(Event::class, $num_events)->create(['owner_id' => $user->id])->each(function($e) use($user_friends, $user, $invitations_status, $faker) {
            foreach ($user_friends as $friend) {
                $e->invitations()->create([
                    'user_id' => $user->id,
                    'guest_id' => $friend,
                    'status' => $invitations_status[$faker->numberBetween(0, count($user_friends)-1)]
                ]);
            }
        });
    }
}
