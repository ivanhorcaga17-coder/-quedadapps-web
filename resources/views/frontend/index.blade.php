<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quedadapps</title>

    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-white text-[#111]">

    @include('partials.header')

    <main>
        <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <div class="grid gap-8 overflow-hidden rounded-[36px] border border-stone-200 bg-[radial-gradient(circle_at_top_left,#ffffff_0%,#f5f5f4_45%,#e7e5e4_100%)] p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)] md:p-10 lg:grid-cols-[1.15fr_0.85fr] lg:p-14">
                <div class="flex flex-col justify-center">
                    <p class="text-sm uppercase tracking-[0.4em] text-stone-500">QuedadApps Web</p>
                    <h1 class="mt-5 max-w-3xl text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                        Organiza un partido cuando quieras y encuentra gente para jugar cerca de ti
                    </h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-stone-600 sm:text-lg">
                        Desde la web puedes crear partidas, consultar el calendario, apuntarte a planes deportivos y seguir cada detalle con una interfaz clara y adaptable a cualquier pantalla.
                    </p>

                    <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                        <a href="{{ route('buscar') }}" class="inline-flex justify-center rounded-full bg-black px-8 py-4 text-base font-semibold text-white transition hover:bg-stone-800">
                            Buscar partidas
                        </a>
                        <a href="{{ route('partidas.create') }}" class="inline-flex justify-center rounded-full border border-stone-300 bg-white px-8 py-4 text-base font-semibold text-stone-900 transition hover:bg-stone-100">
                            Crear una partida
                        </a>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <article class="rounded-[28px] border border-stone-300 bg-white/90 p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.3em] text-stone-400">Calendario</p>
                        <h2 class="mt-3 text-2xl font-semibold">Todo organizado</h2>
                        <p class="mt-3 text-sm leading-6 text-stone-600">
                            Revisa mes, semana o día y entra al detalle de cada partido con un clic.
                        </p>
                    </article>

                    <article class="rounded-[28px] border border-stone-300 bg-stone-950 p-5 text-white shadow-sm">
                        <p class="text-xs uppercase tracking-[0.3em] text-stone-400">Comunidad</p>
                        <h2 class="mt-3 text-2xl font-semibold">Más fácil quedar</h2>
                        <p class="mt-3 text-sm leading-6 text-stone-300">
                            Gestiona asistencia, chat y avisos antes de que empiece la actividad.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 pb-8 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3">
                <article class="rounded-[28px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.05)]">
                    <span class="text-sm uppercase tracking-[0.3em] text-stone-400">1</span>
                    <h2 class="mt-3 text-2xl font-semibold">Crea</h2>
                    <p class="mt-3 text-sm leading-6 text-stone-600">
                        Publica una partida con ubicación, hora, jugadores e imagen en pocos pasos.
                    </p>
                </article>

                <article class="rounded-[28px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.05)]">
                    <span class="text-sm uppercase tracking-[0.3em] text-stone-400">2</span>
                    <h2 class="mt-3 text-2xl font-semibold">Comparte</h2>
                    <p class="mt-3 text-sm leading-6 text-stone-600">
                        Invita a otros jugadores y mantén la información centralizada desde la ficha de la partida.
                    </p>
                </article>

                <article class="rounded-[28px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.05)]">
                    <span class="text-sm uppercase tracking-[0.3em] text-stone-400">3</span>
                    <h2 class="mt-3 text-2xl font-semibold">Juega</h2>
                    <p class="mt-3 text-sm leading-6 text-stone-600">
                        Recibe recordatorios por correo antes de empezar y llega con todo claro.
                    </p>
                </article>
            </div>
        </section>
    </main>

    @include('partials.footer-banner')

</body>
</html>
