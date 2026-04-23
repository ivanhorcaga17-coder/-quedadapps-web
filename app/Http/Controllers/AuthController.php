<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuedadappsMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
        ], [
            'email.unique' => 'Este correo ya está registrado.',
            'password.regex' => 'La contraseña debe incluir mayúsculas, minúsculas, números y símbolos.',
        ]);

        $isFirstUser = User::count() === 0;

        $user = User::create([
            'nombre' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isFirstUser ? 'admin' : 'user',
        ]);

        Auth::login($user);

        $redirect = redirect()->route('index')->with(
            'success',
            $isFirstUser
                ? 'Registro completado. Esta cuenta se ha creado como administradora inicial.'
                : 'Registro completado'
        );

        Mail::to($user->email)->send(new QuedadappsMail(
            "Bienvenido a Quedadapps",
            "Hola {$user->name},\n\nGracias por unirte a nuestra comunidad.\n\nYa puedes crear partidas, unirte a otras y disfrutar de la experiencia.",
            route('index'),
            "Entrar a Quedadapps"
        ));

        return $redirect;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'nullable|boolean',
        ]);

        if (Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            $user = $request->user();

            return $user?->isAdmin()
                ? redirect()->route('admin.index')
                : redirect()->route('index');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ])->onlyInput('email', 'remember');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
