<?php

namespace Tests\Feature;

use App\Mail\PartidaEliminada;
use App\Models\Asistencia;
use App\Models\ChatMessage;
use App\Models\Partida;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EliminarPartidasPasadasCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_deletes_expired_matches_and_notifies_participants(): void
    {
        Mail::fake();

        $creator = User::factory()->create();
        $playerOne = User::factory()->create([
            'email' => 'jugador1@quedadapps.test',
        ]);
        $playerTwo = User::factory()->create([
            'email' => 'jugador2@quedadapps.test',
        ]);

        $expiredPartida = Partida::create([
            'titulo' => 'Padel en Madrid',
            'deporte' => 'Pádel',
            'fecha' => now()->subHour(),
            'lugar' => 'Madrid',
            'max_jugadores' => 4,
            'imagen' => 'images/defaults/padel/default.svg',
            'creador_id' => $creator->id,
        ]);

        $futurePartida = Partida::create([
            'titulo' => 'Tenis en Madrid',
            'deporte' => 'Tenis',
            'fecha' => now()->addHour(),
            'lugar' => 'Madrid',
            'max_jugadores' => 2,
            'imagen' => 'images/defaults/generico/default.svg',
            'creador_id' => $creator->id,
        ]);

        Asistencia::create([
            'usuario_id' => $creator->id,
            'partida_id' => $expiredPartida->id,
            'estado' => 'confirmado',
        ]);

        Asistencia::create([
            'usuario_id' => $playerOne->id,
            'partida_id' => $expiredPartida->id,
            'estado' => 'confirmado',
        ]);

        Asistencia::create([
            'usuario_id' => $playerTwo->id,
            'partida_id' => $expiredPartida->id,
            'estado' => 'confirmado',
        ]);

        ChatMessage::create([
            'partida_id' => $expiredPartida->id,
            'usuario_id' => $playerOne->id,
            'mensaje' => 'Nos vemos en la pista',
        ]);

        $this->artisan('partidas:eliminar-pasadas')
            ->assertSuccessful();

        Mail::assertSent(PartidaEliminada::class, 3);
        Mail::assertSent(PartidaEliminada::class, function (PartidaEliminada $mail) use ($playerOne, $expiredPartida) {
            return $mail->usuario->is($playerOne)
                && $mail->partida->deporte === $expiredPartida->deporte
                && $mail->motivo === 'Partida pasada';
        });

        $this->assertDatabaseMissing('partidas', [
            'id' => $expiredPartida->id,
        ]);
        $this->assertDatabaseHas('partidas', [
            'id' => $futurePartida->id,
        ]);
        $this->assertDatabaseMissing('asistencias', [
            'partida_id' => $expiredPartida->id,
        ]);
        $this->assertDatabaseMissing('chat_messages', [
            'partida_id' => $expiredPartida->id,
        ]);
    }
}
