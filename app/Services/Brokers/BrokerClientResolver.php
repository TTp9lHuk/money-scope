<?php

namespace App\Services\Brokers;

use App\Contracts\BrokerClientInterface;
use App\Enums\BrokersEnum;
use App\Services\Brokers\TBank\TBankClient;

class BrokerClientResolver
{
    public function __construct(
        protected TBankClient $tBankClient,
    ) {
    }
    public function resolve(BrokersEnum $broker): BrokerClientInterface
    {
        return match ($broker) {
            BrokersEnum::TBank => $this->tBankClient,
        };
    }
}
