<?php

namespace App\Services\Brokers\TBank;

use App\Contracts\BrokerClientInterface;
use Illuminate\Support\Facades\Http;

class TBankClient implements BrokerClientInterface
{
    private string $baseUrl = 'https://invest-public-api.tbank.ru/rest';
    public function getAccounts(string $token): array
    {
        return $this->sendRequest(
        token: $token,
        endpoint: '/tinkoff.public.invest.api.contract.v1.UsersService/GetAccounts',
        data: [
            'status' => 'ACCOUNT_STATUS_OPEN',
        ],
    );

    }

    public function getPortfolio(string $token, string $accountId): array
    {
        return $this->sendRequest(
            token: $token,
            endpoint: '/tinkoff.public.invest.api.contract.v1.OperationsService/GetPortfolio',
            data: [
                'accountId' => $accountId,
            ],
        );
    }

    public function getPositions(string $token, string $accountId): array
    {
        return $this->sendRequest(
            token: $token,
            endpoint: '/tinkoff.public.invest.api.contract.v1.OperationsService/GetPositions',
            data: [
                'accountId' => $accountId,
            ],
        );
    }

    protected function sendRequest(string $token, string $endpoint, array $data = []): array
    {
        $response = Http::withOptions([
            'verify' => storage_path('certs/tbank.pem'),
        ])->withToken($token)
            ->acceptJson()
            ->asJson()
            ->timeout(15)
            ->post($this->baseUrl . $endpoint, $data);

        $response->throw();

        return $response->json();
    }
}
