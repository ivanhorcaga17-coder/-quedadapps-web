<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Partida - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 px-4 py-10 text-stone-900 sm:px-6">
    <div class="mx-auto max-w-3xl rounded-[32px] border border-stone-200 bg-white p-8 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-stone-500">Administrador</p>
                <h1 class="mt-2 text-3xl font-semibold">Editar partida</h1>
            </div>

            <a href="{{ route('admin.index') }}" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-medium transition hover:bg-stone-100">
                Volver al panel
            </a>
        </div>

        @if($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.partidas.update', $partida) }}" method="POST" class="mt-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-medium">Deporte</label>
                <input
                    type="text"
                    name="deporte"
                    value="{{ old('deporte', $partida->deporte) }}"
                    class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 outline-none transition focus:border-stone-700"
                    required
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Fecha y hora</label>
                <input
                    type="datetime-local"
                    name="fecha"
                    value="{{ old('fecha', \Carbon\Carbon::parse($partida->fecha)->format('Y-m-d\TH:i')) }}"
                    class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 outline-none transition focus:border-stone-700"
                    required
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Lugar</label>
                <input
                    type="text"
                    name="lugar"
                    value="{{ old('lugar', $partida->lugar) }}"
                    class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 outline-none transition focus:border-stone-700"
                    required
                >
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">Maximo de jugadores</label>
                <input
                    type="number"
                    min="1"
                    name="max_jugadores"
                    value="{{ old('max_jugadores', $partida->max_jugadores) }}"
                    class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 outline-none transition focus:border-stone-700"
                    required
                >
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button class="rounded-full bg-stone-900 px-6 py-3 font-semibold text-white transition hover:bg-stone-700">
                    Guardar cambios
                </button>

                <a href="{{ route('admin.index') }}" class="rounded-full border border-stone-300 px-6 py-3 text-center font-semibold transition hover:bg-stone-100">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>
