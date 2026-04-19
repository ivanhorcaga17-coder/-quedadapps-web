<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partida;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'stats' => [
                'partidas' => Partida::count(),
                'usuarios' => User::count(),
                'reviews' => Review::count(),
            ],
            'partidas' => Partida::with('creador')->latest()->paginate(10, ['*'], 'partidas_page'),
            'usuarios' => User::latest()->paginate(10, ['*'], 'usuarios_page'),
            'reviews' => Review::with('user')->latest()->paginate(10, ['*'], 'reviews_page'),
        ]);
    }

    public function editPartida(Partida $partida)
    {
        return view('admin.edit-partida', compact('partida'));
    }

    public function updatePartida(Request $request, Partida $partida)
    {
        $validated = $request->validate([
            'deporte' => 'required|string|max:255',
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:255',
            'max_jugadores' => 'required|integer|min:1',
        ]);

        $validated['titulo'] = $validated['deporte'] . ' en ' . $validated['lugar'];

        $partida->update($validated);

        return redirect()
            ->route('admin.index')
            ->with('success', 'La partida se ha actualizado correctamente.');
    }

    public function destroyPartida(Partida $partida)
    {
        $partida->delete();

        return back()->with('success', 'La partida se ha eliminado correctamente.');
    }

    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors([
                'admin' => 'No puedes borrar tu propia cuenta de administrador desde el panel.',
            ]);
        }

        $user->delete();

        return back()->with('success', 'El usuario se ha eliminado correctamente.');
    }

    public function destroyReview(Review $review)
    {
        $review->delete();

        return back()->with('success', 'La review se ha eliminado correctamente.');
    }
}
