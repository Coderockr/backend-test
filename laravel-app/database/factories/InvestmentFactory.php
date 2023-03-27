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
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'investment_date' => $this->faker->dateTimeBetween('-8 months')->format('Y-m-d'),
            'invested_amount' => $this->faker->numerify('####.##'),
            'status' => 'ACTIVE',
            'owner_id' => \App\Models\Owner::factory(),
        ];
    }
}
