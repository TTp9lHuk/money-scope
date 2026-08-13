<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_positions', function (Blueprint $table) {
            $table->dropIndex(['figi']);
            $table->dropIndex(['instrument_uid']);
            $table->dropIndex(['ticker']);
            $table->dropIndex(['class_code']);
            $table->dropIndex(['instrument_type']);

            $table->dropIndex(['portfolio_id', 'figi']);
            $table->dropIndex(['portfolio_id', 'instrument_uid']);

            $table->dropColumn([
                'figi',
                'instrument_uid',
                'ticker',
                'class_code',
                'instrument_type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_positions', function (Blueprint $table) {
            $table->string('figi')->nullable()->index()->after('position_uid');
            $table->string('instrument_uid')->nullable()->index()->after('figi');

            $table->string('ticker')->nullable()->index()->after('instrument_uid');
            $table->string('class_code')->nullable()->index()->after('ticker');
            $table->string('instrument_type')->nullable()->index()->after('class_code');

            $table->index(['portfolio_id', 'figi']);
            $table->index(['portfolio_id', 'instrument_uid']);
        });
    }
};
