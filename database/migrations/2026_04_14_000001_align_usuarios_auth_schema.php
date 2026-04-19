<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                if (! Schema::hasColumn('usuarios', 'role')) {
                    $table->string('role')->default('user')->after('password');
                }

                if (! Schema::hasColumn('usuarios', 'foto')) {
                    $table->string('foto')->nullable()->after('role');
                }

                if (! Schema::hasColumn('usuarios', 'remember_token')) {
                    $table->rememberToken();
                }

                if (! Schema::hasColumn('usuarios', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable()->after('email');
                }
            });
        }

        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (! Schema::hasColumn('reviews', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                }
            });

            try {
                DB::statement('ALTER TABLE reviews DROP FOREIGN KEY reviews_user_id_foreign');
            } catch (\Throwable $exception) {
            }

            Schema::table('reviews', function (Blueprint $table) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('usuarios')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('reviews') && Schema::hasColumn('reviews', 'user_id')) {
            try {
                Schema::table('reviews', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Throwable $exception) {
            }
        }
    }
};
