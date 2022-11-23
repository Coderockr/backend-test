<?php

namespace Database\Factories;

use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Investment>
 */
class InvestmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'initial_amount' => fake()->numberBetween(rand(100, 20000)),
            'creation_date' => fake()->dateTimeBetween('-10 years', '-1 day'),
            'owner_id' => Owner::factory()->create()->id
        ];
    }
}
