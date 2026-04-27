@php
    $isBuscar = request()->routeIs('buscar', 'partidas.showPage', 'partidas.confirmar');
    $isCrear = request()->routeIs('partidas.create', 'partidas.preview');
    $isCalendario = request()->routeIs('calendario');
    $isAsistencia = request()->routeIs('asistencia');
    $isDescargar = request()->routeIs('descargar');
    $isAdmin = request()->routeIs('admin.*');

    $desktopNavClass = function (bool $active): string {
        return $active
            ? 'rounded-full bg-stone-900 px-4 py-2 font-semibold text-white shadow-sm'
            : 'rounded-full px-4 py-2 text-stone-600 transition hover:bg-stone-100 hover:text-stone-900';
    };

    $mobileNavClass = function (bool $active): string {
        return $active
            ? 'rounded-2xl bg-stone-900 px-4 py-3 font-semibold text-white'
            : 'rounded-2xl border border-stone-300 px-4 py-3 text-stone-700 transition hover:bg-stone-100';
    };
@endphp

<header class="border-b border-stone-200 bg-white" data-header>
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('index') }}" class="flex shrink-0 items-center gap-3">
            <span class="inline-flex rounded-2xl border border-stone-200 bg-stone-50 p-2 shadow-sm">
                <img src="/frontend/img/logotipo2_quedadapps.png" alt="QuedadApps" class="h-12 w-auto rounded-xl object-contain sm:h-14">
            </span>
        </a>

        <nav class="hidden items-center gap-2 text-sm font-medium md:flex">
            <a href="{{ route('buscar') }}" class="{{ $desktopNavClass($isBuscar) }}">Buscar Partidas</a>
            <a href="{{ route('partidas.create') }}" class="{{ $desktopNavClass($isCrear) }}">Crear Partidas</a>
            <a href="{{ route('calendario') }}" class="{{ $desktopNavClass($isCalendario) }}">Calendario</a>
            <a href="{{ route('asistencia') }}" class="{{ $desktopNavClass($isAsistencia) }}">Asistencia</a>
            <a href="{{ route('descargar') }}" target="_blank" rel="noopener noreferrer" class="{{ $desktopNavClass($isDescargar) }}">Descargar App</a>
        </nav>

        <div class="flex items-center gap-2 sm:gap-3">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.index') }}" class="hidden rounded-full border px-4 py-2 text-sm font-semibold transition md:inline-flex {{ $isAdmin ? 'border-stone-900 bg-stone-900 text-white' : 'border-stone-300 bg-stone-100 text-stone-900 hover:bg-stone-200' }}">
                        Panel admin
                    </a>
                @endif

                <a href="{{ route('perfil') }}" class="inline-flex h-11 w-11 items-center justify-center overflow-hidden rounded-full border border-stone-300 bg-stone-100 transition hover:bg-stone-200">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                </a>

                <form action="{{ route('logout') }}" method="POST" class="hidden sm:block">
                    @csrf
                    <button class="rounded-full bg-stone-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-stone-700">
                        Salir
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hidden rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold transition hover:bg-stone-100 sm:inline-flex">
                    Login
                </a>
                <a href="{{ route('registro') }}" class="hidden rounded-full bg-stone-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-stone-700 sm:inline-flex">
                    Registro
                </a>
            @endauth

            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-stone-300 bg-stone-50 transition hover:bg-stone-100 md:hidden"
                aria-expanded="false"
                aria-label="Abrir menú"
                data-menu-toggle
            >
                <span class="relative h-5 w-5">
                    <span class="absolute left-0 top-0.5 h-0.5 w-5 rounded-full bg-stone-900 transition-all duration-300" data-line="top"></span>
                    <span class="absolute left-0 top-2.5 h-0.5 w-5 rounded-full bg-stone-900 transition-all duration-300" data-line="middle"></span>
                    <span class="absolute left-0 top-4.5 h-0.5 w-5 rounded-full bg-stone-900 transition-all duration-300" data-line="bottom"></span>
                </span>
            </button>
        </div>
    </div>

    <div class="hidden border-t border-stone-200 md:hidden" data-mobile-menu>
        <nav class="mx-auto flex max-w-6xl flex-col gap-3 px-4 py-4 sm:px-6">
            <a href="{{ route('buscar') }}" class="{{ $mobileNavClass($isBuscar) }}">Buscar Partidas</a>
            <a href="{{ route('partidas.create') }}" class="{{ $mobileNavClass($isCrear) }}">Crear Partidas</a>
            <a href="{{ route('calendario') }}" class="{{ $mobileNavClass($isCalendario) }}">Calendario</a>
            <a href="{{ route('asistencia') }}" class="{{ $mobileNavClass($isAsistencia) }}">Asistencia</a>
            <a href="{{ route('descargar') }}" target="_blank" rel="noopener noreferrer" class="{{ $mobileNavClass($isDescargar) }}">Descargar App</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.index') }}" class="{{ $mobileNavClass($isAdmin) }}">Panel admin</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="{{ $mobileNavClass(request()->routeIs('login')) }}">Login</a>
                <a href="{{ route('registro') }}" class="rounded-2xl bg-stone-900 px-4 py-3 font-semibold text-white transition hover:bg-stone-700">Registro</a>
            @endauth
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.querySelector('[data-header]');
        const toggle = header?.querySelector('[data-menu-toggle]');
        const menu = header?.querySelector('[data-mobile-menu]');
        const topLine = header?.querySelector('[data-line="top"]');
        const middleLine = header?.querySelector('[data-line="middle"]');
        const bottomLine = header?.querySelector('[data-line="bottom"]');

        if (!header || !toggle || !menu || !topLine || !middleLine || !bottomLine) {
            return;
        }

        const setOpenState = (open) => {
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            menu.classList.toggle('hidden', !open);

            topLine.classList.toggle('translate-y-2', open);
            topLine.classList.toggle('rotate-45', open);

            middleLine.classList.toggle('opacity-0', open);

            bottomLine.classList.toggle('-translate-y-2', open);
            bottomLine.classList.toggle('-rotate-45', open);
        };

        setOpenState(false);

        toggle.addEventListener('click', function () {
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            setOpenState(!isOpen);
        });
    });
</script>
