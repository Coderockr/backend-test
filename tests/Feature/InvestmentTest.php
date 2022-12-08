<?php

use App\Models\Investment;
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

test('asserts can get investment amount expected', function () {
    $investment = Investment::factory()->create();

    $this->json('get', '/api/investment', [
        'investment' => $investment->id
    ])->assertStatus(200);
});

test('asserts can return expected amount', function () {
    $owner = Owner::factory()->create();
    $investment = Investment::create([
        'initial_amount' => 1000,
        'creation_date' => '2022-01-11',
        'owner_id' => $owner->id
    ]);

    $percentage = 0.52;
    $startDate = Carbon::parse($investment->creation_date);
    $endDate = Carbon::parse();
    $monthsDiff = $startDate->diffInMonths($endDate);

    $gains = $investment->initial_amount * ( (1 + $percentage/100) ** ($monthsDiff)) -  $investment->initial_amount;
    $taxes = $gains * 0.225;
    $expectedAmount = $investment->initial_amount + $gains - $taxes;

    $result = $this->json('get', '/api/investment', [
        'investment' => $investment->id
    ]);

    $this->assertEquals($expectedAmount, $result->original['expected_amount']);
});

test('asserts cannot withdrawal investment twice', function () {
    $investment = Investment::factory()->create();

    $this->json('post', '/api/withdrawal', [
        'investment' => $investment->id,
        'withdrawal_date' => date('Y-m-d')
    ]);

    //tried withdrawal again after done it before
    $this->json('post', '/api/withdrawal', [
        'investment' => $investment->id,
        'withdrawal_date' => date('Y-m-d')
    ])->assertStatus(401);
});

test('asserts cannot withdrawal with invalid date', function () {
    $investment = Investment::create([
        'initial_amount' => 100,
        'creation_date' =>  date('Y-m-d'),
        'owner_id' => Owner::factory()->create()->id
    ]);

    $yesterday = strtotime('-1 day');
    $this->json('post', '/api/withdrawal', [
        'investment' => $investment->id,
        'withdrawal_date' => date('Y-m-d', $yesterday)
    ])->assertStatus(401);
});