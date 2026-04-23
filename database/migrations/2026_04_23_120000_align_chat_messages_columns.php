<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('chat_messages')) {
            return;
        }

        Schema::table('chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chat_messages', 'user_id') && ! Schema::hasColumn('chat_messages', 'usuario_id')) {
                $table->renameColumn('user_id', 'usuario_id');
            }

            if (Schema::hasColumn('chat_messages', 'message') && ! Schema::hasColumn('chat_messages', 'mensaje')) {
                $table->renameColumn('message', 'mensaje');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('chat_messages')) {
            return;
        }

        Schema::table('chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chat_messages', 'usuario_id') && ! Schema::hasColumn('chat_messages', 'user_id')) {
                $table->renameColumn('usuario_id', 'user_id');
            }

            if (Schema::hasColumn('chat_messages', 'mensaje') && ! Schema::hasColumn('chat_messages', 'message')) {
                $table->renameColumn('mensaje', 'message');
            }
        });
    }
};
