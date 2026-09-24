<?php

namespace Tests\Feature;

use App\Models\Setor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateTestUser();
    }

    public function test_index_returns_all_setores_ordered_by_name(): void
    {
        Setor::factory()->create(['nome' => 'Setor de Zootecnia']);
        Setor::factory()->create(['nome' => 'Setor de Administração']);

        $response = $this->getJson('/api/setores');

        $response->assertOk()
            ->assertJsonPath('data.0.nome', 'Setor de Administração')
            ->assertJsonPath('data.1.nome', 'Setor de Zootecnia');
    }

    public function test_store_a_setor(): void
    {
        $data = [
            'nome' => 'Gerência de Informatica',
            'sigla' => 'GEINF',
            'telefone' => '(92) 98241-3874',
            'email' => 'geinf@outlook.com'
        ];

        $response = $this->postJson('/api/setores', $data);

        $response->assertStatus(201);

        $response->assertJsonPath('data.nome', 'Gerência de Informatica')
            ->assertJsonPath('data.sigla', 'GEINF')
            ->assertJsonPath('message', 'Setor criado com sucesso');

        $this->assertDatabaseHas('setores', ['nome' => 'Gerência de Informatica']);
    }

    public function test_store_a_setor_requires_valid_data(): void
    {
        $response = $this->postJson('/api/setores', [
            'nome' => '',
            'sigla' => 'SIGLA-MUITO-LONGA',
            'telefone' => '(92) 98241-3874',
            'email' => 'email-invalido',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['nome', 'sigla', 'email']);
    }

    public function test_return_a_setor(): void
    {
        $setor = Setor::factory()->create([
            'nome' => 'Gerência de Informatica',
            'sigla' => 'GEINF',
            'telefone' => '(92) 98241-3874',
            'email' => 'geinf@outlook.com',
        ]);

        $response = $this->getJson("/api/setores/{$setor->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'nome' => 'Gerência de Informatica',
                'sigla' => 'GEINF',
            ]);
    }

    public function test_returning_a_nonexistent_setor_fails(): void
    {
        $response = $this->getJson('/api/setores/999999');

        $response->assertNotFound();
    }

    public function test_update_a_setor(): void
    {
        $setor = Setor::factory()->create();

        $response = $this->putJson("/api/setores/{$setor->id}", [
            'nome' => 'Setor Informatica',
            'sigla' => 'SEINF',
            'telefone' => '(92) 98241-3874',
            'email' => 'seinf@outlook.com',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['nome' => 'Setor Informatica'])
            ->assertJsonFragment(['message' => 'Setor atualizado com sucesso']);

        $this->assertDatabaseHas('setores', [
            'id' => $setor->id,
            'nome' => 'Setor Informatica',
        ]);
    }

    public function test_update_a_setor_requires_valid_data(): void
    {
        $setor = Setor::factory()->create();

        $response = $this->putJson("/api/setores/{$setor->id}", [
            'nome' => 'Nome válido',
            'sigla' => 'SIGLA-MUITO-LONGA',
            'telefone' => '(92) 98241-3874',
            'email' => 'email-invalido',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sigla', 'email']);
    }

    public function test_destroy_a_setor(): void
    {
        $setor = Setor::factory()->create();

        $response = $this->deleteJson("/api/setores/{$setor->id}");

        $response->assertOk()
            ->assertJsonPath('message', 'Setor excluído com sucesso');

        $this->assertDatabaseMissing('setores', ['id' => $setor->id]);
    }

    public function test_destroying_a_nonexistent_setor_fails(): void
    {
        $response = $this->deleteJson('/api/setores/999999');

        $response->assertNotFound();
    }
}