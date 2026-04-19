<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function buscar(Request $request)
    {
        $partidas = Partida::query()
            ->with(['creador', 'asistencias.usuario'])
            ->withCount('asistencias')
            ->when($request->filled('deporte'), fn ($query) => $query->where('deporte', $request->string('deporte')))
            ->when($request->filled('lugar'), fn ($query) => $query->where('lugar', 'like', '%' . $request->string('lugar') . '%'))
            ->when($request->filled('fecha'), fn ($query) => $query->whereDate('fecha', $request->input('fecha')))
            ->orderBy('fecha')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.buscar', [
            'partidas' => $partidas,
            'filtros' => $request->only(['deporte', 'lugar', 'fecha']),
        ]);
    }

    public function calendario()
    {
        $partidas = Partida::query()
            ->with('creador')
            ->withCount('asistencias')
            ->orderBy('fecha')
            ->get()
            ->groupBy(fn (Partida $partida) => $partida->fecha->locale('es')->translatedFormat('F Y'));

        return view('frontend.calendario', compact('partidas'));
    }

    public function asistencia(Request $request)
    {
        $user = $request->user();

        $misAsistencias = collect();
        $misPartidasIds = [];

        if ($user) {
            $misAsistencias = $user->asistencias()
                ->with('partida.creador')
                ->latest()
                ->get();

            $misPartidasIds = $misAsistencias->pluck('partida_id')->all();
        }

        $proximasPartidas = Partida::query()
            ->with('creador')
            ->withCount('asistencias')
            ->where('fecha', '>=', now()->subDay())
            ->orderBy('fecha')
            ->limit(12)
            ->get();

        return view('frontend.asistencia', [
            'misAsistencias' => $misAsistencias,
            'misPartidasIds' => $misPartidasIds,
            'proximasPartidas' => $proximasPartidas,
        ]);
    }
}
