<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
//use Faker\Generator;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvestorUser>
 */
class InvestorUserFactory extends Factory
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
            'name' => $faker->name(),
        ];
    }
}
