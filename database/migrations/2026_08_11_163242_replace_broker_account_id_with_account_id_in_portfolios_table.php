<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropForeign(['broker_account_id']);
        });

        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn('broker_account_id');
        });

        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('account_id')
                ->nullable()
                ->after('user_id')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropIndex(['account_id']);
            $table->dropColumn('account_id');

            $table->foreignId('broker_account_id')
                ->nullable()
                ->after('user_id')
                ->constrained('broker_accounts')
                ->nullOnDelete();
        });
    }
};
