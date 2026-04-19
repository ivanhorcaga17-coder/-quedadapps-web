<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|gte:1|lte:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::create([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'ip_address' => $request->ip(),
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);

        return redirect()
            ->route('descargar')
            ->with('success', '¡Gracias por tu valoración!');
    }

    public function destroy(Review $review, Request $request)
    {
        // 🔥 Si la review pertenece a un usuario logueado
        if ($review->user_id !== null) {

            // Solo ese usuario logueado puede borrarla
            if (!Auth::check() || Auth::id() !== $review->user_id) {
                abort(403, 'No puedes borrar valoraciones de otros usuarios');
            }

        } else {

            // 🔥 Review anónima → solo se puede borrar si NO estás logueado
            // y la IP coincide
            if (Auth::check() || $review->ip_address !== $request->ip()) {
                abort(403, 'No puedes borrar valoraciones de otros usuarios');
            }
        }

        $review->delete();

        return redirect()
            ->route('descargar')
            ->with('success', 'Tu valoración ha sido eliminada');
    }
}
