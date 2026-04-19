<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
 {
    Schema::table('partidas', function (Blueprint $table) {

        // Eliminar columna antigua que ya no usas
        if (Schema::hasColumn('partidas', 'descripcion')) {
            $table->dropColumn('descripcion');
        }

        // Añadir columnas si no existen
        if (!Schema::hasColumn('partidas', 'deporte')) {
            $table->string('deporte')->after('titulo');
        }

        if (!Schema::hasColumn('partidas', 'lugar')) {
            $table->string('lugar')->after('fecha');
        }

        if (!Schema::hasColumn('partidas', 'max_jugadores')) {
            $table->integer('max_jugadores')->after('lugar');
        }

        if (!Schema::hasColumn('partidas', 'imagen')) {
            $table->string('imagen')->nullable()->after('max_jugadores');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('partidas', function (Blueprint $table) {
        $table->dropColumn(['deporte', 'lugar', 'max_jugadores', 'imagen']);
        $table->text('descripcion')->nullable();
    });
}
};
