<?php

namespace App\Models;

use App\Enums\SyncStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioPosition extends Model
{
    protected $fillable = [
        'portfolio_id',
        'asset_id',
        'position_uid',
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
    ];

    protected function casts(): array
    {
        return [
            'blocked' => 'boolean',
            'raw_payload' => 'array',
        ];
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
