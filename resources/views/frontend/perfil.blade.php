<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
            <section class="rounded-[32px] border border-stone-200 bg-[linear-gradient(145deg,#111111_0%,#292524_100%)] p-6 text-white shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-8">
                <p class="text-sm uppercase tracking-[0.35em] text-stone-400">Perfil</p>
                <h1 class="mt-4 text-3xl font-semibold sm:text-4xl">Gestiona tu cuenta</h1>
                <p class="mt-4 max-w-xl text-sm leading-6 text-stone-300 sm:text-base">
                    Actualiza tu imagen, tu nombre y tu correo desde cualquier dispositivo manteniendo el estilo visual de QuedadApps.
                </p>

                <div class="mt-8 flex flex-col items-start gap-4 rounded-[28px] border border-white/10 bg-white/5 p-5 sm:flex-row sm:items-center">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full border border-white/10 object-cover sm:h-24 sm:w-24">
                    <div>
                        <p class="text-xl font-semibold">{{ $user->name }}</p>
                        <p class="mt-1 text-sm text-stone-300">{{ $user->email }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-[32px] border border-stone-200 bg-stone-100 p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="rounded-[28px] border border-stone-200 bg-white p-5">
                        <label class="mb-4 block text-lg font-semibold">Imagen de perfil</label>

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full border border-stone-200 object-cover">

                            <div class="flex-1 space-y-3">
                                <input type="file" name="foto" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-sm">

                                @if($user->foto)
                                    <button formaction="{{ route('perfil.deletePhoto') }}" formmethod="POST" class="text-sm font-semibold text-red-600 transition hover:text-red-700">
                                        Eliminar foto
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-5">
                        <div>
                            <label class="mb-2 block text-sm font-semibold uppercase tracking-[0.2em] text-stone-500">Nombre usuario</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 focus:outline-none"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold uppercase tracking-[0.2em] text-stone-500">Email</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 focus:outline-none"
                            >
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-between">
                        <a href="{{ route('index') }}" class="inline-flex justify-center rounded-full border border-stone-300 bg-white px-6 py-3 font-semibold transition hover:bg-stone-200">
                            Cancelar
                        </a>

                        <button type="submit" class="inline-flex justify-center rounded-full bg-black px-6 py-3 font-semibold text-white transition hover:bg-stone-800">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </main>

    @include('partials.footer-banner')

</body>
</html>
