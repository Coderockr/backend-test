<?php

namespace Database\Factories\Domains\Person\Models;

use App\Domains\Person\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' =>$this->faker->name, 
            'type' => 1,
            'person' => false,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
