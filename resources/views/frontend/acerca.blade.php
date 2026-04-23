<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acerca de - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    @include('partials.header')

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-[32px] border border-stone-200 bg-[linear-gradient(135deg,#111827_0%,#1f2937_55%,#44403c_100%)] px-6 py-10 text-white shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:px-8">
            <p class="text-sm uppercase tracking-[0.35em] text-stone-300">Acerca de</p>
            <h1 class="mt-4 text-3xl font-semibold sm:text-4xl">QuedadApps Web, deporte, comunidad y organización en un solo sitio</h1>
            <p class="mt-3 max-w-3xl text-sm text-stone-200 sm:text-base">
                QuedadApps Web está pensada para que cualquier persona pueda organizar una quedada deportiva, apuntarse a planes de otros usuarios y gestionar toda la experiencia desde una interfaz clara, rápida y visual.
            </p>
        </section>

        <section class="mt-8 grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <article class="rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-8">
                <p class="text-sm uppercase tracking-[0.25em] text-stone-400">El proyecto</p>
                <h2 class="mt-3 text-2xl font-semibold sm:text-3xl">Qué hace QuedadApps</h2>
                <div class="mt-6 space-y-4 text-sm leading-7 text-stone-600 sm:text-base">
                    <p>La plataforma permite crear partidas deportivas, verlas en calendario, buscarlas por filtros, unirte como participante, chatear con el grupo y usar mapas para ubicar y alcanzar el lugar del encuentro.</p>
                    <p>La idea es reducir al mínimo la fricción de organizar deporte con amigos o con gente nueva, haciendo que crear una quedada y coordinarla sea algo sencillo y natural.</p>
                    <p>Tambien incorpora recordatorios visuales de inicio proximo, confirmación previa antes de publicar una partida, chat con avisos por correo y herramientas para que cada usuario gestione bien su participación.</p>
                </div>
            </article>

            <article class="rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-8">
                <p class="text-sm uppercase tracking-[0.25em] text-stone-400">Autor</p>
                <h2 class="mt-3 text-2xl font-semibold sm:text-3xl">Ivan Horcajada Albacete</h2>
                <div class="mt-6 space-y-4 text-sm leading-7 text-stone-600 sm:text-base">
                    <p>Creador de QuedadApps Web y estudiante actual de 2º de DAW.</p>
                    <p>Este proyecto nace como una forma de unir desarrollo web, organización social y pasión por el deporte en una aplicación útil, moderna y pensada para personas reales.</p>
                    <p>La intención es seguir mejorándola poco a poco para que la experiencia sea cada vez más completa, intuitiva y visualmente sólida.</p>
                </div>
            </article>
        </section>

        <section class="mt-8 rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-8">
            <p class="text-sm uppercase tracking-[0.25em] text-stone-400">Cómo funciona</p>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <article class="rounded-3xl bg-stone-50 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-stone-400">1</p>
                    <h3 class="mt-3 text-xl font-semibold">Crea o encuentra una partida</h3>
                    <p class="mt-3 text-sm leading-6 text-stone-600">Publica una actividad con deporte, fecha, lugar e imagen o descubre partidas ya creadas por otros usuarios.</p>
                </article>
                <article class="rounded-3xl bg-stone-50 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-stone-400">2</p>
                    <h3 class="mt-3 text-xl font-semibold">Organiza a los participantes</h3>
                    <p class="mt-3 text-sm leading-6 text-stone-600">Apúntate, añade jugadores, consulta quién va a asistir y conversa por el chat específico de cada partida.</p>
                </article>
                <article class="rounded-3xl bg-stone-50 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-stone-400">3</p>
                    <h3 class="mt-3 text-xl font-semibold">Llega al sitio y juega</h3>
                    <p class="mt-3 text-sm leading-6 text-stone-600">Usa los mapas, revisa cómo llegar y sigue las notificaciones para no perderte ningún detalle importante.</p>
                </article>
            </div>
        </section>
    </main>

    @include('partials.footer-banner')
</body>
</html>
