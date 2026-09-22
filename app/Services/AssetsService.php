<?php

namespace App\Services;

use App\Models\Asset;

class AssetsService
{
    public function getAssets(array $instrumentalUid = []): array
    {
        return Asset::query()
            ->when(!empty($instrumentalUid), fn($q) => $q->whereIn('instrument_uid', $instrumentalUid))
            ->get()
            ->keyBy('instrument_uid')
            ->toArray();
    }

    public function addAssetFromPortfolioPositions(array $assetsExisting, array $portfolioPositions): array
    {
        $assetsMap = [];

        foreach ($portfolioPositions as $positionItem) {
            if(!isset($assetsExisting[$positionItem['instrumentUid']])) {
                $asset = Asset::updateOrCreate(
                    ['instrument_uid' => $positionItem['instrumentUid']],
                    [
                        'figi' => $positionItem['figi'],
                        'ticker' => $positionItem['ticker'],
                        'class_code' => $positionItem['classCode'],
                        'instrument_type' => $positionItem['instrumentType'],
                        'currency' => $positionItem['currentPrice']['currency'],
                        'is_active' => 1,
                        'raw_payload' => $positionItem,
                        'instrument_uid' => $positionItem['instrumentUid'],
                        'name' => $positionItem['name'] ?? $positionItem['ticker'],
                    ]
                );

                $assetsMap[$positionItem['instrumentUid']] = $asset->id;

            }else{
                $assetsMap[$positionItem['instrumentUid']] = $assetsExisting[$positionItem['instrumentUid']]['id'];
            }
        }

        return $assetsMap;
    }
}
