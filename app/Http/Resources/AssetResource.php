<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
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

            'ticker' => $this->ticker,
            'name' => $this->name,

            'instrument_type' => $this->instrument_type,
            'currency' => $this->currency,

            'isin' => $this->isin,

            'logo_url' => $this->logoUrl(160),
        ];
    }
}
