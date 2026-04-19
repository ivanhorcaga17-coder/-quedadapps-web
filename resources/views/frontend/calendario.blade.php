<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    @include('partials.header')

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-8">
            <p class="text-sm uppercase tracking-[0.35em] text-stone-500">Calendario</p>
            <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">Todas las partidas ordenadas por mes</h1>
            <p class="mt-3 max-w-2xl text-sm text-stone-600 sm:text-base">
                Consulta rapidamente el calendario deportivo y localiza los eventos proximos por bloque temporal.
            </p>
        </section>

        <section class="mt-8 space-y-8">
            @forelse($partidas as $mes => $partidasMes)
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <h2 class="text-2xl font-semibold capitalize">{{ $mes }}</h2>
                        <span class="rounded-full bg-stone-100 px-3 py-1 text-sm font-semibold text-stone-900">
                            {{ $partidasMes->count() }} eventos
                        </span>
                    </div>

                    <div class="grid gap-4">
                        @foreach($partidasMes as $partida)
                            <article class="grid gap-4 rounded-[28px] border border-stone-200 bg-white p-5 shadow-[0_16px_60px_rgba(28,25,23,0.06)] md:grid-cols-[180px_1fr_auto] md:items-center">
                                <div class="rounded-3xl bg-stone-900 px-5 py-4 text-center text-white">
                                    <p class="text-xs uppercase tracking-[0.25em] text-stone-300">{{ $partida->fecha->translatedFormat('D') }}</p>
                                    <p class="mt-2 text-4xl font-semibold">{{ $partida->fecha->format('d') }}</p>
                                    <p class="mt-2 text-sm text-stone-200">{{ $partida->fecha->format('H:i') }}</p>
                                </div>

                                <div>
                                    <p class="text-xs uppercase tracking-[0.25em] text-stone-400">{{ $partida->deporte }}</p>
                                    <h3 class="mt-2 text-2xl font-semibold">{{ $partida->titulo }}</h3>
                                    <p class="mt-2 text-sm text-stone-600">{{ $partida->lugar }}</p>
                                    <p class="mt-1 text-sm text-stone-500">Organiza {{ $partida->creador?->name ?? 'un usuario' }}</p>
                                </div>

                                <div class="flex flex-col gap-3 md:items-end">
                                    <span class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-600">
                                        {{ $partida->asistencias_count }} asistentes
                                    </span>
                                    <a href="{{ route('partidas.confirmar', $partida) }}" class="rounded-full border border-stone-300 px-5 py-3 text-sm font-semibold transition hover:bg-stone-100">
                                        Ver partida
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @empty
                <article class="rounded-[32px] border border-dashed border-stone-300 bg-white p-8 text-center text-stone-500">
                    Aun no hay partidas programadas en el calendario.
                </article>
            @endforelse
        </section>
    </main>

    @include('partials.footer-banner')
</body>
</html>
