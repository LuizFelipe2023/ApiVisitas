<?php

namespace Tests\Feature;

use App\Models\Visitante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitanteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateTestUser();
    }

    private function visitanteData(): array
    {
        return [
            'nome' => 'Carlos Oliveira',
            'cpf' => '123.456.789-00',
            'telefone' => '(92) 99999-2222',
            'email' => 'carlos@example.com',
        ];
    }

    private function createVisitante(array $attributes = []): Visitante
    {
        return Visitante::create(array_merge($this->visitanteData(), $attributes));
    }

    public function test_index_returns_visitantes_ordered_by_name(): void
    {
        $this->createVisitante([
            'nome' => 'Zelia Oliveira',
            'cpf' => '111.111.111-11',
        ]);
        $this->createVisitante([
            'nome' => 'Ana Oliveira',
            'cpf' => '222.222.222-22',
        ]);

        $response = $this->getJson('/api/visitantes');

        $response->assertOk()
            ->assertJsonPath('data.0.nome', 'Ana Oliveira')
            ->assertJsonPath('data.1.nome', 'Zelia Oliveira');
    }

    public function test_store_a_visitante(): void
    {
        $response = $this->postJson('/api/visitantes', $this->visitanteData());

        $response->assertCreated()
            ->assertJsonFragment(['nome' => 'Carlos Oliveira'])
            ->assertJsonFragment(['message' => 'Visitante cadastrado com sucesso']);

        $this->assertDatabaseHas('visitantes', [
            'nome' => 'Carlos Oliveira',
            'cpf' => '123.456.789-00',
        ]);
    }

    public function test_store_a_visitante_requires_name_and_unique_cpf(): void
    {
        $this->createVisitante();

        $response = $this->postJson('/api/visitantes', [
            'nome' => '',
            'cpf' => '123.456.789-00',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['nome', 'cpf']);
    }

    public function test_return_a_visitante(): void
    {
        $visitante = $this->createVisitante();

        $response = $this->getJson("/api/visitantes/{$visitante->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'nome' => 'Carlos Oliveira',
                'cpf' => '123.456.789-00',
            ]);
    }

    public function test_returning_a_nonexistent_visitante_fails(): void
    {
        $response = $this->getJson('/api/visitantes/999999');

        $response->assertNotFound();
    }

    public function test_update_a_visitante(): void
    {
        $visitante = $this->createVisitante();

        $response = $this->putJson("/api/visitantes/{$visitante->id}", [
            'nome' => 'Carlos Atualizado',
            'cpf' => '987.654.321-00',
            'telefone' => '(92) 98888-3333',
            'email' => 'carlos.atualizado@example.com',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['nome' => 'Carlos Atualizado'])
            ->assertJsonFragment(['message' => 'Visitante atualizado com sucesso']);

        $this->assertDatabaseHas('visitantes', [
            'id' => $visitante->id,
            'nome' => 'Carlos Atualizado',
            'cpf' => '987.654.321-00',
        ]);
    }

    public function test_update_a_visitante_allows_keeping_the_same_cpf(): void
    {
        $visitante = $this->createVisitante();

        $response = $this->putJson("/api/visitantes/{$visitante->id}", [
            'nome' => 'Nome Atualizado',
            'cpf' => $visitante->cpf,
        ]);

        $response->assertOk();
    }

    public function test_destroy_a_visitante(): void
    {
        $visitante = $this->createVisitante();

        $response = $this->deleteJson("/api/visitantes/{$visitante->id}");

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Visitante excluído com sucesso']);

        $this->assertDatabaseMissing('visitantes', ['id' => $visitante->id]);
    }
}
