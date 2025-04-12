<?php

namespace App\Http\Resources\Car;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailableCarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['id'],
            'name' => $this->resource['name'],
            'configurations' => $this->resource['configurations'],
        ];
    }
} 