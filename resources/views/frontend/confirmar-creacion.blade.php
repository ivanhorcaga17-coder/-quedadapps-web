<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Partida - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    @include('partials.header')

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="mb-8 overflow-hidden rounded-[32px] border border-stone-200 bg-[linear-gradient(135deg,#111827_0%,#1f2937_55%,#44403c_100%)] px-6 py-10 text-white shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:px-8">
            <p class="text-sm uppercase tracking-[0.35em] text-stone-300">Último paso</p>
            <h1 class="mt-4 text-3xl font-semibold sm:text-4xl">Confirma los datos de la partida</h1>
            <p class="mt-3 max-w-2xl text-sm text-stone-200 sm:text-base">
                Revisa la información antes de guardarla. Aquí también puedes añadir jugadores por ID, nombre o email.
            </p>
        </section>

        <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <section class="overflow-hidden rounded-[32px] border border-stone-200 bg-white shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                <img src="{{ str_starts_with($pendingPartida['imagen'], 'images/') ? asset($pendingPartida['imagen']) : asset('storage/' . $pendingPartida['imagen']) }}" alt="{{ $pendingPartida['titulo'] }}" class="h-72 w-full object-cover sm:h-96">

                <div class="space-y-6 p-6 sm:p-8">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-stone-500">{{ $pendingPartida['deporte'] }}</p>
                        <h2 class="mt-3 text-3xl font-semibold sm:text-4xl">{{ $pendingPartida['titulo'] }}</h2>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <article class="rounded-3xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Fecha</p>
                            <p class="mt-2 font-semibold">{{ \Illuminate\Support\Carbon::parse($pendingPartida['fecha'])->format('d/m/Y') }}</p>
                        </article>
                        <article class="rounded-3xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Hora</p>
                            <p class="mt-2 font-semibold">{{ \Illuminate\Support\Carbon::parse($pendingPartida['fecha'])->format('H:i') }}</p>
                        </article>
                        <article class="rounded-3xl bg-stone-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Jugadores</p>
                            <p class="mt-2 font-semibold">{{ $pendingPartida['max_jugadores'] }}</p>
                        </article>
                    </div>

                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-stone-400">Ubicación</p>
                        <p class="mt-2 text-lg font-semibold">{{ $pendingPartida['lugar'] }}</p>
                        <a
                            href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($pendingPartida['lugar']) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-4 inline-flex items-center rounded-2xl bg-[linear-gradient(135deg,#111827_0%,#1f2937_55%,#0f766e_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_50px_rgba(17,24,39,0.18)] transition hover:scale-[1.01] hover:shadow-[0_24px_60px_rgba(15,118,110,0.22)]"
                        >
                            Como llegar al sitio
                        </a>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                @if($googleMapsApiKey)
                    <div class="overflow-hidden rounded-[32px] border border-stone-200 bg-white p-5 shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                        <div
                            class="js-google-map h-[280px] w-full rounded-3xl"
                            data-location="{{ $pendingPartida['lugar'] }}"
                            data-zoom="15"
                        ></div>
                    </div>
                @endif

                <div class="rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                    <form action="{{ route('partidas.finalize') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="jugadores" class="mb-2 block text-sm font-semibold text-stone-700">Añadir jugadores</label>
                            <textarea
                                id="jugadores"
                                name="jugadores"
                                rows="5"
                                placeholder="Ejemplo: 2, ivan, maria@email.com"
                                class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-4 text-sm outline-none transition focus:border-stone-700"
                            >{{ old('jugadores') }}</textarea>
                            <p class="mt-2 text-xs text-stone-500">Separa varios jugadores con comas. Puedes usar ID, nombre o email.</p>
                        </div>

                        <button class="w-full rounded-full bg-stone-900 px-6 py-3 font-semibold text-white transition hover:bg-stone-700">
                            Confirmar partida
                        </button>
                    </form>

                    <a href="{{ route('partidas.create') }}" class="mt-3 inline-flex w-full justify-center rounded-full border border-stone-300 px-6 py-3 text-center font-semibold transition hover:bg-stone-100">
                        Volver a editar
                    </a>
                </div>
            </aside>
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

                        node.innerHTML = '<div class="flex h-full items-center justify-center rounded-3xl bg-stone-100 px-6 text-center text-sm text-stone-500">No hemos podido cargar el mapa de esta ubicación.</div>';
                    });
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&libraries=places&callback=initMiniMaps" async defer></script>
    @endif
</body>
</html>
