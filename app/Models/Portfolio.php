<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    // Портфели
    protected $fillable = [
        'name',             # Название (например, "Основной Т-Банк").
        'broker_type',      # tinkoff, binance, manual и т.д.
        'api_token',        # Зашифрованный токен (если подключен по API).
        'currency',         # Базовая валюта (RUB, USD).
    ];

    /**
     * Автоматическое шифрование полей
     */
    protected $casts = [
        // 'encrypted' — это встроенный каст Laravel для AES-256-CBC
        'api_token' => 'encrypted',
    ];

    /**
     * Связь с пользователем
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
