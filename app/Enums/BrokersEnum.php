<?php

namespace App\Enums;

use App\Services\Brokers\TBank\TBankClient;

enum BrokersEnum: string
{
    case TBank = 'tinkoff';

    public function getClass(): string
    {
        return match ($this) {
            self::TBank => TBankClient::class,
        };
    }
}
