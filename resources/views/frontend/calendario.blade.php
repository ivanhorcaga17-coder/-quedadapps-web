<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario - QuedadApps</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    <style>
        .fc {
            --fc-border-color: #e7e5e4;
            --fc-page-bg-color: #ffffff;
            --fc-neutral-bg-color: #fafaf9;
            --fc-list-event-hover-bg-color: #f5f5f4;
            --fc-event-bg-color: #111827;
            --fc-event-border-color: #111827;
            --fc-event-text-color: #ffffff;
            --fc-now-indicator-color: #dc2626;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1c1917;
        }

        .fc .fc-button {
            border-radius: 999px;
            border: 1px solid #d6d3d1;
            background: #ffffff;
            color: #1c1917;
            box-shadow: none;
            padding: 0.55rem 0.9rem;
        }

        .fc .fc-button:hover,
        .fc .fc-button:focus,
        .fc .fc-button.fc-button-active {
            background: #1c1917;
            border-color: #1c1917;
            color: #ffffff;
        }

        .fc .fc-timegrid-slot {
            height: 3.5rem;
        }

        .fc .fc-timegrid-axis-cushion,
        .fc .fc-timegrid-slot-label-cushion,
        .fc .fc-col-header-cell-cushion {
            color: #44403c;
            font-size: 0.85rem;
        }

        .fc .fc-timegrid-event {
            border-radius: 16px;
            padding: 0.2rem;
        }

        .fc .fc-event-time,
        .fc .fc-event-title {
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>
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
            <div class="mb-5 grid gap-3 rounded-[28px] border border-stone-200 bg-stone-50 p-4 text-sm text-stone-600 sm:grid-cols-3">
                <div class="rounded-2xl border border-stone-200 bg-white px-4 py-3">
                    <span class="font-semibold text-stone-900">Vista mensual</span>
                    <p class="mt-1">Resumen general de todas las partidas programadas.</p>
                </div>
                <div class="rounded-2xl border border-stone-200 bg-white px-4 py-3">
                    <span class="font-semibold text-stone-900">Vista semanal</span>
                    <p class="mt-1">Ideal para localizar la actividad exacta de los próximos días.</p>
                </div>
                <div class="rounded-2xl border border-stone-200 bg-white px-4 py-3">
                    <span class="font-semibold text-stone-900">Vista diaria</span>
                    <p class="mt-1">Horario completo de 00:00 a 23:59 para ubicar cada partida en su franja real.</p>
                </div>
            </div>
            <div id="calendar" class="min-h-[720px]"></div>
        </section>
    </main>

    @include('partials.footer-banner')

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            let calendar;

            const buildHeaderToolbar = () => window.innerWidth < 768
                ? {
                    left: 'today',
                    center: 'title',
                    right: 'prevCustom,nextCustom',
                }
                : {
                    left: 'prevCustom,nextCustom today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay',
                };

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                timeZone: 'Europe/Madrid',
                height: 'auto',
                expandRows: true,
                stickyHeaderDates: true,
                nowIndicator: true,
                slotMinTime: '00:00:00',
                slotMaxTime: '24:00:00',
                slotDuration: '01:00:00',
                snapDuration: '00:15:00',
                defaultTimedEventDuration: '01:00:00',
                allDaySlot: false,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                },
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                },
                dayHeaderFormat: {
                    weekday: 'short',
                    day: '2-digit',
                    month: '2-digit',
                },
                buttonText: {
                    today: 'hoy',
                    month: 'mes',
                    week: 'semana',
                    day: 'día',
                },
                customButtons: {
                    prevCustom: {
                        text: 'anterior',
                        click() {
                            calendar.prev();
                        },
                    },
                    nextCustom: {
                        text: 'siguiente',
                        click() {
                            calendar.next();
                        },
                    },
                },
                headerToolbar: buildHeaderToolbar(),
                events: @json($calendarEvents),
                views: {
                    timeGridDay: {
                        dayMaxEventRows: false,
                    },
                    timeGridWeek: {
                        dayMaxEventRows: false,
                    },
                },
                eventClick(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                },
            });

            calendar.render();

            window.addEventListener('resize', function () {
                calendar.setOption('headerToolbar', buildHeaderToolbar());
            });
        });
    </script>
</body>
</html>
