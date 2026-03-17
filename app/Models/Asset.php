<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    //Активы
    protected $fillable = [
        'portfolio_id',     # К какому портфелю относится.
        'ticker',           # Тикер (например, SBER, AAPL, BTC).
        'type',             # Тип: stock, bond, crypto, currency
        'quantity',         # Количество (может быть дробным для крипты).
        'buy_price',        # Средняя цена покупки (для расчета прибыли).
    ];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
