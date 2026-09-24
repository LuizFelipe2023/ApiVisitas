<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitanteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
           return [
               'nome' => $this->nome,
               'cpf' => $this->cpf,
               'telefone' => $this->telefone,
               'email' => $this->email
           ];
    }
}
