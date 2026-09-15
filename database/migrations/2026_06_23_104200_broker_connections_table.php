<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broker_connections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('portfolio_id')
                ->unique()
                ->constrained('portfolios')
                ->cascadeOnDelete();

            $table->string('broker_type');

            $table->string('name')->nullable();

            // В модели используется encrypted cast
            $table->text('api_token');

            $table->timestamp('last_synced_at')->nullable();

            $table->enum('sync_status', [
                'idle',
                'syncing',
                'success',
                'error',
            ])->default('idle');

            $table->text('sync_error_message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broker_connections');
    }
};
