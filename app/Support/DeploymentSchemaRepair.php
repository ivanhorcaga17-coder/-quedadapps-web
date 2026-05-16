<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeploymentSchemaRepair
{
    public function repair(): array
    {
        $steps = [];

        $this->ensureUsuariosTable();
        $steps[] = 'usuarios ok';

        $this->ensureReviewsTable();
        $steps[] = 'reviews ok';

        $this->ensurePartidasTable();
        $steps[] = 'partidas ok';

        $this->ensureAsistenciasTable();
        $steps[] = 'asistencias ok';

        return $steps;
    }

    protected function ensureUsuariosTable(): void
    {
        if (! Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table): void {
                $table->id();
                $table->string('nombre');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('user');
                $table->string('foto')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });

            return;
        }

        Schema::table('usuarios', function (Blueprint $table): void {
            if (! Schema::hasColumn('usuarios', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }

            if (! Schema::hasColumn('usuarios', 'role')) {
                $table->string('role')->default('user')->after('password');
            }

            if (! Schema::hasColumn('usuarios', 'foto')) {
                $table->string('foto')->nullable()->after('role');
            }

            if (! Schema::hasColumn('usuarios', 'remember_token')) {
                $table->rememberToken();
            }
        });
    }

    protected function ensureReviewsTable(): void
    {
        if (! Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->integer('rating');
                $table->text('comment')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('reviews', function (Blueprint $table): void {
                if (! Schema::hasColumn('reviews', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                }

                if (! Schema::hasColumn('reviews', 'rating')) {
                    $table->integer('rating');
                }

                if (! Schema::hasColumn('reviews', 'comment')) {
                    $table->text('comment')->nullable();
                }

                if (! Schema::hasColumn('reviews', 'ip_address')) {
                    $table->string('ip_address')->nullable();
                }
            });
        }

        try {
            DB::statement('ALTER TABLE reviews DROP FOREIGN KEY reviews_user_id_foreign');
        } catch (\Throwable $exception) {
        }

        try {
            Schema::table('reviews', function (Blueprint $table): void {
                $table->foreign('user_id')->references('id')->on('usuarios')->nullOnDelete();
            });
        } catch (\Throwable $exception) {
        }
    }

    protected function ensurePartidasTable(): void
    {
        if (! Schema::hasTable('partidas')) {
            Schema::create('partidas', function (Blueprint $table): void {
                $table->id();
                $table->string('titulo');
                $table->string('deporte')->nullable();
                $table->dateTime('fecha');
                $table->string('lugar')->nullable();
                $table->integer('max_jugadores')->default(1);
                $table->string('imagen')->nullable();
                $table->unsignedBigInteger('creador_id');
                $table->timestamps();
            });
        } else {
            Schema::table('partidas', function (Blueprint $table): void {
                if (Schema::hasColumn('partidas', 'descripcion')) {
                    $table->dropColumn('descripcion');
                }

                if (! Schema::hasColumn('partidas', 'deporte')) {
                    $table->string('deporte')->nullable()->after('titulo');
                }

                if (! Schema::hasColumn('partidas', 'lugar')) {
                    $table->string('lugar')->nullable()->after('fecha');
                }

                if (! Schema::hasColumn('partidas', 'max_jugadores')) {
                    $table->integer('max_jugadores')->default(1)->after('lugar');
                }

                if (! Schema::hasColumn('partidas', 'imagen')) {
                    $table->string('imagen')->nullable()->after('max_jugadores');
                }
            });
        }

        try {
            DB::statement('ALTER TABLE partidas DROP FOREIGN KEY partidas_creador_id_foreign');
        } catch (\Throwable $exception) {
        }

        try {
            Schema::table('partidas', function (Blueprint $table): void {
                $table->foreign('creador_id')->references('id')->on('usuarios')->cascadeOnDelete();
            });
        } catch (\Throwable $exception) {
        }
    }

    protected function ensureAsistenciasTable(): void
    {
        if (! Schema::hasTable('asistencias') && Schema::hasTable('asistencia')) {
            Schema::rename('asistencia', 'asistencias');
        }

        if (! Schema::hasTable('asistencias')) {
            Schema::create('asistencias', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('usuario_id');
                $table->unsignedBigInteger('partida_id');
                $table->string('estado')->nullable();
                $table->timestamp('recordatorio_partida_enviado_at')->nullable();
                $table->timestamp('recordatorio_inicio_enviado_at')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('asistencias', function (Blueprint $table): void {
                if (! Schema::hasColumn('asistencias', 'estado')) {
                    $table->string('estado')->nullable()->after('partida_id');
                }

                if (! Schema::hasColumn('asistencias', 'recordatorio_partida_enviado_at')) {
                    $table->timestamp('recordatorio_partida_enviado_at')->nullable()->after('estado');
                }

                if (! Schema::hasColumn('asistencias', 'recordatorio_inicio_enviado_at')) {
                    $table->timestamp('recordatorio_inicio_enviado_at')->nullable()->after('recordatorio_partida_enviado_at');
                }
            });
        }

        try {
            DB::statement('ALTER TABLE asistencias DROP FOREIGN KEY asistencia_usuario_id_foreign');
        } catch (\Throwable $exception) {
        }

        try {
            DB::statement('ALTER TABLE asistencias DROP FOREIGN KEY asistencias_usuario_id_foreign');
        } catch (\Throwable $exception) {
        }

        try {
            DB::statement('ALTER TABLE asistencias DROP FOREIGN KEY asistencia_partida_id_foreign');
        } catch (\Throwable $exception) {
        }

        try {
            DB::statement('ALTER TABLE asistencias DROP FOREIGN KEY asistencias_partida_id_foreign');
        } catch (\Throwable $exception) {
        }

        try {
            Schema::table('asistencias', function (Blueprint $table): void {
                $table->foreign('usuario_id')->references('id')->on('usuarios')->cascadeOnDelete();
            });
        } catch (\Throwable $exception) {
        }

        try {
            Schema::table('asistencias', function (Blueprint $table): void {
                $table->foreign('partida_id')->references('id')->on('partidas')->cascadeOnDelete();
            });
        } catch (\Throwable $exception) {
        }

        try {
            Schema::table('asistencias', function (Blueprint $table): void {
                $table->unique(['usuario_id', 'partida_id'], 'asistencias_usuario_partida_unique');
            });
        } catch (\Throwable $exception) {
        }
    }
}
