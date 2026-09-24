<?php

namespace App\Models;

use Database\Factories\SetorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'sigla', 'telefone', 'email'];
    
    protected $table = "setores";

    public function colaboradores()
    {
           return $this->hasMany(Colaborador::class);
    }
}
