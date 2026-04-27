<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            if (! Schema::hasColumn('asistencias', 'recordatorio_partida_enviado_at')) {
                $table->timestamp('recordatorio_partida_enviado_at')->nullable()->after('estado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            if (Schema::hasColumn('asistencias', 'recordatorio_partida_enviado_at')) {
                $table->dropColumn('recordatorio_partida_enviado_at');
            }
        });
    }
};
