<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'bio' => $this->faker->sentence(6, true),
            'location' => $this->faker->streetAddress,
            'password' => $this->faker->password,
            'remember_token' => Str::random(10),
        ];
    }
}
