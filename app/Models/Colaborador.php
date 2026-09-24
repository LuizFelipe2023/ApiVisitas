<?php

namespace App\Models;

use Database\Factories\ColaboradorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
      use HasFactory;
      protected $fillable = ['nome','cpf','data_de_nascimento','telefone','email','setor_id'];

      protected $table = "colaboradores";

      public function setor()
      {
             return $this->belongsTo(Setor::class,'setor_id');
      }

      protected $casts = [
            'data_de_nascimento' => 'date'
      ];
}
