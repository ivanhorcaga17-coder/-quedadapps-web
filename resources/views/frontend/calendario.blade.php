<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    @include('partials.header')

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-[32px] border border-stone-200 bg-white p-6 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-8">
            <p class="text-sm uppercase tracking-[0.35em] text-stone-500">Calendario</p>
            <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">Calendario real de partidas</h1>
            <p class="mt-3 max-w-2xl text-sm text-stone-600 sm:text-base">
                Consulta las partidas en vista mensual o semanal. Al hacer clic sobre un evento se abrirá su detalle.
            </p>
        </section>

        <section class="mt-8 overflow-hidden rounded-[32px] border border-stone-200 bg-white p-4 shadow-[0_24px_80px_rgba(28,25,23,0.08)] sm:p-6">
            <div id="calendar" class="min-h-[720px]"></div>
        </section>
    </main>

    @include('partials.footer-banner')

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek',
                },
                events: @json($calendarEvents),
                eventClick(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                },
            });

            calendar.render();
        });
    </script>
</body>
</html>
