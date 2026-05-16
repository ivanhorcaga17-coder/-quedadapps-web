<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_load_successfully_without_seed_data(): void
    {
        $this->get(route('index'))->assertOk();
        $this->get(route('acerca'))->assertOk();
        $this->get(route('buscar'))->assertOk();
        $this->get(route('calendario'))->assertOk();
        $this->get(route('descargar'))->assertOk();
        $this->get(route('asistencia'))->assertOk();
    }
}
