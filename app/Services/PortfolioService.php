<?php

namespace App\Services;

use App\Models\User;

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
}
