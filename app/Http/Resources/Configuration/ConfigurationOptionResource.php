<?php

namespace App\Http\Resources\Configuration;

use App\Http\Resources\Configuration\ConfigurationResource;
use App\Http\Resources\Option\OptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'configuration_id' => $this->configuration_id,
            'option_id' => $this->option_id,
            'configuration' => new ConfigurationResource($this->whenLoaded('configuration')),
            'option' => new OptionResource($this->whenLoaded('option')),
        ];
    }
} 