<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColaboradorRequest;
use App\Http\Resources\ColaboradorResource;
use App\Models\Colaborador;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ColaboradorController extends Controller
{
    public function index()
    {
        $colaboradores = Colaborador::with('setor')->orderBy('nome')->get();
        return ColaboradorResource::collection($colaboradores);
    }

    public function store(ColaboradorRequest $request)
    {
        try {
            $colaborador = Colaborador::create($request->validated());
            $colaborador->load('setor'); // Garante que o setor vem preenchido no resource

            return (new ColaboradorResource($colaborador))
                ->additional(['message' => 'Colaborador cadastrado com sucesso'])
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            Log::error('Erro ao cadastrar colaborador:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao cadastrar o colaborador'], 422);
        }
    }

    public function show(Colaborador $colaborador)
    {
        $colaborador->load('setor');
        return new ColaboradorResource($colaborador);
    }

    public function update(ColaboradorRequest $request, Colaborador $colaborador)
    {
        try {
            $colaborador->update($request->validated());
            $colaborador->load('setor');

            return (new ColaboradorResource($colaborador))
                ->additional(['message' => 'Colaborador atualizado com sucesso']);
        } catch (QueryException $e) {
            Log::error('Erro ao atualizar colaborador:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao atualizar o colaborador'], 422);
        }
    }

    public function destroy(Colaborador $colaborador)
    {
        try {
            $colaborador->delete();
            return response()->json(['message' => 'Colaborador excluído com sucesso'], 200);
        } catch (QueryException $e) {
            Log::error('Erro ao excluir colaborador:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao excluir o colaborador'], 422);
        }
    }
}

