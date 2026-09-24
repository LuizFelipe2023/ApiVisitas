<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitanteRequest;
use App\Http\Resources\VisitanteResource;
use App\Models\Visitante;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class VisitanteController extends Controller
{
    
    public function index()
    {
        $visitantes = Visitante::orderBy('nome')->get();
        return VisitanteResource::collection($visitantes);
    }

    
    public function store(VisitanteRequest $request)
    {
        try {
            $visitante = Visitante::create($request->validated());

            return (new VisitanteResource($visitante))
                ->additional(['message' => 'Visitante cadastrado com sucesso'])
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            Log::error('Erro ao cadastrar visitante:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao cadastrar o visitante'], 422);
        }
    }

    public function show(Visitante $visitante)
    {
        return new VisitanteResource($visitante);
    }

    
    public function update(VisitanteRequest $request, Visitante $visitante)
    {
        try {
            $visitante->update($request->validated());

            return (new VisitanteResource($visitante))
                ->additional(['message' => 'Visitante atualizado com sucesso']);
        } catch (QueryException $e) {
            Log::error('Erro ao atualizar visitante:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao atualizar o visitante'], 422);
        }
    }

   
    public function destroy(Visitante $visitante)
    {
        try {
            $visitante->delete();
            return response()->json(['message' => 'Visitante excluído com sucesso'], 200);
        } catch (QueryException $e) {
            Log::error('Erro ao excluir visitante:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao excluir o visitante'], 422);
        }
    }
}