<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->string('figi')->nullable()->unique();
            $table->string('instrument_uid')->nullable()->unique();

            $table->string('ticker')->index();
            $table->string('class_code')->nullable()->index();

            $table->string('name');
            $table->string('instrument_type');
            $table->string('currency', 12)->nullable();

            $table->string('isin')->nullable()->index();
            $table->unsignedInteger('lot')->nullable();

            $table->boolean('is_active')->default(true);

            $table->jsonb('raw_payload')->nullable();

            $table->timestamps();

            $table->index(['ticker', 'class_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
