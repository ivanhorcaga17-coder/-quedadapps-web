<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndAdminFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_registered_user_becomes_admin(): void
    {
        $response = $this->post(route('registro.post'), [
            'name' => 'Admin Inicial',
            'email' => 'admin@quedadapps.test',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $response->assertRedirect(route('index'));

        $this->assertDatabaseHas('usuarios', [
            'email' => 'admin@quedadapps.test',
            'role' => 'admin',
        ]);
    }

    public function test_crear_partidas_requires_authentication(): void
    {
        $this->get(route('partidas.create'))
            ->assertRedirect(route('login'));

        $this->post(route('partidas.store'), [
            'deporte' => 'Fútbol',
            'fecha' => now()->addDay()->format('Y-m-d H:i:s'),
            'lugar' => 'Madrid',
            'max_jugadores' => 10,
        ])->assertRedirect(route('login'));
    }

    public function test_admin_panel_requires_admin_role(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($user)
            ->get(route('admin.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.index'))
            ->assertOk();
    }
}
