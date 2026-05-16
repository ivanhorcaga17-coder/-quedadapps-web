<?php

namespace Tests\Feature;

use Tests\TestCase;

class MigrationSafetyTest extends TestCase
{
    public function test_legacy_schema_alignment_migrations_are_idempotent(): void
    {
        $roleMigration = file_get_contents(database_path('migrations/2026_03_26_232029_add_role_to_users_table.php'));
        $fotoMigration = file_get_contents(database_path('migrations/2026_03_27_003217_add_foto_to_users_table.php'));
        $reviewUserMigration = file_get_contents(database_path('migrations/2026_03_27_011139_add_user_id_to_reviews_table.php'));
        $asistenciaUniqueMigration = file_get_contents(database_path('migrations/2026_04_16_000001_add_unique_index_to_asistencias.php'));

        $this->assertStringContainsString("Schema::hasColumn('usuarios', 'role')", $roleMigration);
        $this->assertStringContainsString("Schema::hasColumn('usuarios', 'foto')", $fotoMigration);
        $this->assertStringContainsString("Schema::hasColumn('reviews', 'user_id')", $reviewUserMigration);
        $this->assertStringContainsString('DROP FOREIGN KEY reviews_user_id_foreign', $reviewUserMigration);
        $this->assertStringContainsString("Schema::hasTable('asistencias')", $asistenciaUniqueMigration);
        $this->assertStringContainsString('asistencias_usuario_partida_unique', $asistenciaUniqueMigration);
    }
}
