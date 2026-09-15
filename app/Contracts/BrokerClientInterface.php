<?php

namespace App\Contracts;

interface BrokerClientInterface
{
    public function getAccounts(string $token): array;
    public function getPortfolio(string $token, string $accountId): array;
    public function getPositions(string $token, string $accountId): array;
}
