<?php

namespace App\Services;

use App\Models\User;

class PortfolioService
{
    public function excludeExistingPortfolios(User $user, array $accounts): array
    {
        $ids = [];
        foreach ($accounts as $key => $arAccount) {
            $ids[$arAccount['id']] = $key;
        }

        $currentPortfolios = $user->portfolios()->whereIn('account_id', array_keys($ids))->get('account_id');

        foreach ($currentPortfolios as $portfolio) {
            unset($accounts[$ids[$portfolio['account_id']]]);
        }

        return $accounts;
    }
}
