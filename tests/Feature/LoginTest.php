<?php

use App\Models\User;

it('auth correctly', function () {
    $password = '1SafePassword*!!';
    $user = User::factory()->create(['password' => bcrypt($password)]);
    $payload = [
        'email' => $user->email,
        'password' => $password,
    ];

    $this->post(route('login'), $payload)
        ->assertStatus(200)
        ->assertJson([
            'name' => $user->name,
            'email' => $user->email,
        ])
        ->assertJsonStructure([
            'id', 'name', 'email', 'token', 'created_at', 'updated_at',
        ]);
});

it('require e-mail and password', function () {
    $this->post(route('register'))
        ->assertStatus(302)
        ->assertSessionHasErrors([
            'email', 'password',
        ]);
});

it('require valid credentials', function () {
    User::factory()->count(10)->create();
    $payload = [
        'email' => 'wrong@mail.com',
        'password' => '1WrongButSafePassword*!!',
    ];

    $this->post(route('login'), $payload)->assertStatus(401);
});
