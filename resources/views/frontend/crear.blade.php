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

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <section class="rounded-[32px] border border-stone-200 bg-white p-8 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-10">
                <p class="text-sm uppercase tracking-[0.35em] text-stone-500">Nueva actividad</p>
                <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">Crear partida</h1>
                <p class="mt-3 text-sm text-stone-600 sm:text-base">
                    Rellena los datos y antes de guardar verás una página de confirmación con todos los detalles.
                </p>

                <form action="{{ route('partidas.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="deporte" class="mb-3 block font-semibold">Deporte</label>
                        <input
                            id="deporte"
                            type="text"
                            name="deporte"
                            list="sports-list"
                            value="{{ old('deporte') }}"
                            placeholder="Escribe o elige un deporte"
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 outline-none transition focus:border-stone-700"
                            required
                        >
                        <datalist id="sports-list">
                            @foreach($sports as $sport)
                                <option value="{{ $sport }}"></option>
                            @endforeach
                        </datalist>

                        <div class="mt-4">
                            <p class="mb-3 text-xs uppercase tracking-[0.25em] text-stone-400">Accesos rápidos</p>
                            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach(array_slice($sports, 0, 12) as $sport)
                                    <button
                                        type="button"
                                        class="sport-chip rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-left text-sm font-medium text-stone-700 transition hover:bg-stone-100"
                                        data-sport="{{ $sport }}"
                                    >
                                        {{ $sport }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block font-semibold">Fecha y hora</label>
                            <input type="datetime-local" name="fecha" value="{{ old('fecha') }}" class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-3" required>
                        </div>

                        <div>
                            <label class="mb-2 block font-semibold">Número de jugadores</label>
                            <input type="number" name="max_jugadores" min="1" value="{{ old('max_jugadores') }}" placeholder="Introduce el número"
                                class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-3" required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block font-semibold">Ubicación</label>
                        <input id="autocomplete" type="text" name="lugar" value="{{ old('lugar') }}" placeholder="Introduce una ubicación"
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-3" required>
                    </div>

                    <div>
                        <label class="mb-2 block font-semibold">Añade imagen</label>
                        <input type="file" name="imagen" accept="image/*"
                            class="w-full rounded-2xl border border-stone-300 bg-stone-50 p-3"
                            id="imagenInput">
                        <p class="mt-2 text-sm text-stone-500">
                            Si no subes imagen, se asignará una imagen aleatoria según el deporte.
                        </p>
                        <div id="imagePreviewWrapper" class="mt-4 hidden overflow-hidden rounded-3xl border border-stone-200 bg-stone-50">
                            <img id="imagePreview" src="" alt="Previsualización de la imagen" class="h-56 w-full object-cover">
                        </div>
                    </div>

                    <button class="w-full rounded-full bg-stone-900 py-3 font-semibold text-white transition hover:bg-stone-700">
                        Revisar antes de confirmar
                    </button>
                </form>
            </section>

            <aside class="rounded-[32px] border border-stone-200 bg-white p-8 shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
                <p class="text-sm uppercase tracking-[0.35em] text-stone-500">Consejos</p>
                <div class="mt-5 space-y-5 text-sm leading-6 text-stone-600">
                    <p>Usa una ubicación clara para que los mini-mapas y el calendario muestren la partida correctamente.</p>
                    <p>En la siguiente pantalla podrás revisar todos los datos y añadir jugadores por nombre o ID antes de guardar.</p>
                    <p>El chat se activará automáticamente una vez se confirme y cree la actividad.</p>
                </div>
            </aside>
        </div>
    </main>

    @if($googleMapsApiKey)
        <script>
            function initAutocomplete() {
                const input = document.getElementById('autocomplete');

                if (!input || !window.google?.maps?.places) {
                    return;
                }

                const autocomplete = new google.maps.places.Autocomplete(input, {
                    componentRestrictions: { country: 'es' },
                    fields: ['formatted_address', 'name', 'geometry'],
                });

                autocomplete.addListener('place_changed', function () {
                    const place = autocomplete.getPlace();

                    if (!place) {
                        return;
                    }

                    if (place.name && place.formatted_address) {
                        input.value = `${place.name}, ${place.formatted_address}`;
                        return;
                    }

                    if (place.formatted_address) {
                        input.value = place.formatted_address;
                        return;
                    }

                    if (place.name) {
                        input.value = place.name;
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                const sportInput = document.getElementById('deporte');
                const sportChips = document.querySelectorAll('.sport-chip');
                const imageInput = document.getElementById('imagenInput');
                const imagePreview = document.getElementById('imagePreview');
                const imagePreviewWrapper = document.getElementById('imagePreviewWrapper');

                sportChips.forEach((chip) => {
                    chip.addEventListener('click', function () {
                        if (!sportInput) {
                            return;
                        }

                        sportInput.value = this.dataset.sport || '';
                        sportInput.dispatchEvent(new Event('input'));
                    });
                });

                imageInput?.addEventListener('change', function (event) {
                    const file = event.target.files?.[0];

                    if (!file || !imagePreview || !imagePreviewWrapper) {
                        imagePreviewWrapper?.classList.add('hidden');
                        return;
                    }

                    const objectUrl = URL.createObjectURL(file);
                    imagePreview.src = objectUrl;
                    imagePreviewWrapper.classList.remove('hidden');
                });
            });
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&libraries=places&callback=initAutocomplete" async defer></script>
    @endif

    @include('partials.footer-banner')
</body>
</html>
