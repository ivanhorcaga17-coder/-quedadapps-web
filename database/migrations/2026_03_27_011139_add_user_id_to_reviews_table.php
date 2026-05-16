<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('reviews')) {
            return;
        }

        Schema::table('reviews', function (Blueprint $table) {
            if (! Schema::hasColumn('reviews', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });

        try {
            DB::statement('ALTER TABLE reviews DROP FOREIGN KEY reviews_user_id_foreign');
        } catch (\Throwable $exception) {
        }

        try {
            Schema::table('reviews', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('usuarios')->nullOnDelete();
            });
        } catch (\Throwable $exception) {
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('reviews') || ! Schema::hasColumn('reviews', 'user_id')) {
            return;
        }

        Schema::table('reviews', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $exception) {
            }
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
