<?php

namespace Database\Factories\User;

use App\Models\User\Investment;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestmentFactory extends Factory
{
    protected $model = Investment::class;

    public function definition(): array
    {
    	return [
    	    "value" => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 5000, $max = 75000),
            "created_at" => $this->faker->dateTimeThisDecade($max = 'now', $timezone = env('APP_TIMEZONE'))
    	];
    }
}
