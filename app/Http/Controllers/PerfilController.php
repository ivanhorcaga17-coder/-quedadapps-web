<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('frontend.perfil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $user->id,
            'foto'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('foto')) {
            $filename = time().'.'.$request->foto->extension();
            $request->foto->move(public_path('frontend/img/perfiles'), $filename);
            $user->foto = $filename;
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->foto && file_exists(public_path('frontend/img/perfiles/'.$user->foto))) {
            unlink(public_path('frontend/img/perfiles/'.$user->foto));
        }

        $user->foto = null;
        $user->save();

        return back()->with('success', 'Foto eliminada.');
    }
}
