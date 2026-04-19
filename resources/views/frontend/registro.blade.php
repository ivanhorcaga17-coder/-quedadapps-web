<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Quedadapps</title>

    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white font-sans text-[#111]">

    <div class="min-h-screen flex flex-col justify-center items-center px-6">

        <!-- LOGO PREMIUM -->
        <img src="/frontend/img/logotipo2_quedadapps.png"
             class="h-28 mb-6 rounded-xl shadow-lg border border-black/10 object-contain hover:opacity-90 transition">

        <h1 class="text-3xl font-bold mb-6">Crear cuenta</h1>

        <!-- ALERTAS DE ERROR -->
        @if($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4 w-full max-w-md shadow">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('registro.post') }}" method="POST" class="w-full max-w-md flex flex-col gap-6">
            @csrf

            <!-- NOMBRE -->
            <div>
                <label class="block text-sm font-medium mb-1">Nombre</label>
                <input type="text" name="name"
                       class="w-full px-4 py-3 border rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black"
                       required>
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email"
                       class="w-full px-4 py-3 border rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black"
                       required>
            </div>

            <!-- CONTRASEÑA -->
            <div>
                <label class="block text-sm font-medium mb-1">Contraseña</label>

                <div class="relative">
                    <input type="password" name="password" id="password"
                           class="w-full px-4 py-3 border rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black"
                           required>

                    <button type="button"
                            onclick="togglePassword('password', this)"
                            class="absolute right-3 top-3 text-xl">
                        👁️
                    </button>
                </div>
            </div>

            <!-- CONFIRMAR CONTRASEÑA -->
            <div>
                <label class="block text-sm font-medium mb-1">Confirmar contraseña</label>

                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
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
                Crear cuenta
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
