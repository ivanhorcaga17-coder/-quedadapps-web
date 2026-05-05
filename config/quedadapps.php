<?php

return [
    'upcoming_match_notification_minutes' => (int) env('PARTIDA_PROXIMA_MINUTOS', 30),
    'match_start_notification_grace_minutes' => (int) env('PARTIDA_INICIO_GRACIA_MINUTOS', 5),
];
