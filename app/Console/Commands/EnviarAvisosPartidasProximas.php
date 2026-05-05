<?php

namespace App\Console\Commands;

use App\Mail\PartidaIniciada;
use App\Mail\PartidaProxima;
use App\Models\Asistencia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarAvisosPartidasProximas extends Command
{
    protected $signature = 'partidas:avisar-proximas';

    protected $description = 'Envia avisos por correo a los usuarios apuntados en partidas proximas.';

    public function handle(): int
    {
        $minutesBefore = max(1, (int) config('quedadapps.upcoming_match_notification_minutes', 30));
        $startGraceMinutes = max(1, (int) config('quedadapps.match_start_notification_grace_minutes', 5));
        $now = now();

        $preMatchAsistencias = Asistencia::query()
            ->with(['usuario', 'partida'])
            ->whereNull('recordatorio_partida_enviado_at')
            ->whereHas('partida', function ($query) use ($now, $minutesBefore) {
                $query
                    ->where('fecha', '>', $now)
                    ->where('fecha', '<=', $now->copy()->addMinutes($minutesBefore));
            })
            ->get();

        $startedAsistencias = Asistencia::query()
            ->with(['usuario', 'partida'])
            ->whereNull('recordatorio_inicio_enviado_at')
            ->whereHas('partida', function ($query) use ($now, $startGraceMinutes) {
                $query
                    ->where('fecha', '<=', $now)
                    ->where('fecha', '>=', $now->copy()->subMinutes($startGraceMinutes));
            })
            ->get();

        $preMatchSent = 0;
        $startedSent = 0;

        foreach ($preMatchAsistencias as $asistencia) {
            if (! $asistencia->usuario?->email || ! $asistencia->partida) {
                continue;
            }

            $remainingMinutes = max(0, $now->diffInMinutes($asistencia->partida->fecha));

            Mail::to($asistencia->usuario->email)->send(
                new PartidaProxima($asistencia->partida, $asistencia->usuario, $remainingMinutes)
            );

            $asistencia->forceFill([
                'recordatorio_partida_enviado_at' => now(),
            ])->save();

            $preMatchSent++;
        }

        foreach ($startedAsistencias as $asistencia) {
            if (! $asistencia->usuario?->email || ! $asistencia->partida) {
                continue;
            }

            Mail::to($asistencia->usuario->email)->send(
                new PartidaIniciada($asistencia->partida, $asistencia->usuario)
            );

            $asistencia->forceFill([
                'recordatorio_inicio_enviado_at' => now(),
            ])->save();

            $startedSent++;
        }

        $this->info("Avisos previos enviados: {$preMatchSent}");
        $this->info("Avisos de inicio enviados: {$startedSent}");

        return self::SUCCESS;
    }
}
