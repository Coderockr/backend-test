<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Investment>
 */
class InvestmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        return [
            'value' => $faker->numberBetween(100, 1000000),
            'investment_timestamp' => $faker->dateTime(),
            'withdraw_timestamp' => $faker->randomElement([null, new \DateTime()]),
            'investor_user_id' => \App\Models\InvestorUser::all()->random(1)->first()->id,
        ];
    }
}
