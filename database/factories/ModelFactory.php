<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function(Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt(Str::random(8)),
        'city' => $faker->city,
        'state' => $faker->stateAbbr
    ];
});

$factory->define(Event::class, function(Faker $faker) {
    return [
        'name' => $faker->sentence(5),
        'description' => $faker->sentence(12),
        'date' => $faker->dateTimeBetween( date('Y-m-d'), date('Y-m-t', strtotime('+1 months')) )->format('Y-m-d'),
        'time' => Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->addHours( $faker->numberBetween(1, 8) )->format('H:00'),
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'status' => 'pending'
    ];
});