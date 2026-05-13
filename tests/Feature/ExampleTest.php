<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertStatus(200)
            ->assertSee('Quedadapps')
            ->assertSee('Buscar partidas');
    }

    public function test_the_homepage_uses_built_assets_instead_of_the_vite_dev_server(): void
    {
        $response = $this->get('/');

        $response
            ->assertStatus(200)
            ->assertSee('/build/assets/', false)
            ->assertDontSee('http://127.0.0.1:5173', false);
    }
}
