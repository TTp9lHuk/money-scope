<?php

namespace App\Services\Brokers\TBank;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class TBankClient
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

    public function getPortfolio(array $data)
    {

    }

    public function getPositions(array $data)
    {

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
