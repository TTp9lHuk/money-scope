<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'currency' => $this->currency,
            'account_id' => $this->account_id,
            'sync_status' => $this->sync_status,
            'sync_error_message' => $this->sync_error_message,
            'last_synced_at' => $this->last_synced_at,
            'positions' => PortfolioPositionResource::collection(
                $this->whenLoaded('positions')
            ),

        ];
    }
}
