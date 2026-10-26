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

    public function getAssets(string $token, string $instrumentType): array
    {
        return $this->sendRequest(
            token: $token,
            endpoint: '/tinkoff.public.invest.api.contract.v1.InstrumentsService/GetAssets',
            data: [
                'instrumentType' => $instrumentType,
            ],
        );
    }

    public function getAssetBy(string $token, string $instrumentUid): array
    {
        return $this->sendRequest(
            token: $token,
            endpoint: '/tinkoff.public.invest.api.contract.v1.InstrumentsService/GetAssetBy',
            data: [
                'id' => $instrumentUid,
            ],
        );
    }

    public function getBondBy(string $token, string $uid): array
    {
        return $this->sendRequest(
            token: $token,
            endpoint: '/tinkoff.public.invest.api.contract.v1.InstrumentsService/BondBy',
            data: [
                'id_type' => 'INSTRUMENT_ID_TYPE_UID',
                'id' => $uid,
            ],
        );
    }

    protected function sendRequest(string $token, string $endpoint, array $data = []): array
    {

        /*dd([
            'url' => $this->baseUrl . $endpoint,
            'data' => $data
        ]);*/
        $response = Http::withToken($token)
            ->acceptJson()
            ->asJson()
            ->timeout(15)
            ->post($this->baseUrl . $endpoint, $data);

        $response->throw();

        return $response->json();
    }
}
