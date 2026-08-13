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
        Schema::table('portfolios', function (Blueprint $table) {
            $table->timestamp('last_synced_at')->useCurrent();
            $table->enum('sync_status', ['idle', 'syncing', 'success', 'error'])->default('idle');
            $table->string('sync_error_message')->nullable();
            $table->boolean('autosync_enabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['last_synced_at', 'sync_status', 'sync_error_message','autosync_enabled']);
        });
    }
};
