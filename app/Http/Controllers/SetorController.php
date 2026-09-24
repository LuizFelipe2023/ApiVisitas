<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetorRequest;
use App\Http\Resources\SetorResource;
use App\Models\Setor;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SetorController extends Controller
{
    public function index()
    {
        $setores = Setor::orderBy('nome')->get();
        return SetorResource::collection($setores);
    }

    public function store(SetorRequest $request)
    {
        try {
            $setor = Setor::create($request->validated());
            
            return (new SetorResource($setor))
                ->additional(['message' => 'Setor criado com sucesso'])
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            Log::error('Erro ao criar setor:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao criar o setor'], 422);
        }
    }

    public function show(Setor $setor)
    {
        return new SetorResource($setor);
    }

    public function update(SetorRequest $request, Setor $setor)
    {
        try {
            $setor->update($request->validated());
            
            return (new SetorResource($setor))
                ->additional(['message' => 'Setor atualizado com sucesso']);
        } catch (QueryException $e) {
            Log::error('Erro ao atualizar setor:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao atualizar o setor'], 422);
        }
    }

    public function destroy(Setor $setor)
    {
        try {
            $setor->delete();
            return response()->json(['message' => 'Setor excluído com sucesso'], 200);
        } catch (QueryException $e) {
            Log::error('Erro ao excluir setor:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao excluir o setor'], 422);
        }
    }
}