<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    // Muestra el formulario donde el usuario escribe su nueva contraseña
    public function showResetForm(Request $request, $token)
    {
        return view('frontend.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Procesa el formulario y actualiza la contraseña
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',

                // VALIDACIÓN DE CONTRASEÑA FUERTE (permitiendo cualquier símbolo)
                'regex:/[A-Z]/',          // al menos una mayúscula
                'regex:/[a-z]/',          // al menos una minúscula
                'regex:/[0-9]/',          // al menos un número
                'regex:/[^A-Za-z0-9]/',   // al menos un símbolo
            ],
        ], [
            // MENSAJES EN ESPAÑOL
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe incluir mayúsculas, minúsculas, números y símbolos.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Guardar nueva contraseña
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Contraseña actualizada correctamente.')
            : back()->withErrors(['email' => 'Error al actualizar la contraseña.']);
    }
}
