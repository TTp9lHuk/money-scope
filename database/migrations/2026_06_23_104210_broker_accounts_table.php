<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broker_accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('broker_connection_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('account_id');

            $table->string('type');
            $table->string('name');
            $table->string('status');

            $table->timestamp('opened_date')->nullable();
            $table->timestamp('closed_date')->nullable();

            $table->string('access_level')->nullable();

            $table->jsonb('raw_payload')->nullable();

            $table->timestamps();

            $table->unique(['broker_connection_id', 'account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broker_accounts');
    }
};
