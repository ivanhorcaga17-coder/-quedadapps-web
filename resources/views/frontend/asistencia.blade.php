<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    @include('partials.header')

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-sm text-stone-900">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-8">
            <p class="text-sm uppercase tracking-[0.35em] text-stone-500">Asistencia</p>
            <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">Gestiona tus confirmaciones</h1>
            <p class="mt-3 max-w-2xl text-sm text-stone-600 sm:text-base">
                Revisa las partidas a las que ya te has apuntado y confirma nuevas asistencias en un solo lugar.
            </p>
        </section>

        <section class="mt-8 grid gap-8 xl:grid-cols-[0.95fr_1.05fr]">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold">Mis asistencias</h2>
                    @guest
                        <a href="{{ route('login') }}" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold transition hover:bg-stone-100">
                            Inicia sesión
                        </a>
                    @endguest
                </div>

                @auth
                    @forelse($misAsistencias as $asistencia)
                        <article class="rounded-[28px] border border-stone-200 bg-white p-5 shadow-[0_16px_60px_rgba(28,25,23,0.06)]">
                            <p class="text-xs uppercase tracking-[0.25em] text-stone-500">{{ $asistencia->estado ?? 'confirmado' }}</p>
                            <h3 class="mt-2 text-2xl font-semibold">{{ $asistencia->partida?->titulo }}</h3>
                            <p class="mt-2 text-sm text-stone-600">{{ $asistencia->partida?->fecha?->format('d/m/Y H:i') }}</p>
                            <p class="mt-1 text-sm text-stone-500">{{ $asistencia->partida?->lugar }}</p>

                            @if($asistencia->partida)
                                <form action="{{ route('asistencia.leave', $asistencia->partida) }}" method="POST" class="mt-5">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                                        Cancelar asistencia
                                    </button>
                                </form>
                            @endif
                        </article>
                    @empty
                        <article class="rounded-[28px] border border-dashed border-stone-300 bg-white p-8 text-center text-stone-500">
                            Aun no te has apuntado a ninguna partida.
                        </article>
                    @endforelse
                @else
                    <article class="rounded-[28px] border border-dashed border-stone-300 bg-white p-8 text-center text-stone-500">
                        Debes iniciar sesión para gestionar tu asistencia.
                    </article>
                @endauth
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-semibold">Proximas partidas</h2>

                @forelse($proximasPartidas as $partida)
                    <article class="rounded-[28px] border border-stone-200 bg-white p-5 shadow-[0_16px_60px_rgba(28,25,23,0.06)]">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-stone-400">{{ $partida->deporte }}</p>
                                <h3 class="mt-2 text-2xl font-semibold">{{ $partida->titulo }}</h3>
                                <p class="mt-2 text-sm text-stone-600">{{ $partida->fecha->format('d/m/Y H:i') }}</p>
                                <p class="mt-1 text-sm text-stone-500">{{ $partida->lugar }}</p>
                            </div>

                            <div class="flex flex-col gap-3 md:items-end">
                                <span class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-600">
                                    {{ $partida->asistencias_count }}/{{ $partida->max_jugadores }} plazas ocupadas
                                </span>

                                @auth
                                    @if(in_array($partida->id, $misPartidasIds, true))
                                        <form action="{{ route('asistencia.leave', $partida) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-full border border-red-300 px-5 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-50">
                                                Ya apuntado, cancelar
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('asistencia.join', $partida) }}" method="POST">
                                            @csrf
                                            <button class="rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-stone-700">
                                                Confirmar asistencia
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="rounded-full border border-stone-300 px-5 py-3 text-sm font-semibold transition hover:bg-stone-100">
                                        Inicia sesión
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="rounded-[28px] border border-dashed border-stone-300 bg-white p-8 text-center text-stone-500">
                        No hay partidas proximas disponibles.
                    </article>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer-banner')
</body>
</html>
