<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Nueva contraseña - Quedadapps</title>

    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white font-sans text-[#111]">

    <div class="min-h-screen flex flex-col justify-center items-center px-6">

        <!-- LOGO PREMIUM -->
        <img src="/frontend/img/logotipo2_quedadapps.png"
             class="h-28 mb-6 rounded-xl shadow-lg border border-black/10 object-contain hover:opacity-90 transition"
             alt="Quedadapps">

        <h1 class="text-3xl font-bold mb-6">Nueva contraseña</h1>

        <!-- ERRORES -->
        @if ($errors->any())
            <div class="text-red-600 text-sm mb-4 w-full max-w-md">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- FORMULARIO -->
        <form action="{{ route('password.update') }}" method="POST" class="w-full max-w-md flex flex-col gap-6">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Nueva contraseña -->
            <div>
                <label class="block text-sm font-medium mb-1">Nueva contraseña</label>

                <div class="relative">
                    <input type="password" name="password" id="password"
                           placeholder="Escribe tu nueva contraseña"
                           class="w-full px-4 py-3 border rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black"
                           required>

                    <button type="button"
                            onclick="togglePassword('password', this)"
                            class="absolute right-3 top-3 text-xl">
                        👁️
                    </button>
                </div>

                <p class="text-xs text-gray-500 mt-1">
                    Debe contener: mínimo 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.
                </p>
            </div>

            <!-- Confirmar contraseña -->
            <div>
                <label class="block text-sm font-medium mb-1">Confirmar contraseña</label>

                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Repite tu contraseña"
                           class="w-full px-4 py-3 border rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black"
                           required>

                    <button type="button"
                            onclick="togglePassword('password_confirmation', this)"
                            class="absolute right-3 top-3 text-xl">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-black text-white py-3 rounded-lg text-lg font-semibold hover:bg-gray-900 transition">
                Guardar nueva contraseña
            </button>
        </form>

    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);

            if (input.type === "password") {
                input.type = "text";
                btn.textContent = "🔓";
            } else {
                input.type = "password";
                btn.textContent = "👁️";
            }
        }
    </script>

</body>
</html>
