<section class="mt-16 border-t border-stone-200 bg-stone-950 text-white">
    <div class="mx-auto flex max-w-6xl flex-col gap-8 px-4 py-10 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm uppercase tracking-[0.35em] text-stone-400">QuedadApps Web</p>
            <h2 class="mt-3 text-3xl font-semibold">Descarga la app y deja tu valoración</h2>
            <p class="mt-3 text-sm leading-6 text-stone-300 sm:text-base">
                Desde aquí puedes entrar a la página de descarga, instalar la app y valorar tu experiencia.
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('descargar') }}" target="_blank" rel="noopener noreferrer" class="rounded-full bg-white px-6 py-3 text-center font-semibold text-stone-950 transition hover:bg-stone-200">
                Descargar la app
            </a>
            <a href="{{ route('descargar') }}#reviews" target="_blank" rel="noopener noreferrer" class="rounded-full border border-white/20 px-6 py-3 text-center font-semibold text-white transition hover:bg-white/10">
                Ver valoraciones
            </a>
            <a href="{{ route('acerca') }}" class="rounded-full border border-white/20 px-6 py-3 text-center font-semibold text-white transition hover:bg-white/10">
                Acerca de
            </a>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-6xl flex-col gap-3 px-4 py-5 text-sm text-stone-400 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <p>&copy; {{ $currentYear }} QuedadApps. Todos los derechos reservados.</p>
            <p>QuedadApps Web · Plataforma de quedadas deportivas</p>
        </div>
    </div>
</section>
