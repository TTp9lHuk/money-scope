<?php

namespace App\Http\Controllers;

use App\Services\Brokers\TBank\TBankClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BrokerConnectionController extends Controller
{
    protected TBankClient $clientTBank;
    public function __construct()
    {
        $this->clientTBank = new TBankClient();
    }

    public function fetchAccounts(Request $request): JsonResponse
    {
        $token = $request->input('api_token');

        if (!$token) {
            return response()->json([
                'error' => 'Токен обязателен'
            ], 422);
        }

        try {
            $accounts = $this->clientTBank->getAccounts($token);

            return response()->json([
                'data' => $accounts,
                'success' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
