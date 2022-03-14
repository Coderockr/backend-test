<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Investiment;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestimentFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Investiment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'value' => $this->faker->numerify('####.##'),
            'investiment_date' => '2022-03-01'
        ];
    }
}
