<header class="border-b border-stone-200 bg-white">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('index') }}" class="flex items-center gap-3">
            <span class="inline-flex rounded-2xl border border-stone-200 bg-stone-50 p-2 shadow-sm">
                <img src="/frontend/img/logotipo2_quedadapps.png" alt="QuedadApps" class="h-14 w-auto rounded-xl object-contain">
            </span>
        </a>

        <nav class="hidden items-center gap-6 text-sm font-medium text-stone-600 md:flex">
            <a href="{{ route('buscar') }}" class="transition hover:text-stone-900">Buscar Partidas</a>
            <a href="{{ route('partidas.create') }}" class="transition hover:text-stone-900">Crear Partidas</a>
            <a href="{{ route('calendario') }}" class="transition hover:text-stone-900">Calendario</a>
            <a href="{{ route('asistencia') }}" class="transition hover:text-stone-900">Asistencia</a>
            <a href="{{ route('descargar') }}" class="transition hover:text-stone-900">Descargar App</a>
        </nav>

        <div class="flex items-center gap-3">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.index') }}" class="hidden rounded-full border border-stone-300 bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-900 transition hover:bg-stone-200 md:inline-flex">
                        Panel admin
                    </a>
                @endif

                <a href="{{ route('perfil') }}" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold transition hover:bg-stone-100">
                    Perfil
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="rounded-full bg-stone-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-stone-700">
                        Salir
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold transition hover:bg-stone-100">
                    Login
                </a>
                <a href="{{ route('registro') }}" class="rounded-full bg-stone-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-stone-700">
                    Registro
                </a>
            @endauth
        </div>
    </div>
</header>
