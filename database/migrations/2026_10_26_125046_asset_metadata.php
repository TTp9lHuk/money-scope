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
        Schema::create('asset_metadata', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asset_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('brand_name')->nullable();

            $table->string('logo_name')
                ->nullable();

            $table->string('logo_base_color')
                ->nullable();

            $table->string('logo_text_color')
                ->nullable();

            $table->string('sector')
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->jsonb('raw_payload')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_metadata');
    }
};
