<?php

namespace App\Http\Resources\Price;

use App\Http\Resources\Configuration\ConfigurationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
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
            'configuration_id' => $this->configuration_id,
            'price' => $this->price,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'configuration' => new ConfigurationResource($this->whenLoaded('configuration')),
        ];
    }
} 