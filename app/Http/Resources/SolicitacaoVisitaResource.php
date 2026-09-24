<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitacaoVisitaResource extends JsonResource
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
            'visitante' => new VisitanteResource($this->whenLoaded('visitante')),
            'colaborador' => new ColaboradorResource($this->whenLoaded('colaborador')),
            'data_hora' => $this->data_hora,
            'motivo' => $this->motivo,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}