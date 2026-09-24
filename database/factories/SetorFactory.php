<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SetorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => 'Setor de ' . ucfirst($this->faker->unique()->word()),
            'sigla' => strtoupper($this->faker->unique()->lexify('???')),
            'telefone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
        ];
    }
}