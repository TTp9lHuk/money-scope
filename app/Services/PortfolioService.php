<?php

namespace App\Services;

use App\Models\Portfolio;
use App\Models\PortfolioPosition;
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
            try {
                $portfolio = $user->portfolios()->create($portfolioBrokerData);
            }
            catch (UniqueConstraintViolationException $e) {
                throw new PortfolioAlreadyExistsException();
            }
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
    public function quotationToDecimal(array $value): float
    {
        if(empty($value)){
            $value['units'] = 0;
            $value['nano'] = 0;
        }

        return (float) $value['units']
            + ((int) ($value['nano'] ?? 0) / 1_000_000_000);
    }

    public function syncPortfolio(User $user, int $portfolioId): Portfolio
    {
        try {
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

            return DB::transaction(function () use ($portfolio, $portfolioPositions) {

                $instrumentUids = [];

                foreach ($portfolioPositions['positions'] as $portfolioPosition) {
                    $instrumentUids[$portfolioPosition['instrumentUid']] = $portfolioPosition['instrumentUid'];
                }

                $assetsMap = $this->assetsService->addAssetFromPortfolioPositions($this->assetsService->getAssets($instrumentUids), $portfolioPositions['positions']);
                unset($instrumentUids);
                $portfolioPositionsRows = [];
                $actualPositionUids = [];

                foreach ($portfolioPositions['positions'] as $portfolioPosition) {

                    $assetId = $assetsMap[$portfolioPosition['instrumentUid']] ?? null;

                    if (!$assetId) {
                        throw new \RuntimeException(
                            "Asset not found: {$portfolioPosition['instrumentUid']}"
                        );
                    }

                    $actualPositionUids[] = $portfolioPosition['positionUid'];

                    $quantity = $this->quotationToDecimal($portfolioPosition['quantity']);
                    $currentPrice = $this->quotationToDecimal($portfolioPosition['currentPrice']);
                    $currentValue = $quantity * $currentPrice;

                    $portfolioPositionsRows[] = [
                        'asset_id' => $assetId,
                        'portfolio_id' => $portfolio->id,
                        'position_uid' => $portfolioPosition['positionUid'],
                        'quantity' => $this->quotationToDecimal($portfolioPosition['quantity']),
                        'quantity_lots' => $this->quotationToDecimal($portfolioPosition['quantityLots']),
                        'average_position_price' => $this->quotationToDecimal($portfolioPosition['averagePositionPrice']),
                        'average_position_price_fifo' => $this->quotationToDecimal($portfolioPosition['averagePositionPriceFifo']),
                        'current_price' => $this->quotationToDecimal($portfolioPosition['currentPrice']),
                        'current_price_pt' => $this->quotationToDecimal($portfolioPosition['averagePositionPricePt']),
                        'current_value' => $currentValue,
                        'expected_yield' => $this->quotationToDecimal($portfolioPosition['expectedYield']),
                        'expected_yield_fifo' => $this->quotationToDecimal($portfolioPosition['expectedYieldFifo']),
                        'daily_yield' => $this->quotationToDecimal($portfolioPosition['dailyYield']),
                        'current_nkd' => $this->quotationToDecimal($portfolioPosition['currentNkd']?? []),
                        'var_margin' => $this->quotationToDecimal($portfolioPosition['varMargin']),
                        'blocked' => $portfolioPosition['blocked'],
                        'blocked_lots' => $this->quotationToDecimal($portfolioPosition['blockedLots']),
                        'currency' => $portfolioPosition['currentPrice']['currency'],
                        'raw_payload' => json_encode($portfolioPosition, JSON_UNESCAPED_UNICODE),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                PortfolioPosition::upsert(
                    $portfolioPositionsRows,
                    ['portfolio_id', 'position_uid'],
                    [
                        'asset_id',
                        'quantity',
                        'quantity_lots',
                        'average_position_price',
                        'average_position_price_fifo',
                        'current_price',
                        'current_price_pt',
                        'current_value',
                        'expected_yield',
                        'expected_yield_fifo',
                        'daily_yield',
                        'current_nkd',
                        'var_margin',
                        'blocked',
                        'blocked_lots',
                        'currency',
                        'raw_payload',
                        'updated_at',
                    ]
                );

                $portfolio->positions()
                    ->whereNotIn('position_uid', $actualPositionUids)
                    ->delete();

                $portfolio->update([
                    'sync_status' => 'success',
                    'sync_error_message' => null,
                    'last_synced_at' => now(),
                ]);

                return $portfolio;
            });

        }catch (\Throwable $e) {

            $portfolio->update([
                'sync_status' => 'error',
                'sync_error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

}
