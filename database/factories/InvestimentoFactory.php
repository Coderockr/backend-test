<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Investimento>
 */
class InvestimentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'investimento' => $this->faker->randomElement(['TESOURO_PRE', 'TESOURO_POS', 'CDB']),
            'investidor_id' => 1,
            'valor_inicial' => $this->faker->unique()->numberBetween(int1: 1, int2: 1000),
            'valor_final' => 0.00,
            'data_criacao' => $this->faker->date(),
            'data_retirada' => $this->faker->date(max: now())
        ];
    }
}
