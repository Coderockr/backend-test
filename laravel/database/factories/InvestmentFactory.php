<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvestmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner' => rand(1, 5),
            'creation' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'initial_amount' => rand(100, 10000)
        ];
    }
}
