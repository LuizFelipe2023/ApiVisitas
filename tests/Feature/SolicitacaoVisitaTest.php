<?php

namespace Tests\Feature;

use App\Models\Colaborador;
use App\Models\Setor;
use App\Models\SolicitacaoVisita;
use App\Models\Visitante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolicitacaoVisitaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateTestUser();
    }

    private function createVisitante(array $attributes = []): Visitante
    {
        return Visitante::create(array_merge([
            'nome' => 'Carlos Oliveira',
            'cpf' => '123.456.789-00',
            'telefone' => '(92) 99999-2222',
            'email' => 'carlos@example.com',
        ], $attributes));
    }

    private function createColaborador(array $attributes = []): Colaborador
    {
        $setor = Setor::factory()->create(['nome' => 'Informatica']);

        return Colaborador::create(array_merge([
            'nome' => 'Maria da Silva',
            'cpf' => '987.654.321-00',
            'data_de_nascimento' => '1990-05-20',
            'telefone' => '(92) 99999-1111',
            'email' => 'maria@example.com',
            'setor_id' => $setor->id,
        ], $attributes));
    }

    private function solicitacaoData(int $visitanteId, int $colaboradorId): array
    {
        return [
            'visitante_id' => $visitanteId,
            'colaborador_id' => $colaboradorId,
            'data_hora' => '2026-10-15 14:30:00',
            'motivo' => 'Reuniao de trabalho',
            'status' => 'pendente',
        ];
    }

    private function createSolicitacao(array $attributes = []): SolicitacaoVisita
    {
        $visitante = $this->createVisitante([
            'cpf' => '111.111.111-11',
        ]);
        $colaborador = $this->createColaborador([
            'cpf' => '222.222.222-22',
        ]);

        return SolicitacaoVisita::create(array_merge(
            $this->solicitacaoData($visitante->id, $colaborador->id),
            $attributes,
        ));
    }

    public function test_index_returns_solicitacoes_from_newest_to_oldest(): void
    {
        $oldest = $this->createSolicitacao([
            'data_hora' => '2026-10-15 10:00:00',
        ]);
        $oldest->colaborador->update(['cpf' => '333.333.333-33']);
        $oldest->created_at = now()->subMinute();
        $oldest->save();
        $newest = $this->createSolicitacao([
            'data_hora' => '2026-10-16 10:00:00',
        ]);

        $response = $this->getJson('/api/solicitacoes-visitas');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $newest->id)
            ->assertJsonPath('data.1.id', $oldest->id)
            ->assertJsonPath('data.0.status', 'pendente');
    }

    public function test_store_a_solicitacao_de_visita(): void
    {
        $visitante = $this->createVisitante();
        $colaborador = $this->createColaborador();

        $response = $this->postJson(
            '/api/solicitacoes-visitas',
            $this->solicitacaoData($visitante->id, $colaborador->id),
        );

        $response->assertCreated()
            ->assertJsonFragment(['nome' => 'Carlos Oliveira'])
            ->assertJsonFragment(['nome' => 'Maria da Silva'])
            ->assertJsonFragment(['status' => 'pendente'])
            ->assertJsonFragment(['message' => 'Solicitação de visita criada com sucesso']);

        $this->assertDatabaseHas('solicitacoes_visitas', [
            'visitante_id' => $visitante->id,
            'colaborador_id' => $colaborador->id,
            'status' => 'pendente',
        ]);
    }

    public function test_store_a_solicitacao_requires_valid_relations_and_date(): void
    {
        $response = $this->postJson('/api/solicitacoes-visitas', [
            'visitante_id' => 999999,
            'colaborador_id' => 999999,
            'data_hora' => 'data-invalida',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'visitante_id',
                'colaborador_id',
                'data_hora',
            ]);
    }

    public function test_return_a_solicitacao_with_its_relations(): void
    {
        $solicitacao = $this->createSolicitacao();

        $response = $this->getJson("/api/solicitacoes-visitas/{$solicitacao->id}");

        $response->assertOk()
            ->assertJsonFragment(['nome' => 'Carlos Oliveira'])
            ->assertJsonFragment(['nome' => 'Maria da Silva'])
            ->assertJsonFragment(['motivo' => 'Reuniao de trabalho']);
    }

    public function test_returning_a_nonexistent_solicitacao_fails(): void
    {
        $response = $this->getJson('/api/solicitacoes-visitas/999999');

        $response->assertNotFound();
    }

    public function test_update_a_solicitacao_de_visita(): void
    {
        $solicitacao = $this->createSolicitacao();

        $response = $this->putJson("/api/solicitacoes-visitas/{$solicitacao->id}", [
            'visitante_id' => $solicitacao->visitante_id,
            'colaborador_id' => $solicitacao->colaborador_id,
            'data_hora' => '2026-10-20 09:00:00',
            'motivo' => 'Motivo atualizado',
            'status' => 'aprovada',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['motivo' => 'Motivo atualizado'])
            ->assertJsonFragment(['status' => 'aprovada'])
            ->assertJsonFragment(['message' => 'Solicitação de visita atualizada com sucesso']);

        $this->assertDatabaseHas('solicitacoes_visitas', [
            'id' => $solicitacao->id,
            'status' => 'aprovada',
            'motivo' => 'Motivo atualizado',
        ]);
    }

    public function test_destroy_a_solicitacao_de_visita(): void
    {
        $solicitacao = $this->createSolicitacao();

        $response = $this->deleteJson("/api/solicitacoes-visitas/{$solicitacao->id}");

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Solicitação de visita excluída com sucesso']);

        $this->assertDatabaseMissing('solicitacoes_visitas', ['id' => $solicitacao->id]);
    }
}
