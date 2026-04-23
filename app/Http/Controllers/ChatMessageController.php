<?php

namespace App\Http\Controllers;

use App\Mail\QuedadappsMail;
use App\Models\ChatMessage;
use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChatMessageController extends Controller
{
    public function store(Request $request, Partida $partida)
    {
        $validated = $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        $message = $partida->chatMessages()->create([
            'usuario_id' => $request->user()->id,
            'mensaje' => $validated['mensaje'],
        ]);

        $this->notifyParticipants($partida, $message);

        return back()->with('success', 'Mensaje enviado al chat de la partida.');
    }

    public function destroy(Request $request, Partida $partida, ChatMessage $chatMessage)
    {
        abort_unless($chatMessage->partida_id === $partida->id, 404);

        $user = $request->user();
        $canDelete = $chatMessage->usuario_id === $user->id
            || $partida->creador_id === $user->id
            || $user->isAdmin();

        abort_unless($canDelete, 403);

        $chatMessage->delete();

        return back()->with('success', 'Mensaje eliminado del chat.');
    }

    protected function notifyParticipants(Partida $partida, ChatMessage $message): void
    {
        $message->loadMissing('usuario');
        $partida->loadMissing('asistencias.usuario');

        $recipientEmails = $partida->asistencias
            ->pluck('usuario')
            ->filter()
            ->reject(fn ($user) => $user->id === $message->usuario_id)
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

        foreach ($recipientEmails as $email) {
            Mail::to($email)->send(new QuedadappsMail(
                'Nuevo mensaje en una partida',
                "Hay un nuevo mensaje en la partida \"{$partida->titulo}\".\n\n{$message->usuario?->name}: {$message->mensaje}",
                route('partidas.showPage', $partida),
                'Abrir chat'
            ));
        }
    }
}
