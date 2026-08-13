<?php

use App\Http\Controllers\BrokerConnectionController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/p')->middleware('auth')->group(function () {
    Route::post('/broker-connections/fetchAccounts', [BrokerConnectionController::class, 'fetchAccounts']);
});
