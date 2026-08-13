<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_positions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('portfolio_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('asset_id')
                ->nullable()
                ->constrained('assets')
                ->nullOnDelete();

            $table->string('position_uid')->nullable()->index();
            $table->string('figi')->nullable()->index();
            $table->string('instrument_uid')->nullable()->index();

            $table->string('ticker')->nullable()->index();
            $table->string('class_code')->nullable()->index();
            $table->string('instrument_type')->nullable()->index();

            $table->decimal('quantity', 24, 8)->default(0);
            $table->decimal('quantity_lots', 24, 8)->nullable();

            $table->decimal('average_position_price', 24, 8)->nullable();
            $table->decimal('average_position_price_fifo', 24, 8)->nullable();

            $table->decimal('current_price', 24, 8)->nullable();
            $table->decimal('current_price_pt', 24, 8)->nullable();

            $table->decimal('current_value', 24, 8)->nullable();

            $table->decimal('expected_yield', 24, 8)->nullable();
            $table->decimal('expected_yield_fifo', 24, 8)->nullable();
            $table->decimal('daily_yield', 24, 8)->nullable();

            $table->decimal('current_nkd', 24, 8)->nullable();
            $table->decimal('var_margin', 24, 8)->nullable();

            $table->boolean('blocked')->default(false);
            $table->decimal('blocked_lots', 24, 8)->nullable();

            $table->string('currency', 12)->nullable();

            $table->jsonb('raw_payload')->nullable();

            $table->timestamps();

            $table->unique(['portfolio_id', 'position_uid']);
            $table->index(['portfolio_id', 'figi']);
            $table->index(['portfolio_id', 'instrument_uid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_positions');
    }
};
