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

            $brokerConnection  =$user->brokerConnections()->create([
                'portfolio_id' => $portfolio->id,
                ...$portfolioBrokerData
            ]);

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

        if($this->checkUserPortfolioId($user, $portfolio)){

            $portfolioData = $portfolio->with('brokerConnection')->get()->toArray();
            $brokerConnection = $portfolioData[0]['broker_connection'];
            $brokerEnum = BrokersEnum::from($brokerConnection['broker_type']);
            $brokerClient = $this->brokerClientResolver->resolve($brokerEnum);
            $portfolioPositions = $brokerClient->getPortfolio($brokerConnection['api_token'],$portfolioData[0]['account_id']);
            $instrumentalUid = [];

            foreach ($portfolioPositions['positions'] as $portfolioPosition) {
                $instrumentalUid[$portfolioPosition['instrumentUid']] = $portfolioPosition['instrumentUid'];
            }

            $assetsMap = $this->assetsService->addAssetFromPortfolioPositions($this->assetsService->getAssets($instrumentalUid),$portfolioPositions['positions']);
            dd($assetsMap);
        }else{
            throw new \Exception("Portfolio doesn't exist");
        }
    }

}
