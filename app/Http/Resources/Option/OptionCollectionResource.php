<?php

namespace App\Http\Resources\Option;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OptionCollectionResource extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = OptionResource::class;

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