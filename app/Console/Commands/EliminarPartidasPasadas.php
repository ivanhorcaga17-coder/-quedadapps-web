<?php

namespace App\Console\Commands;

use App\Mail\PartidaEliminada;
use App\Models\Partida;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EliminarPartidasPasadas extends Command
{
    protected $signature = 'partidas:eliminar-pasadas';

    protected $description = 'Elimina automaticamente las partidas cuya fecha ya ha pasado y avisa a los participantes.';

    public function handle(): int
    {
        $partidas = Partida::query()
            ->with(['asistencias.usuario'])
            ->where('fecha', '<', now())
            ->get();

        $deleted = 0;
        $notified = 0;

        foreach ($partidas as $partida) {
            $usuarios = $partida->asistencias
                ->pluck('usuario')
                ->filter(fn ($usuario) => filled($usuario?->email))
                ->unique('id')
                ->values();

            foreach ($usuarios as $usuario) {
                Mail::to($usuario->email)->send(
                    new PartidaEliminada($partida, $usuario)
                );

                $notified++;
            }

            $partida->delete();
            $deleted++;
        }

        $this->info("Partidas eliminadas: {$deleted}");
        $this->info("Correos enviados: {$notified}");

        return self::SUCCESS;
    }
}
