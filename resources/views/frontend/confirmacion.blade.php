<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Partida - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    @include('partials.header')

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-sm text-stone-900">
                {{ session('success') }}
            </div>
        @endif

        @if($partida->starts_soon)
            <div class="mb-6 rounded-2xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                Esta partida empieza pronto, {{ $partida->starts_in_text }}.
            </div>
        @endif

        <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="overflow-hidden rounded-[32px] border border-stone-200 bg-white shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                <img src="{{ $partida->image_url }}" alt="{{ $partida->titulo }}" class="h-72 w-full object-cover sm:h-96">

                <div class="space-y-6 p-6 sm:p-8">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-stone-500">{{ $partida->deporte }}</p>
                        <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">{{ $partida->titulo }}</h1>
                        <p class="mt-3 text-base text-stone-600">
                            Actividad organizada por {{ $partida->creador?->name ?? 'un usuario' }}.
                        </p>
                        @if($partida->starts_soon)
                            <span class="mt-4 inline-flex rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-800">
                                Empieza {{ $partida->starts_in_text }}
                            </span>
                        @endif
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <article class="rounded-3xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Fecha</p>
                            <p class="mt-2 font-semibold">{{ $partida->fecha->format('d/m/Y') }}</p>
                        </article>
                        <article class="rounded-3xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Hora</p>
                            <p class="mt-2 font-semibold">{{ $partida->fecha->format('H:i') }}</p>
                        </article>
                        <article class="rounded-3xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Jugadores</p>
                            <p class="mt-2 font-semibold">{{ $partida->max_jugadores }}</p>
                        </article>
                    </div>

                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-stone-400">Ubicación</p>
                        <p class="mt-2 text-lg font-semibold">{{ $partida->lugar }}</p>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                @if($googleMapsApiKey)
                    <div class="overflow-hidden rounded-[32px] border border-stone-200 bg-white p-5 shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                        <div
                            class="js-google-map h-[280px] w-full rounded-3xl"
                            data-location="{{ $partida->lugar }}"
                            data-zoom="15"
                        ></div>
                    </div>
                @endif

                <div class="rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                    <p class="text-sm uppercase tracking-[0.25em] text-stone-400">Ajustes rápidos</p>
                    <div class="mt-5 flex flex-col gap-3">
                        @auth
                            @if(auth()->id() === $partida->creador_id)
                                <div class="rounded-2xl bg-stone-50 px-4 py-3 text-sm text-stone-600">
                                    Estás viendo tu propia partida. Puedes borrarla cuando quieras.
                                </div>
                                <form action="{{ route('partidas.destroy', $partida) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar tu partida?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full rounded-full bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-700">
                                        Borrar mi partida
                                    </button>
                                </form>
                            @elseif($authUserJoined)
                                <form action="{{ route('asistencia.leave', $partida) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full rounded-full border border-red-300 px-5 py-3 font-semibold text-red-700 transition hover:bg-red-50">
                                        Ya estás apuntado, cancelar
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('asistencia.join', $partida) }}" method="POST">
                                    @csrf
                                    <button class="w-full rounded-full bg-stone-900 px-5 py-3 font-semibold text-white transition hover:bg-stone-700">
                                        Apuntarme a la partida
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="rounded-full border border-stone-300 px-5 py-3 text-center font-semibold transition hover:bg-stone-100">
                                Inicia sesión para apuntarte
                            </a>
                        @endauth

                        <a href="{{ route('buscar') }}" class="rounded-full border border-stone-300 px-5 py-3 text-center font-semibold transition hover:bg-stone-100">
                            Ver más partidas
                        </a>
                    </div>
                </div>
            </aside>
        </section>

        <section class="mt-8 grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
            <div class="rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] text-stone-400">Asistencia</p>
                        <h2 class="mt-2 text-2xl font-semibold">Jugadores apuntados</h2>
                    </div>
                    <span class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">
                        {{ $partida->asistencias->count() }}/{{ $partida->max_jugadores }}
                    </span>
                </div>

                <div class="mt-6 grid gap-3">
                    @forelse($partida->asistencias as $asistencia)
                        <div class="flex items-center gap-3 rounded-2xl bg-stone-50 px-4 py-3">
                            <img src="{{ $asistencia->usuario?->avatar_url ?? asset('frontend/img/perfil.png') }}" alt="" class="h-11 w-11 rounded-full object-cover border border-stone-200">
                            <div>
                                <p class="font-semibold text-stone-900">{{ $asistencia->usuario?->name ?? 'Usuario' }}</p>
                                <p class="text-sm text-stone-500">{{ $asistencia->estado ?? 'confirmado' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-stone-500">Todavía no hay jugadores añadidos.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                <p class="text-sm uppercase tracking-[0.25em] text-stone-400">Chat de la partida</p>
                <h2 class="mt-2 text-2xl font-semibold">Conversación</h2>

                <div class="mt-6 max-h-[360px] space-y-3 overflow-y-auto pr-2">
                    @forelse($partida->chatMessages as $message)
                        <article class="rounded-2xl bg-stone-50 p-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $message->usuario?->avatar_url ?? asset('frontend/img/perfil.png') }}" alt="" class="h-10 w-10 rounded-full object-cover border border-stone-200">
                                <div>
                                    <p class="font-semibold text-stone-900">{{ $message->usuario?->name ?? 'Usuario' }}</p>
                                    <p class="text-xs text-stone-500">{{ $message->created_at?->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-stone-700">{{ $message->mensaje }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-stone-500">Todavía no hay mensajes. Sé el primero en escribir.</p>
                    @endforelse
                </div>

                @auth
                    <form action="{{ route('partidas.chat.store', $partida) }}" method="POST" class="mt-6 space-y-3">
                        @csrf
                        <textarea
                            name="mensaje"
                            rows="3"
                            placeholder="Escribe algo para los jugadores de esta partida..."
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-4 text-sm outline-none transition focus:border-stone-700"
                            required
                        ></textarea>
                        <button class="rounded-full bg-stone-900 px-6 py-3 font-semibold text-white transition hover:bg-stone-700">
                            Enviar mensaje
                        </button>
                    </form>
                @else
                    <p class="mt-6 text-sm text-stone-500">
                        Inicia sesión para participar en el chat.
                    </p>
                @endauth
            </div>
        </section>
    </main>

    @include('partials.footer-banner')

    @if($googleMapsApiKey)
        <script>
            function initMiniMaps() {
                const mapNodes = document.querySelectorAll('.js-google-map');

                if (!mapNodes.length || !window.google?.maps) {
                    return;
                }

                const geocoder = new google.maps.Geocoder();

                mapNodes.forEach((node) => {
                    const location = node.dataset.location;
                    const zoom = Number(node.dataset.zoom || 15);

                    if (!location) {
                        return;
                    }

                    const map = new google.maps.Map(node, {
                        zoom,
                        disableDefaultUI: true,
                        gestureHandling: 'cooperative',
                        mapTypeControl: false,
                        streetViewControl: false,
                        fullscreenControl: false,
                    });

                    geocoder.geocode({ address: location }, (results, status) => {
                        if (status === 'OK' && results?.[0]?.geometry?.location) {
                            map.setCenter(results[0].geometry.location);
                            new google.maps.Marker({
                                map,
                                position: results[0].geometry.location,
                            });
                            return;
                        }

                        node.innerHTML = '<div class="flex h-full items-center justify-center rounded-3xl bg-stone-100 px-6 text-center text-sm text-stone-500">No hemos podido cargar el mapa de esta ubicación.</div>';
                    });
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&libraries=places&callback=initMiniMaps" async defer></script>
    @endif
</body>
</html>
