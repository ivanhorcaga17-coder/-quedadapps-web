<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartidaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store']);
    }

    public function index(Request $request)
    {
        $partidas = Partida::with('creador')->latest('fecha')->get();

        return response()->json($partidas);
    }

    public function create()
    {
        return view('frontend.crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'deporte' => 'required|string',
            'fecha' => 'required|date',
            'lugar' => 'required|string',
            'max_jugadores' => 'required|integer|min:1',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $titulo = $request->deporte . ' en ' . $request->lugar;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen')->store('partidas', 'public');
        } else {
            $imagen = 'default/partida_default.jpg';
        }

        $partida = $request->user()->partidasCreadas()->create([
            'titulo' => $titulo,
            'deporte' => $request->deporte,
            'fecha' => $request->fecha,
            'lugar' => $request->lugar,
            'max_jugadores' => $request->max_jugadores,
            'imagen' => $imagen,
        ]);

        if ($request->expectsJson()) {
            return response()->json($partida->load('creador'), 201);
        }

        return redirect()->route('partidas.confirmar', $partida->id);
    }

    public function show(Partida $partida)
    {
        return response()->json($partida->load('creador'));
    }

    public function confirmar(Partida $partida)
    {
        $partida->load([
            'creador',
            'chatMessages.user',
        ]);

        return view('frontend.confirmacion', compact('partida'));
    }

    public function update(Request $request, Partida $partida)
    {
        $validated = $request->validate([
            'deporte' => 'sometimes|required|string|max:255',
            'fecha' => 'sometimes|required|date',
            'lugar' => 'sometimes|required|string|max:255',
            'max_jugadores' => 'sometimes|required|integer|min:1',
        ]);

        if (isset($validated['deporte'], $validated['lugar'])) {
            $validated['titulo'] = $validated['deporte'] . ' en ' . $validated['lugar'];
        }

        $partida->update($validated);

        return response()->json($partida->fresh('creador'));
    }

    public function destroy(Partida $partida)
    {
        $partida->delete();

        return response()->json(['message' => 'Partida eliminada correctamente.']);
    }
}
