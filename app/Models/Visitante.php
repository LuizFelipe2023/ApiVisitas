<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitante extends Model
{
      use HasFactory;
      
      protected $fillable = ['nome','cpf','telefone','email'];
}
