<?php

namespace App\Console\Commands;

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
        $windowStart = now()->addMinutes($minutesBefore)->startOfMinute();
        $windowEnd = $windowStart->copy()->endOfMinute();

        $asistencias = Asistencia::query()
            ->with(['usuario', 'partida'])
            ->whereNull('recordatorio_partida_enviado_at')
            ->whereHas('partida', function ($query) use ($windowStart, $windowEnd) {
                $query->whereBetween('fecha', [$windowStart, $windowEnd]);
            })
            ->get();

        $sent = 0;

        foreach ($asistencias as $asistencia) {
            if (! $asistencia->usuario?->email || ! $asistencia->partida) {
                continue;
            }

            Mail::to($asistencia->usuario->email)->send(
                new PartidaProxima($asistencia->partida, $asistencia->usuario, $minutesBefore)
            );

            $asistencia->forceFill([
                'recordatorio_partida_enviado_at' => now(),
            ])->save();

            $sent++;
        }

        $this->info("Avisos enviados: {$sent}");

        return self::SUCCESS;
    }
}
