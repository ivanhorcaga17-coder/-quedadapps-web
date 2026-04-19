<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Recuperar contraseña - Quedadapps</title>

    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white font-sans text-[#111]">

<div class="min-h-screen flex flex-col justify-center items-center px-6">

    <!-- LOGO PREMIUM -->
    <img src="/frontend/img/logotipo2_quedadapps.png"
         class="h-28 mb-6 rounded-xl shadow-lg border border-black/10 object-contain hover:opacity-90 transition">

    <h1 class="text-3xl font-bold mb-6">Recuperar contraseña</h1>

    <!-- MENSAJE DE ÉXITO -->
    @if (session('status'))
        <div class="bg-stone-100 text-stone-800 px-4 py-3 rounded mb-4 w-full max-w-md shadow">
            {{ session('status') }}
        </div>
    @endif

    <!-- ERRORES -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4 w-full max-w-md shadow">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- FORMULARIO -->
    <form action="{{ route('password.email') }}" method="POST" class="w-full max-w-md flex flex-col gap-6">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Correo electrónico</label>
            <input type="email" name="email"
                   class="w-full px-4 py-3 border rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black"
                   placeholder="Introduce tu correo"
                   required>
        </div>

        <button type="submit"
                class="w-full bg-black text-white py-3 rounded-lg text-lg font-semibold hover:bg-gray-900 transition">
            Enviar enlace de recuperación
        </button>
    </form>

</div>

</body>
</html>
