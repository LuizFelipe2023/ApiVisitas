<?php

namespace Database\Seeders;

use App\Models\Colaborador;
use App\Models\Setor;
use App\Models\SolicitacaoVisita;
use App\Models\Visitante;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        $quantidadeSetores = rand(5, 10);
        $setores = Setor::factory($quantidadeSetores)->create();

        $colaboradores = Colaborador::factory(60)->create(function () use ($setores) {
            return [
                'setor_id' => $setores->random()->id,
            ];
        });

        $visitantes = Visitante::factory(30)->create();

        SolicitacaoVisita::factory(40)->create(function () use ($visitantes, $colaboradores) {
            return [
                'visitante_id' => $visitantes->random()->id,
                'colaborador_id' => $colaboradores->random()->id,
            ];
        });
    }
}