<?php

namespace App\Http\Resources\Configuration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConfigurationCollectionResource extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ConfigurationResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
} 