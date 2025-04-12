<?php

namespace App\Http\Resources\Configuration;

use App\Http\Resources\Car\CarResource;
use App\Http\Resources\Option\OptionResource;
use App\Http\Resources\Price\PriceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationResource extends JsonResource
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
            'car_id' => $this->car_id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'car' => new CarResource($this->whenLoaded('car')),
            'options' => OptionResource::collection($this->whenLoaded('options')),
            'prices' => PriceResource::collection($this->whenLoaded('prices')),
        ];
    }
} 