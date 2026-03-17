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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');

            $table->string('ticker'); // Например: SBER, AAPL, BTC
            $table->string('type');   // stock, bond, crypto, currency

            // Используем decimal для точности (крипта требует много знаков)
            $table->decimal('quantity', 20, 8);
            $table->decimal('buy_price', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
