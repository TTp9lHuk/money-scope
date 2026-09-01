<?php

namespace App\Services\Brokers;

use App\Contracts\BrokerClientInterface;
use App\Enums\BrokersEnum;

class BrokerClientResolver
{
    public function resolve(BrokersEnum $broker): BrokerClientInterface
    {
        return app()->make(
            $broker->getClass()
        );
    }
}
