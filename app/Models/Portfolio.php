<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Portfolio extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'type',
        'name',
        'status',
        'opened_date',
        'closed_date',
        'access_level',
        'currency',
        'last_synced_at',
        'sync_status',
        'sync_error_message',
        'autosync_enabled',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'opened_date' => 'datetime',
            'closed_date' => 'datetime',
            'last_synced_at' => 'datetime',
            'autosync_enabled' => 'boolean',
            'raw_payload' => 'array',
        ];
    }

    /**
     * Связь с пользователем
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function brokerConnection(): HasOne
    {
        return $this->hasOne(BrokerConnection::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(PortfolioPosition::class);
    }
}
