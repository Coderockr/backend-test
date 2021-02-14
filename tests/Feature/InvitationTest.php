<?php

use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use function Pest\Faker\faker;

it('list correctly', function () {
    $user = User::factory()->create();
    Invitation::factory()->count(2)->create(['user_id' => $user->id]);

    $this->actingAs($user, 'api')
        ->get(route('invitations'))
        ->assertStatus(200)
        ->assertJson([
            ['user_id' => $user->id],
            ['user_id' => $user->id],
        ])
        ->assertJsonStructure([
            '*' => [
                'id', 'user_id', 'email', 'user', 'created_at', 'updated_at',
            ],
        ]);
});

it('store correctly', function () {
    $user = User::factory()->create();
    $payload = ['email' => faker()->email];

    $this->actingAs($user, 'api')
        ->post(route('invitations.store'), $payload)
        ->assertStatus(201)
        ->assertJson($payload);
});

it('send invites by email', function () {
    Mail::fake();

    $user = User::factory()->create();
    $payload = ['email' => faker()->email];

    $this->actingAs($user, 'api')->post(route('invitations.store'), $payload);

    Mail::assertSent(InvitationMail::class);
});
