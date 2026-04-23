<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function store(Request $request, Partida $partida)
    {
        $validated = $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        $partida->chatMessages()->create([
            'usuario_id' => $request->user()->id,
            'mensaje' => $validated['mensaje'],
        ]);

        return back()->with('success', 'Mensaje enviado al chat de la partida.');
    }
}
