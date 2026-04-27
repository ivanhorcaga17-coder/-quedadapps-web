<?php

namespace Tests\Feature;

use App\Models\Partida;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PartidaImageFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_image_is_stored_temporarily_and_moved_on_finalize(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('partidas.store'), [
            'deporte' => 'Fútbol',
            'fecha' => now()->addDay()->format('Y-m-d H:i:s'),
            'lugar' => 'Madrid',
            'max_jugadores' => 10,
            'imagen' => UploadedFile::fake()->image('partida.png'),
        ]);

        $response->assertRedirect(route('partidas.preview'));

        $pendingPartida = session('pending_partida');

        $this->assertIsArray($pendingPartida);
        $this->assertStringStartsWith('temp/partidas/', $pendingPartida['imagen']);
        Storage::disk('public')->assertExists($pendingPartida['imagen']);

        $this->actingAs($user)->post(route('partidas.finalize'), [
            'jugadores' => '',
        ])->assertRedirect();

        $partida = Partida::first();

        $this->assertNotNull($partida);
        $this->assertStringStartsWith('partidas/', $partida->imagen);
        Storage::disk('public')->assertExists($partida->imagen);
    }

    public function test_default_sport_image_is_used_when_no_upload_is_provided(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('partidas.store'), [
            'deporte' => 'Fútbol',
            'fecha' => now()->addDay()->format('Y-m-d H:i:s'),
            'lugar' => 'Madrid',
            'max_jugadores' => 10,
        ])->assertRedirect(route('partidas.preview'));

        $pendingPartida = session('pending_partida');

        $this->assertIsArray($pendingPartida);
        $this->assertStringStartsWith('images/defaults/futbol/', $pendingPartida['imagen']);
    }
}
