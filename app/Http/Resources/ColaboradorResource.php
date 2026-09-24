<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColaboradorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'data_de_nascimento' => $this->data_de_nascimento,
            'telefone' => $this->telefone,
            'email' => $this->email,
            'setor_id' => $this->setor_id,
            'setor_nome' => $this->setor?->nome, 
        ];
    }
}