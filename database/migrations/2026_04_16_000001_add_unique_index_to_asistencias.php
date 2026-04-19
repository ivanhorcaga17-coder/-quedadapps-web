<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('asistencias')
            ->select('usuario_id', 'partida_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('usuario_id', 'partida_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->each(function ($duplicate) {
                DB::table('asistencias')
                    ->where('usuario_id', $duplicate->usuario_id)
                    ->where('partida_id', $duplicate->partida_id)
                    ->where('id', '!=', $duplicate->keep_id)
                    ->delete();
            });

        Schema::table('asistencias', function (Blueprint $table) {
            $table->unique(['usuario_id', 'partida_id'], 'asistencias_usuario_partida_unique');
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropUnique('asistencias_usuario_partida_unique');
        });
    }
};
