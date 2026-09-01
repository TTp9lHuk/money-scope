<?php

namespace App\Contracts;

interface BrokerClientInterface
{
    public function getAccounts(string $token): array;
}
