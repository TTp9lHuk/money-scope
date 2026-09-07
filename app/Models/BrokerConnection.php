<?php

namespace App\Models;

use App\Enums\BrokersEnum;
use App\Enums\SyncStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokerConnection extends Model
{
    protected $fillable = [
        'portfolio_id',
        'broker_type',
        'name',
        'api_token',
        'last_synced_at',
        'sync_status',
        'sync_error_message',
    ];

    /**
     * Автоматическое шифрование полей
     */
    protected function casts(): array
    {
        return [
            'api_token' => 'encrypted',
            'last_synced_at' => 'datetime',
            'sync_status' => SyncStatusEnum::class,
            'broker_type' => BrokersEnum::class,
        ];
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
