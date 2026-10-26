<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssetMetadata extends Model
{
    public function asset(): HasOne
    {
        return $this->hasOne(Asset::class);
    }
}
