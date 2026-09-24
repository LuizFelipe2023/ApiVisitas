<?php

namespace Database\Factories;

use App\Models\Colaborador;
use App\Models\Visitante;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitacaoVisitaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'visitante_id' => Visitante::factory(), 
            'colaborador_id' => Colaborador::factory(), 
            'data_hora' => $this->faker->dateTimeBetween('now', '+30 days'),
            'motivo' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pendente', 'aprovada', 'recusada']),
        ];
    }
}