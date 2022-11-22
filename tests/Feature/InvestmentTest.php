<?php

use App\Models\Owner;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Faker\faker;
uses(RefreshDatabase::class);

test('asserts can create investment', function () {
    $owner = Owner::factory()->create();
    $this->json('post', '/api/investment', [
        'creation_date' => Carbon::parse(faker()->dateTimeBetween())->format('Y-m-d'),
        'email' => $owner->email,
        'amount' => faker()->numberBetween(0, random_int(100,20000))
    ])
        ->assertStatus(201);
});

test('asserts cannot create investment with no owner registered', function () {
    $this->json('post', '/api/investment', [
        'creation_date' => Carbon::parse(faker()->dateTimeBetween())->format('Y-m-d'),
        'email' => faker()->email,
        'amount' => faker()->numberBetween(0, random_int(100,20000))
    ])
        ->assertStatus(422);
});

test('asserts cannot create investment with negative amount', function () {
    $owner = Owner::factory()->create();
    $this->json('post', '/api/investment', [
        'creation_date' => Carbon::parse(faker()->dateTimeBetween())->format('Y-m-d'),
        'email' => $owner->email,
        'amount' => faker()->numberBetween(random_int(-10000,-1), 0)
    ])
        ->assertStatus(422);
});

test('asserts cannot create investment with invalid date', function () {
    $owner = Owner::factory()->create();

    // Invalid range in date
    $this->json('post', '/api/investment', [
        'creation_date' => Carbon::parse(faker()->dateTimeBetween('now', '+1 year'))->format('Y-m-d'),
        'email' => $owner->email,
        'amount' => faker()->numberBetween(random_int(-10000,-1), 0)
    ])
        ->assertStatus(422);

    // Invalid date format
    $this->json('post', '/api/investment', [
        'creation_date' => Carbon::parse(faker()->dateTimeBetween())->format('d-m-Y'),
        'email' => $owner->email,
        'amount' => faker()->numberBetween(random_int(-10000,-1), 0)
    ])
        ->assertStatus(422);
});