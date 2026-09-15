<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // ID брокерского счета во внешней системе
            $table->string('account_id');

            $table->string('name');
            $table->string('currency', 12)->default('RUB');

            $table->timestamp('last_synced_at')->nullable();

            $table->enum('sync_status', [
                'idle',
                'syncing',
                'success',
                'error',
            ])->default('idle');

            $table->text('sync_error_message')->nullable();
            $table->boolean('autosync_enabled')->default(false);

            $table->timestamps();

            // Пользователь не может дважды добавить один счет
            $table->unique(['user_id', 'account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
