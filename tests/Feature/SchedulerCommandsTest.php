<?php

namespace Tests\Feature;

use Tests\TestCase;

class SchedulerCommandsTest extends TestCase
{
    public function test_schedule_list_includes_match_commands(): void
    {
        $this->artisan('schedule:list')
            ->expectsOutputToContain('partidas:avisar-proximas')
            ->expectsOutputToContain('partidas:eliminar-pasadas')
            ->assertSuccessful();
    }
}
