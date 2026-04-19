<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmacion de Partida - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    @include('partials.header')

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('index') }}" class="inline-flex items-center gap-3 rounded-full border border-stone-300 bg-white px-4 py-2 text-sm font-medium transition hover:bg-stone-100">
                <span>&larr;</span>
                Volver al inicio
            </a>

            @auth
                <a href="{{ route('perfil') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900">
                    Mi perfil
                </a>
            @endauth
        </div>

        <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="overflow-hidden rounded-[32px] border border-stone-200 bg-white shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                <img
                    src="{{ asset('storage/' . $partida->imagen) }}"
                    alt="{{ $partida->titulo }}"
                    class="h-72 w-full object-cover sm:h-96"
                >

                <div class="space-y-6 p-6 sm:p-8">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-stone-500">{{ $partida->deporte }}</p>
                        <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">{{ $partida->titulo }}</h1>
                        <p class="mt-3 text-base text-stone-600">
                            Tu partida se ha creado correctamente y ya esta lista para gestionarse desde la plataforma.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <article class="rounded-3xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Fecha</p>
                            <p class="mt-2 font-semibold">{{ \Carbon\Carbon::parse($partida->fecha)->format('d/m/Y') }}</p>
                        </article>
                        <article class="rounded-3xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Hora</p>
                            <p class="mt-2 font-semibold">{{ \Carbon\Carbon::parse($partida->fecha)->format('H:i') }}</p>
                        </article>
                        <article class="rounded-3xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Jugadores</p>
                            <p class="mt-2 font-semibold">{{ $partida->max_jugadores }}</p>
                        </article>
                    </div>

                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-stone-400">Ubicacion</p>
                        <p class="mt-2 text-lg font-semibold">{{ $partida->lugar }}</p>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="overflow-hidden rounded-[32px] border border-stone-200 bg-white p-5 shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                    @if($googleMapsApiKey)
                        <iframe
                            width="100%"
                            height="280"
                            style="border:0; border-radius: 24px;"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed/v1/place?key={{ $googleMapsApiKey }}&q={{ urlencode($partida->lugar) }}">
                        </iframe>
                    @e