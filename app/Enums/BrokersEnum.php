<?php

namespace App\Enums;

use App\Services\Brokers\TBank\TBankClient;

enum BrokersEnum: string
{
    case TBank = 'tinkoff';
}
