<?php

namespace App\Http\Controllers;

use App\Http\Requests\FetchBrokerAccountsRequest;
use App\Services\Brokers\TBank\TBankClient;
use App\Services\PortfolioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BrokerConnectionController extends Controller
{

    public function __construct(protected TBankClient $clientTBank, protected PortfolioService $portfolioService)
    {

    }

    public function fetchAccounts(FetchBrokerAccountsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $token = $validated['api_token'];

        try {
            $accounts = $this->clientTBank->getAccounts($token);
            $accounts['accounts'] = $this->portfolioService->excludeIssetsPortfolio($request->user(), $accounts['accounts']);
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
