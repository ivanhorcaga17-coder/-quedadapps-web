<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Partida - Quedadapps</title>

    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-black font-sans">

    @include('partials.header')

    <div class="w-full px-4 py-10 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-4xl rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-8 lg:p-10">

            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm uppercase tracking-[0.35em] text-stone-500">Nueva quedada</p>
                <h1 class="mt-3 text-3xl font-bold sm:text-4xl">Crear partida</h1>
                <p class="mt-3 text-sm text-stone-600 sm:text-base">
                    Configura el deporte, la ubicación y abre el chat de la actividad en cuanto la crees.
                </p>
            </div>

            <form action="{{ route('partidas.store') }}" method="POST" enctype="multipart/form-data" class="mt-10 grid gap-8 lg:grid-cols-2">
                @csrf

                <div class="space-y-6">

                    <!-- SELECCIÓN DE DEPORTE -->
                    <div>
                        <label class="mb-3 block font-semibold">Deporte</label>

                        <input type="hidden" name="deporte" id="deporte-selected" required>

                        <div class="overflow-x-auto pb-2">
                            <div id="sports-picker" class="flex min-w-max gap-3">

                                @foreach([
                                    'Fútbol', 'Baloncesto', 'Pádel', 'Tenis', 'Running', 'Senderismo',
                                    'Voleibol', 'Natación', 'Crossfit', 'Ciclismo', 'Skate', 'Ping Pong',
                                    'Béisbol', 'Rugby', 'Boxeo', 'Escalada', 'Yoga', 'Pilates',
                                    'Gimnasio', 'Airsoft', 'Paintball', 'E-Sports', 'Dardos', 'Billar'
                                ] as $sport)
                                    <button
                                        type="button"
                                        class="sport-chip whitespace-nowrap rounded-full border border-stone-300 px-4 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-100"
                                        data-sport="{{ $sport }}"
                                    >
                                        {{ $sport }}
                                    </button>
                                @endforeach

                            </div>
                        </div>

                        <p class="mt-2 text-xs text-stone-500">Desliza horizontalmente para ver todos los deportes.</p>
                    </div>

                    <!-- FECHA -->
                    <div>
                        <label class="mb-2 block font-semibold">Fecha y hora</label>
                        <input type="datetime-local" name="fecha"
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-3 outline-none transition focus:border-stone-700"
                            required>
                    </div>

                    <!-- UBICACIÓN -->
                    <div>
                        <label class="mb-2 block font-semibold">Ubicación</label>
                        <input id="autocomplete" type="text" name="lugar" placeholder="Introduce una ubicación"
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-3 outline-none transition focus:border-stone-700"
                            required>

                        @if(!$googleMapsApiKey)
                            <p class="mt-2 text-sm text-stone-500">
                                Configura `GOOGLE_MAPS_API_KEY` en tu `.env` para activar el autocompletado de ubicaciones.
                            </p>
                        @endif
                    </div>

                </div>

                <div class="space-y-6">

                    <!-- JUGADORES -->
                    <div>
                        <label class="mb-2 block font-semibold">Número de jugadores</label>
                        <input type="number" name="max_jugadores" min="1" placeholder="Introduce el número"
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-3 outline-none transition focus:border-stone-700"
                            required>
                    </div>

                    <!-- IMAGEN -->
                    <div>
                        <label class="mb-2 block font-semibold">Imagen (opcional)</label>
                        <input type="file" name="imagen"
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-3 outline-none transition focus:border-stone-700">
                    </div>

                </div>

                <div class="lg:col-span-2 flex justify-center">
                    <button
                        class="rounded-full bg-stone-900 px-10 py-4 text-white font-semibold shadow hover:bg-stone-700 transition">
                        Crear partida
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- GOOGLE MAPS AUTOCOMPLETE (CORREGIDO) -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initAutocomplete"
        async defer>
    </script>

    <script>
        // Selección de deporte
        document.querySelectorAll('.sport-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.sport-chip').forEach(b => b.classList.remove('bg-stone-900', 'text-white'));
                btn.classList.add('bg-stone-900', 'text-white');
                document.getElementById('deporte-selected').value = btn.dataset.sport;
            });
        });

        // Google Places Autocomplete
        function initAutocomplete() {
            const input = document.getElementById('autocomplete');
            if (input) {
                new google.maps.places.Autocomplete(input);
            }
        }
    </script>

</body>
</html>
