<?php

namespace Tests\Feature;

use App\Support\DeploymentSchemaRepair;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DeploymentSchemaRepairTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_repairs_the_minimum_runtime_schema(): void
    {
        app(DeploymentSchemaRepair::class)->repair();

        $this->assertTrue(Schema::hasColumn('usuarios', 'role'));
        $this->assertTrue(Schema::hasColumn('usuarios', 'foto'));
        $this->assertTrue(Schema::hasColumn('usuarios', 'remember_token'));
        $this->assertTrue(Schema::hasColumn('reviews', 'user_id'));
        $this->assertTrue(Schema::hasColumn('partidas', 'max_jugadores'));
        $this->assertTrue(Schema::hasColumn('asistencias', 'estado'));
    }
}
