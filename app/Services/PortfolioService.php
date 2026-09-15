<?php

namespace App\Services;

use App\Enums\BrokersEnum;
use App\Models\Portfolio;
use App\Models\User;
use App\Services\Brokers\BrokerClientResolver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PortfolioService
{
    public function __construct(
        protected BrokerClientResolver $brokerClientResolver,
        protected AssetsService $assetsService
    ) {
    }
    public function filterAvailableAccounts(User $user, array $accounts): array
    {
        $ids = [];
        foreach ($accounts as $key => $arAccount) {
            $ids[$arAccount['id']] = $key;
        }

        $currentPortfolios = $user->portfolios()
            ->whereIn('account_id', array_keys($ids))
            ->pluck('account_id')
            ->toArray();

        foreach ($currentPortfolios as $accountId) {
            unset($accounts[$ids[$accountId]]);
        }

        return array_values($accounts);
    }

    public function createPortfolioWithBroker(User $user, array $portfolioBrokerData): array
    {
        return DB::transaction(function () use ($user, $portfolioBrokerData) {
            $portfolio = $user->portfolios()->create($portfolioBrokerData);

            /*$brokerConnection  =$user->brokerConnections()->create([
                'portfolio_id' => $portfolio->id,
                ...$portfolioBrokerData
            ]);*/

            $brokerConnection = $portfolio
                ->brokerConnection()
                ->create($portfolioBrokerData);

            return [
                'portfolio' => $portfolio,
                'broker_connection' => $brokerConnection
            ];
        });
    }

    public function getUserPortfolioWithBrokerPositionsCount(User $user): Collection
    {
        return $user->portfolios()->with('brokerConnection')->withCount('positions')->get();
    }

    public function checkUserPortfolioId(User $user, Portfolio $portfolio): bool
    {
        return $user->id == $portfolio->user()->first()->id;
    }

    public function syncPortfolio(User $user, int $portfolioId): void
    {
        $portfolio = $user->portfolios()->find($portfolioId);

        if (!$portfolio) {
            throw new \Exception("Portfolio doesn't exist");
        }


        $brokerConnection = $portfolio->brokerConnection;
        $brokerClient = $this->brokerClientResolver->resolve($brokerConnection->broker_type);
        $portfolioPositions = $brokerClient->getPortfolio(
            $brokerConnection->api_token,
            $portfolio->account_id
        );
        $instrumentUids = [];

        foreach ($portfolioPositions['positions'] as $portfolioPosition) {
            $instrumentUids[$portfolioPosition['instrumentUid']] = $portfolioPosition['instrumentUid'];
        }

        $assetsMap = $this->assetsService->addAssetFromPortfolioPositions($this->assetsService->getAssets($instrumentUids), $portfolioPositions['positions']);
        dd($assetsMap);
    }

}
