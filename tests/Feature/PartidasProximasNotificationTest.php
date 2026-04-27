<?php

namespace Tests\Feature;

use App\Mail\PartidaProxima;
use App\Models\Asistencia;
use App\Models\Partida;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PartidasProximasNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_sends_upcoming_match_notification_once(): void
    {
        Mail::fake();

        config()->set('quedadapps.upcoming_match_notification_minutes', 30);

        $creator = User::factory()->create();
        $player = User::factory()->create([
            'email' => 'jugador@quedadapps.test',
        ]);

        $partida = Partida::create([
            'titulo' => 'Futbol en Madrid',
            'deporte' => 'Fútbol',
            'fecha' => now()->addMinutes(30)->startOfMinute(),
            'lugar' => 'Madrid',
            'max_jugadores' => 10,
            'imagen' => 'images/defaults/futbol/default.svg',
            'creador_id' => $creator->id,
        ]);

        $asistencia = Asistencia::create([
            'usuario_id' => $player->id,
            'partida_id' => $partida->id,
            'estado' => 'confirmado',
        ]);

        $this->artisan('partidas:avisar-proximas')
            ->assertSuccessful();

        Mail::assertSent(PartidaProxima::class, function (PartidaProxima $mail) use ($player, $partida) {
            return $mail->usuario->is($player) && $mail->partida->is($partida);
        });

        $this->assertNotNull($asistencia->fresh()->recordatorio_partida_enviado_at);

        Mail::fake();

        $this->artisan('partidas:avisar-proximas')
            ->assertSuccessful();

        Mail::assertNothingSent();
    }
}
