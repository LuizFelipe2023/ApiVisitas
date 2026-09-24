<?php

namespace Tests\Feature;

use App\Models\Colaborador;
use App\Models\Setor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColaboradorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateTestUser();
    }

    private function colaboradorData(int $setorId): array
    {
        return [
            'nome' => 'Maria da Silva',
            'cpf' => '123.456.789-00',
            'data_de_nascimento' => '1990-05-20',
            'telefone' => '(92) 99999-1111',
            'email' => 'maria@example.com',
            'setor_id' => $setorId,
        ];
    }

    public function test_index_returns_colaboradores_ordered_by_name(): void
    {
        Colaborador::factory()->create(['nome' => 'Zelia Souza']);
        Colaborador::factory()->create(['nome' => 'Ana Souza']);

        $response = $this->getJson('/api/colaboradores');

        $response->assertOk()
            ->assertJsonPath('data.0.nome', 'Ana Souza')
            ->assertJsonPath('data.1.nome', 'Zelia Souza');
    }

    public function test_store_a_colaborador(): void
    {
        $setor = Setor::factory()->create(['nome' => 'Informatica']);

        $response = $this->postJson('/api/colaboradores', $this->colaboradorData($setor->id));

        $response->assertCreated()
            ->assertJsonFragment(['nome' => 'Maria da Silva'])
            ->assertJsonFragment(['setor_nome' => 'Informatica'])
            ->assertJsonFragment(['message' => 'Colaborador cadastrado com sucesso']);

        $this->assertDatabaseHas('colaboradores', [
            'cpf' => '123.456.789-00',
            'setor_id' => $setor->id,
        ]);
    }

    public function test_store_a_colaborador_requires_valid_data(): void
    {
        $response = $this->postJson('/api/colaboradores', [
            'nome' => '',
            'cpf' => '123',
            'data_de_nascimento' => 'data-invalida',
            'telefone' => '(92) 99999-1111',
            'email' => 'email-invalido',
            'setor_id' => 999999,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'nome',
                'data_de_nascimento',
                'email',
                'setor_id',
            ]);
    }

    public function test_return_a_colaborador_with_its_setor(): void
    {
        $setor = Setor::factory()->create(['nome' => 'Recursos Humanos']);
        $colaborador = Colaborador::factory()->create([
            'nome' => 'Joao Santos',
            'setor_id' => $setor->id,
        ]);

        $response = $this->getJson("/api/colaboradores/{$colaborador->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'nome' => 'Joao Santos',
                'setor_nome' => 'Recursos Humanos',
            ]);
    }

    public function test_returning_a_nonexistent_colaborador_fails(): void
    {
        $response = $this->getJson('/api/colaboradores/999999');

        $response->assertNotFound();
    }

    public function test_update_a_colaborador(): void
    {
        $colaborador = Colaborador::factory()->create();
        $data = $this->colaboradorData($colaborador->setor_id);
        $data['nome'] = 'Colaborador Atualizado';
        $data['cpf'] = '987.654.321-00';
        $data['email'] = 'atualizado@example.com';

        $response = $this->putJson("/api/colaboradores/{$colaborador->id}", $data);

        $response->assertOk()
            ->assertJsonFragment(['nome' => 'Colaborador Atualizado'])
            ->assertJsonFragment(['message' => 'Colaborador atualizado com sucesso']);

        $this->assertDatabaseHas('colaboradores', [
            'id' => $colaborador->id,
            'cpf' => '987.654.321-00',
            'nome' => 'Colaborador Atualizado',
        ]);
    }

    public function test_destroy_a_colaborador(): void
    {
        $colaborador = Colaborador::factory()->create();

        $response = $this->deleteJson("/api/colaboradores/{$colaborador->id}");

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Colaborador excluído com sucesso']);

        $this->assertDatabaseMissing('colaboradores', ['id' => $colaborador->id]);
    }
}
