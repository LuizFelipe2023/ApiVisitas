<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitacaoVisitaRequest;
use App\Http\Resources\SolicitacaoVisitaResource;
use App\Models\SolicitacaoVisita;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SolicitacaoVisitaController extends Controller
{
    public function index()
    {
        $solicitacoes = SolicitacaoVisita::with(['visitante', 'colaborador.setor'])->latest()->get();
        return SolicitacaoVisitaResource::collection($solicitacoes);
    }

    public function store(SolicitacaoVisitaRequest $request)
    {
        try {
            $solicitacao = SolicitacaoVisita::create($request->validated());
            $solicitacao->load(['visitante', 'colaborador.setor']);

            return (new SolicitacaoVisitaResource($solicitacao))
                ->additional(['message' => 'Solicitação de visita criada com sucesso'])
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            Log::error('Erro ao criar solicitação de visita:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao criar a solicitação de visita'], 422);
        }
    }

    public function show(SolicitacaoVisita $solicitacaoVisita)
    {
        $solicitacaoVisita->load(['visitante', 'colaborador.setor']);
        return new SolicitacaoVisitaResource($solicitacaoVisita);
    }

    public function update(SolicitacaoVisitaRequest $request, SolicitacaoVisita $solicitacaoVisita)
    {
        try {
            $solicitacaoVisita->update($request->validated());
            $solicitacaoVisita->load(['visitante', 'colaborador.setor']);

            return (new SolicitacaoVisitaResource($solicitacaoVisita))
                ->additional(['message' => 'Solicitação de visita atualizada com sucesso']);
        } catch (QueryException $e) {
            Log::error('Erro ao atualizar solicitação de visita:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao atualizar a solicitação de visita'], 422);
        }
    }

    public function destroy(SolicitacaoVisita $solicitacaoVisita)
    {
        try {
            $solicitacaoVisita->delete();
            return response()->json(['message' => 'Solicitação de visita excluída com sucesso'], 200);
        } catch (QueryException $e) {
            Log::error('Erro ao excluir solicitação de visita:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Houve um erro ao excluir a solicitação de visita'], 422);
        }
    }
}