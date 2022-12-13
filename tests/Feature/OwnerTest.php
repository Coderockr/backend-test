<?php

use App\Models\Owner;
use function Pest\Faker\faker;

test('asserts owner created with success', function () {
    $this->json('post', '/api/owner', [
        'name' => faker()->name,
        'email' => faker()->email,
    ])
        ->assertStatus(201);
});

test('asserts cannot create owner with existing email', function () {
    $email = faker()->email;
    Owner::create([
        'name' => faker()->name,
        'email' => $email,
    ]);

    $this->json('post', '/api/owner', [
        'name' => faker()->name,
        'email' => $email,
    ])
        ->assertStatus(422);
});

test('asserts cannot create owner not sending email', function () {
    $this->json('post', '/api/owner', [
        'name' => faker()->name,
    ])
        ->assertStatus(422);
});

test('asserts cannot create owner not sending name', function () {
    $this->json('post', '/api/owner', [
        'email' => faker()->email,
    ])
        ->assertStatus(422);
});

test('asserts can create owner with existing name', function () {
    $name = faker()->name;
    Owner::create([
        'name' => $name,
        'email' => faker()->email,
    ]);

    $this->json('post', '/api/owner', [
        'name' => $name,
        'email' => faker()->email,
    ])
        ->assertStatus(201);
});
