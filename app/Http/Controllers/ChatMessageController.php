<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function store(Request $request, Partida $partida)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $partida->chatMessages()->create([
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Mensaje enviado al chat de la partida.');
    }
}
