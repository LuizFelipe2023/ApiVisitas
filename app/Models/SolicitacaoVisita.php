<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoVisita extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes_visitas';

    protected $fillable = [
        'visitante_id',
        'colaborador_id',
        'data_hora',
        'motivo',
        'status',
    ];

    public function visitante()
    {
        return $this->belongsTo(Visitante::class);
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
}