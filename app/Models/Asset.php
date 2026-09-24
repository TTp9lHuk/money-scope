<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'figi',
        'instrument_uid',
        'ticker',
        'class_code',
        'name',
        'instrument_type',
        'currency',
        'is_active',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'raw_payload' => 'array',
        ];
    }

    public function portfolioPositions(): HasMany
    {
        return $this->hasMany(PortfolioPosition::class);
    }

    public function logoUrl(string $tiker, int $size = 160): ?string
    {
        if (!$tiker) {
            return null;
        }

        return config('services.tbank.brands_url')
            . "/{$tiker}x{$size}.png";
    }
}
