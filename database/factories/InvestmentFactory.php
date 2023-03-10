<?php

namespace Database\Factories;

use App\Models\Investment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

class InvestmentFactory extends Factory
{
    protected $model = Investment::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'status' => $this->faker->randomElement(['ACTIVE', 'WITHDRAWN']),
            'create_date' => new Carbon($this->faker->dateTime()),
            'last_applied_rate' => new Carbon($this->faker->dateTime()),
            'balance' => Money::BRL($this->faker->numberBetween(500, 1000)),
            'investment_balance' => Money::BRL(0),
            'expected_balance' => Money::BRL(0)
        ];
    }
}
