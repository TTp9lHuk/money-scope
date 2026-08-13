<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('broker_connections', function (Blueprint $table) {
            $table->foreignId('portfolio_id')
                ->nullable()
                ->after('user_id')
                ->unique()
                ->constrained('portfolios')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('broker_connections', function (Blueprint $table) {
            $table->dropConstrainedForeignId('portfolio_id');
        });
    }
};
