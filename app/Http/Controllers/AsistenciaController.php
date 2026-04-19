<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Partida;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index()
    {
        return response()->json(
            Asistencia::with('usuario', 'partida')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'partida_id' => 'required|exists:partidas,id',
            'estado' => 'nullable|string|max:255'
        ]);

        $asistencia = Asistencia::updateOrCreate(
            [
                'usuario_id' => $validated['usuario_id'],
                'partida_id' => $validated['partida_id'],
            ],
            [
                'estado' => $validated['estado'] ?? 'confirmado',
            ]
        );

        return response()->json($asistencia, 201);
    }

    public function destroy($id)
    {
        Asistencia::destroy($id);

        return response()->json(['message' => 'Asistencia eliminada']);
    }

    public function join(Request $request, Partida $partida)
    {
        $asistencia = Asistencia::updateOrCreate(
            [
                'usuario_id' => $request->user()->id,
                'partida_id' => $partida->id,
            ],
            [
                'estado' => 'confirmado',
            ]
        );

        return back()->with('success', "Tu asistencia para \"{$partida->titulo}\" ha quedado guardada.");
    }

    public function leave(Request $request, Partida $partida)
    {
        Asistencia::query()
            ->where('usuario_id', $request->user()->id)
            ->where('partida_id', $partida->id)
            ->delete();

        return back()->with('success', "Tu asistencia para \"{$partida->titulo}\" ha sido cancelada.");
    }
}
