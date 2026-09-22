<?php

namespace App\Enums;

enum SyncStatusEnum: string
{
    case IDLE = 'idle';
    case SYNCING = 'syncing';
    case SUCCESS = 'success';
    case ERROR = 'error';
}
