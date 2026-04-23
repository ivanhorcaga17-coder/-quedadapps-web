<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Partidas - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    @include('partials.header')

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-[32px] border border-stone-200 bg-[linear-gradient(135deg,#111827_0%,#1f2937_55%,#44403c_100%)] px-6 py-10 text-white shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:px-8">
            <p class="text-sm uppercase tracking-[0.35em] text-stone-300">Buscar partidas</p>
            <div class="mt-4 flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h1 class="text-3xl font-semibold sm:text-4xl">Encuentra tu siguiente quedada deportiva</h1>
                    <p class="mt-3 text-sm text-stone-200 sm:text-base">
                        Filtra por deporte, ciudad o fecha y revisa rapidamente cuantas plazas hay disponibles.
                    </p>
                </div>

                <div class="rounded-full bg-white/10 px-5 py-3 text-sm font-medium text-stone-100">
                    {{ $partidas->total() }} partidas encontradas
                </div>
            </div>
        </section>

        <section class="mt-8 rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
            <form method="GET" action="{{ route('buscar') }}" class="grid gap-4 lg:grid-cols-[1fr_1fr_1fr_auto]">
                <div>
                    <label class="mb-2 block text-sm font-medium text-stone-600">Deporte</label>
                    <select name="deporte" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 outline-none transition focus:border-stone-700">
                        <option value="">Todos</option>
                        @foreach($sports as $deporte)
                            <option value="{{ $deporte }}" @selected(($filtros['deporte'] ?? '') === $deporte)>{{ $deporte }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-stone-600">Lugar</label>
                    <input
                        type="text"
                        name="lugar"
                        value="{{ $filtros['lugar'] ?? '' }}"
                        placeholder="Madrid, Valencia, pista..."
                        class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 outline-none transition focus:border-stone-700"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-stone-600">Fecha</label>
                    <input
                        type="date"
                        name="fecha"
                        value="{{ $filtros['fecha'] ?? '' }}"
                        class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 outline-none transition focus:border-stone-700"
                    >
                </div>

                <div class="flex items-end gap-3">
                    <button class="rounded-full bg-stone-900 px-6 py-3 font-semibold text-white transition hover:bg-stone-700">
                        Filtrar
                    </button>
                    <a href="{{ route('buscar') }}" class="rounded-full border border-stone-300 px-6 py-3 font-semibold transition hover:bg-stone-100">
                        Limpiar
                    </a>
                </div>
            </form>
        </section>

        <section class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($partidas as $partida)
                <article class="overflow-hidden rounded-[32px] border border-stone-200 bg-white shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                    <img
                        src="{{ $partida->image_url }}"
                        alt="{{ $partida->titulo }}"
                        class="h-56 w-full object-cover"
                    >

                    <div class="space-y-5 p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-stone-500">{{ $partida->deporte }}</p>
                                <h2 class="mt-2 text-2xl font-semibold">{{ $partida->titulo }}</h2>
                            </div>
                            <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-600">
                                {{ $partida->asistencias_count }}/{{ $partida->max_jugadores }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-stone-600">
                            <p>{{ $partida->fecha->format('d/m/Y H:i') }}</p>
                            <p>{{ $partida->lugar }}</p>
                            <p>Creador: {{ $partida->creador?->name ?? 'Usuario no disponible' }}</p>
                        </div>

                        @if($partida->starts_soon)
                            <div class="rounded-2xl bg-amber-50 px-4 py-3 text-sm font-medium text-amber-900">
                                Empieza pronto, {{ $partida->starts_in_text }}.
                            </div>
                        @endif

                        @if($googleMapsApiKey)
                            <div class="overflow-hidden rounded-3xl border border-stone-200 bg-stone-50">
                                <div
                                    class="js-google-map h-[180px] w-full"
                                    data-location="{{ $partida->lugar }}"
                                    data-zoom="15"
                                ></div>
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('partidas.showPage', $partida) }}" class="rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-stone-700">
                                Ver detalle
                            </a>
                            @auth
                                @if(auth()->id() === $partida->creador_id)
                                    <form action="{{ route('partidas.destroy', $partida) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar tu partida?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                                            Borrar partida
                                        </button>
                                    </form>
                                @elseif(in_array($partida->id, $joinedPartidaIds, true))
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
                                        <button class="rounded-full border border-stone-300 px-5 py-3 text-sm font-semibold transition hover:bg-stone-100">
                                            Apuntarme
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
                <article class="rounded-[32px] border border-dashed border-stone-300 bg-white p-8 text-center text-stone-500 md:col-span-2 xl:col-span-3">
                    No hay partidas que coincidan con los filtros actuales.
                </article>
            @endforelse
        </section>

        <div class="mt-8">
            {{ $partidas->links() }}
        </div>
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

                        node.innerHTML = '<div class="flex h-full items-center justify-center bg-stone-100 px-6 text-center text-sm text-stone-500">No hemos podido cargar el mapa de esta ubicación.</div>';
                    });
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&libraries=places&callback=initMiniMaps" async defer></script>
    @endif
</body>
</html>
