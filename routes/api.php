<?php

use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\SolicitacaoVisitaController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rotas de Autenticação Públicas
Route::post('/register', [AuthController::class, 'register']); // <- ADICIONADO AQUI
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

// Rotas Protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('setores', SetorController::class)
        ->parameters(['setores' => 'setor']);

    Route::apiResource('colaboradores', ColaboradorController::class)
        ->parameters(['colaboradores' => 'colaborador']);

    Route::apiResource('visitantes', VisitanteController::class)
        ->parameters(['visitantes' => 'visitante']);

    Route::apiResource('solicitacoes-visitas', SolicitacaoVisitaController::class)
        ->parameters(['solicitacoes-visitas' => 'solicitacaoVisita']);
});