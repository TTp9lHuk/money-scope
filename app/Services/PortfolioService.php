<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PortfolioService
{
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

}
