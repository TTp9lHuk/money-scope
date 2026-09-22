<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'currency' => $this->currency,

            'sync_status' => $this->sync_status,
            'last_synced_at' => $this->last_synced_at,

            'positions' => $this->positions->map(function ($position) {

                return [
                    'id' => $position->id,

                    'quantity' => $position->quantity,
                    'current_price' => $position->current_price,
                    'current_value' => $position->current_value,
                    'expected_yield' => $position->expected_yield,

                    'currency' => $position->currency,

                    'asset' => new AssetResource($position->asset),
                ];
            }),
        ];
    }
}
