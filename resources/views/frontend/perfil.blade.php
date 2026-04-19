<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mi Perfil - Quedadapps</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">

    <!-- TIPOGRAFÍA -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind compilado por Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans bg-white text-[#111]">

    @include('partials.header')

    <!-- CONTENIDO DEL PERFIL -->
    <div class="max-w-2xl mx-auto mt-16 p-6 bg-gray-100 rounded-xl shadow">

        <h1 class="text-3xl font-bold mb-8 text-center">Mi Perfil</h1>

        @if(session('success'))
            <div class="bg-stone-100 text-stone-800 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- FORMULARIO PRINCIPAL -->
        <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
            @csrf

            <!-- FOTO -->
            <div>
                <label class="block text-lg font-semibold mb-2">Imagen de perfil:</label>

                <div class="flex items-center gap-4">

                    @if($user->foto)
                        <img src="/frontend/img/perfiles/{{ $user->foto }}" class="h-20 w-20 rounded-full object-cover border">
                    @else
                        <img src="/frontend/img/perfil.png" class="h-20 w-20 rounded-full object-cover border">
                    @endif

                    <div class="flex flex-col gap-2">
                        <input type="file" name="foto" class="text-sm">

                        @if($user->foto)
                            <button formaction="{{ route('perfil.deletePhoto') }}" formmethod="POST" class="text-red-600 text-sm hover:underline">
                                @csrf
                                Eliminar foto
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- NOMBRE -->
            <div>
                <label class="block text-lg font-semibold mb-1">Nombre usuario</label>
                <input type="text" name="name" value="{{ $user->name }}"
                       class="w-full px-4 py-3 bg-white border rounded-lg focus:outline-none">
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-lg font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ $user->email }}"
                       class="w-full px-4 py-3 bg-white border rounded-lg focus:outline-none">
            </div>

            <!-- BOTONES -->
            <div class="flex justify-between mt-6">
                <a href="{{ route('index') }}"
                   class="px-6 py-3 bg-gray-300 rounded-lg font-semibold hover:bg-gray-400">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-black text-white rounded-lg font-semibold hover:bg-gray-900">
                    Guardar cambios
                </button>
            </div>

        </form>

    </div>

    @include('partials.footer-banner')

</body>
</html>
