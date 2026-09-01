<?php

namespace App\Http\Controllers;

use App\Contracts\BrokerClientInterface;
use App\Enums\BrokersEnum;
use App\Http\Requests\FetchBrokerAccountsRequest;
use App\Services\Brokers\BrokerClientResolver;
use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;

class BrokerConnectionController extends Controller
{
    protected BrokerClientInterface $clientBank;

    public function __construct(
        protected BrokerClientResolver $brokerClientResolver,
        protected PortfolioService $portfolioService
    )
    {

    }

    public function fetchAccounts(FetchBrokerAccountsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $token = $validated['api_token'];
        $brokerEnum = BrokersEnum::from($validated['broker_type']);
        $this->clientBank = $this->brokerClientResolver->resolve($brokerEnum);

        try {
            $accounts = $this->clientBank->getAccounts($token);
            $accounts['accounts'] = $this->portfolioService->filterAvailableAccounts($request->user(), $accounts['accounts']);
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
