<?php

namespace App\Services\Brokers;

use App\Enums\BrokersEnum;
use App\Models\User;
use App\Services\PortfolioService;

class BrokerConnectionService
{
    public function __construct(
        protected BrokerClientResolver $brokerClientResolver,
        protected PortfolioService $portfolioService
    ) {
    }

    public function getBrokerAccounts(BrokersEnum $brokerEnum, string $token, User $user): array
    {
        $clientBank = $this->brokerClientResolver->resolve($brokerEnum);
        $accounts = $clientBank->getAccounts($token);
        $accounts['accounts'] = $this->portfolioService->filterAvailableAccounts($user, $accounts['accounts']);
        return $accounts;
    }
}
