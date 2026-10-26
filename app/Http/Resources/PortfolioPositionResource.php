<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioPositionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'quantity' => $this->quantity,
            'quantity_lots' => $this->quantity_lots,
            'average_position_price' => $this->average_position_price,
            'current_price' => $this->current_price,
            'current_value' => $this->current_value,
            'expected_yield' => $this->expected_yield,
            'expected_yield_fifo' => $this->expected_yield_fifo,
            'daily_yield' => $this->daily_yield,
            'currency' => $this->currency,
            'asset' => new AssetResource($this->asset),
        ];
    }
}
