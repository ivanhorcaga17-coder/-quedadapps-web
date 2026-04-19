<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-[32px] border border-stone-200 bg-white shadow-[0_24px_80px_rgba(28,25,23,0.08)]">
            <header class="border-b border-stone-200 bg-[linear-gradient(135deg,#0f172a_0%,#1c1917_55%,#44403c_100%)] px-6 py-8 text-white sm:px-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-stone-300">Administracion</p>
                        <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">Panel de control de QuedadApps</h1>
                        <p class="mt-3 max-w-2xl text-sm text-stone-200 sm:text-base">
                            Gestiona partidas, usuarios y reviews desde un unico panel protegido.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('index') }}" class="rounded-full border border-white/20 px-4 py-2 text-sm font-medium text-white transition hover:bg-white/10">
                            Volver a la web
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-stone-900 transition hover:bg-stone-100">
                                Cerrar sesion
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="space-y-8 px-6 py-8 sm:px-8">
                @if(session('success'))
                    <div class="rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-sm text-stone-900">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->has('admin'))
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        {{ $errors->first('admin') }}
                    </div>
                @endif

                <section class="grid gap-4 md:grid-cols-3">
                    <article class="rounded-3xl border border-stone-200 bg-stone-50 p-6">
                        <p class="text-sm uppercase tracking-[0.25em] text-stone-500">Partidas</p>
                        <p class="mt-4 text-4xl font-semibold">{{ $stats['partidas'] }}</p>
                    </article>
                    <article class="rounded-3xl border border-stone-200 bg-stone-50 p-6">
                        <p class="text-sm uppercase tracking-[0.25em] text-stone-500">Usuarios</p>
                        <p class="mt-4 text-4xl font-semibold">{{ $stats['usuarios'] }}</p>
                    </article>
                    <article class="rounded-3xl border border-stone-200 bg-stone-50 p-6">
                        <p class="text-sm uppercase tracking-[0.25em] text-stone-500">Reviews</p>
                        <p class="mt-4 text-4xl font-semibold">{{ $stats['reviews'] }}</p>
                    </article>
                </section>

                <section class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold">Partidas</h2>
                            <p class="text-sm text-stone-500">Edita o elimina cualquier partida publicada.</p>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-3xl border border-stone-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-stone-200 text-sm">
                                <thead class="bg-stone-100 text-left text-stone-600">
                                    <tr>
                                        <th class="px-4 py-3 font-medium">Titulo</th>
                                        <th class="px-4 py-3 font-medium">Deporte</th>
                                        <th class="px-4 py-3 font-medium">Fecha</th>
                                        <th class="px-4 py-3 font-medium">Creador</th>
                                        <th class="px-4 py-3 font-medium text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-200 bg-white">
                                    @forelse($partidas as $partida)
                                        <tr>
                                            <td class="px-4 py-4 font-medium">{{ $partida->titulo }}</td>
                                            <td class="px-4 py-4">{{ $partida->deporte }}</td>
                                            <td class="px-4 py-4">{{ \Carbon\Carbon::parse($partida->fecha)->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-4">{{ $partida->creador?->name ?? 'Sin creador' }}</td>
                                            <td class="px-4 py-4">
                                                <div class="flex justify-end gap-3">
                                                    <a href="{{ route('admin.partidas.edit', $partida) }}" class="rounded-full border border-stone-300 px-4 py-2 font-medium transition hover:bg-stone-100">
                                                        Editar
                                                    </a>
                                                    <form action="{{ route('admin.partidas.destroy', $partida) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="rounded-full bg-red-600 px-4 py-2 font-medium text-white transition hover:bg-red-700">
                                                            Borrar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-6 text-center text-stone-500">No hay partidas registradas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{ $partidas->links() }}
                </section>

                <section class="space-y-4">
                    <div>
                        <h2 class="text-2xl font-semibold">Usuarios</h2>
                        <p class="text-sm text-stone-500">Listado completo de usuarios autenticables de la tabla `usuarios`.</p>
                    </div>

                    <div class="overflow-hidden rounded-3xl border border-stone-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-stone-200 text-sm">
                                <thead class="bg-stone-100 text-left text-stone-600">
                                    <tr>
                                        <th class="px-4 py-3 font-medium">Nombre</th>
                                        <th class="px-4 py-3 font-medium">Email</th>
                                        <th class="px-4 py-3 font-medium">Rol</th>
                                        <th class="px-4 py-3 font-medium text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-200 bg-white">
                                    @forelse($usuarios as $usuario)
                                        <tr>
                                            <td class="px-4 py-4 font-medium">{{ $usuario->name }}</td>
                                            <td class="px-4 py-4">{{ $usuario->email }}</td>
                                            <td class="px-4 py-4">
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $usuario->isAdmin() ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-700' }}">
                                                    {{ $usuario->role }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex justify-end">
                                                    <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="rounded-full bg-red-600 px-4 py-2 font-medium text-white transition hover:bg-red-700">
                                                            Borrar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-6 text-center text-stone-500">No hay usuarios registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{ $usuarios->links() }}
                </section>

                <section class="space-y-4">
                    <div>
                        <h2 class="text-2xl font-semibold">Reviews</h2>
                        <p class="text-sm text-stone-500">Moderacion de valoraciones publicadas en la landing de descarga.</p>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        @forelse($reviews as $review)
                            <article class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-stone-500">
                                            {{ $review->user?->name ?? 'Usuario anonimo' }}
                                        </p>
                                        <p class="mt-2 text-lg font-semibold">{{ str_repeat('★', $review->rating) }}</p>
                                    </div>

                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-full bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                                            Borrar
                                        </button>
                                    </form>
                                </div>

                                <p class="mt-4 text-sm leading-6 text-stone-700">{{ $review->comment ?: 'Sin comentario.' }}</p>
                                <p class="mt-4 text-xs uppercase tracking-[0.25em] text-stone-400">{{ $review->created_at?->format('d/m/Y H:i') }}</p>
                            </article>
                        @empty
                            <article class="rounded-3xl border border-stone-200 bg-stone-50 p-6 text-sm text-stone-500">
                                No hay reviews registradas.
                            </article>
                        @endforelse
                    </div>

                    {{ $reviews->links() }}
                </section>
            </main>
        </div>
    </div>
</body>
</html>
