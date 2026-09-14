<?php

use App\Http\Controllers\BrokerConnectionController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/p')->middleware('auth')->group(function () {
    Route::post('/broker-connections/fetchAccounts', [BrokerConnectionController::class, 'fetchAccounts']);
    Route::post('/portfolios/{portfolio}/sync', [PortfolioController::class, 'sync'])->name('portfolios.sync');
});
