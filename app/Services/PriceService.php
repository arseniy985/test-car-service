<?php

namespace App\Services;

use App\Models\Price;
use Illuminate\Support\Facades\Cache;

class PriceService
{
    public function getAll()
    {
        return Price::with('configuration', 'configuration.options')->get();
    }

    public function findById(int $id)
    {
        return Price::with('configuration', 'configuration.options')->findOrFail($id);
    }

    public function create(array $data)
    {
        $price = Price::create($data);
        
        $this->clearCacheAfterChanges();
        
        return $price->load('configuration', 'configuration.options');
    }

    public function update(Price $price, array $data)
    {
        $price->update($data);
        
        $this->clearCacheAfterChanges();
        
        return $price->load('configuration', 'configuration.options');
    }

    public function delete(Price $price)
    {
        $result = $price->delete();
        
        $this->clearCacheAfterChanges();
        
        return $result;
    }
    
    protected function clearCacheAfterChanges(): void
    {
        Cache::forget('available_cars');
    }
} 