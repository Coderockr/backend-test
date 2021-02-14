<?php

use App\Models\User;
use function Pest\Faker\faker;

it('store correctly', function () {
    $payload = [
        'name' => 'Daniel Freitas',
        'email' => 'daniel@coderock.com.br',
        'bio' => 'Solution builder.',
        'location' => 'Lajeado/RS, Brasil',
        'password' => '1SafePassword*!!',
        'password_confirmation' => '1SafePassword*!!',
    ];

    $this->post(route('register'), $payload)
        ->assertStatus(201)
        ->assertJson([
            'name' => $payload['name'],
            'email' => $payload['email'],
        ])
        ->assertJsonStructure([
            'id', 'name', 'email', 'bio', 'location', 'picture', 'token', 'created_at', 'updated_at',
        ]);
});

it('require password confirmation', function () {
    $payload = [
        'name' => faker()->name,
        'email' => faker()->email,
        'password' => faker()->password(8, 10),
    ];

    $this->post(route('register'), $payload)
        ->assertStatus(302)
        ->assertSessionHasErrors(['password']);
});

it('require name, e-mail and password', function () {
    $this->post(route('register'))
        ->assertStatus(302)
        ->assertSessionHasErrors([
            'name', 'email', 'password',
        ]);
});

it('require an unique e-mail address', function () {
    User::factory()->create(['email' => 'duplicated@mail.com']);

    $payload = [
        'name' => faker()->name,
        'email' => 'duplicated@mail.com',
        'password' => faker()->password(8, 10),
    ];

    $this->post(route('register'), $payload)
        ->assertStatus(302)
        ->assertSessionHasErrors(['email']);
});

it('require an valid (format) e-mail address', function () {
    $payload = [
        'name' => faker()->name,
        'email' => 'invalid_email.com',
        'password' => faker()->password(8, 10),
    ];

    $this->post(route('register'), $payload)
        ->assertStatus(302)
        ->assertSessionHasErrors(['email']);
});

it('require a minimum length for name and password', function () {
    $payload = [
        'name' => 'ABC',
        'email' => faker()->email,
        'password' => '1234567',
    ];

    $this->post(route('register'), $payload)
        ->assertStatus(302)
        ->assertSessionHasErrors(['name', 'password']);
});
