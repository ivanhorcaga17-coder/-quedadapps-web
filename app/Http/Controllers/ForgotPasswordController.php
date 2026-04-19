<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuedadappsMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('frontend.recuperarcontrasenya');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,email'
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Debes introducir un correo válido.',
            'email.exists' => 'No existe ninguna cuenta con ese correo.',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $token = Password::broker()->createToken($user);

        $resetUrl = url("/reset-password/{$token}?email=" . urlencode($request->email));

        Mail::to($request->email)->send(new QuedadappsMail(
            "Recupera tu contraseña",
            "Hemos recibido una solicitud para restablecer tu contraseña.\n\nHaz clic en el botón para continuar.",
            $resetUrl,
            "Restablecer contraseña"
        ));

        return back()->with([
            'status' => 'Te hemos enviado un correo para recuperar tu contraseña.'
        ]);
    }
}
