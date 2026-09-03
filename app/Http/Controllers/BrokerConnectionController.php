<?php

namespace App\Http\Controllers;

use App\Enums\BrokersEnum;
use App\Http\Requests\FetchBrokerAccountsRequest;
use App\Services\Brokers\BrokerConnectionService;
use Illuminate\Http\JsonResponse;

class BrokerConnectionController extends Controller
{


    public function __construct(
        protected BrokerConnectionService $brokerConnectionService,
    )
    {

    }

    public function fetchAccounts(FetchBrokerAccountsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $token = $validated['api_token'];
        $brokerEnum = BrokersEnum::from($validated['broker_type']);

        try {
            $accounts = $this->brokerConnectionService->getBrokerAccounts($brokerEnum,$token,$request->user());
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
