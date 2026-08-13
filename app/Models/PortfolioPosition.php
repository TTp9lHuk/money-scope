<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioPosition extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'type',
        'name',
        'status',
        'currency',
        'last_synced_at',
        'sync_status',
        'sync_error_message',
        'autosync_enabled',
    ];

    protected function casts(): array
    {
        return [
            'last_synced_at' => 'datetime',
            'autosync_enabled' => 'boolean',
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
