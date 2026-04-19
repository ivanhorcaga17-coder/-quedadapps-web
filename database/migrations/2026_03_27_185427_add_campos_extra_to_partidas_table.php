<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('partidas', function (Blueprint $table) {

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

    public function down()
    {
        Schema::table('partidas', function (Blueprint $table) {

            if (Schema::hasColumn('partidas', 'deporte')) {
                $table->dropColumn('deporte');
            }

            if (Schema::hasColumn('partidas', 'lugar')) {
                $table->dropColumn('lugar');
            }

            if (Schema::hasColumn('partidas', 'max_jugadores')) {
                $table->dropColumn('max_jugadores');
            }

            if (Schema::hasColumn('partidas', 'imagen')) {
                $table->dropColumn('imagen');
            }
        });
    }
};
