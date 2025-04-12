<?php

namespace App\Services;

use App\Models\Configuration;
use Illuminate\Support\Facades\DB;

class ConfigurationService
{
    public function getAll()
    {
        return Configuration::with(['car', 'options'])->get();
    }

    public function findById(int $id)
    {
        return Configuration::with(['car', 'options'])->findOrFail($id);
    }

    public function create(array $data)
    {
        $options = $data['options'] ?? [];
        unset($data['options']);
        
        return DB::transaction(function () use ($data, $options) {
            $configuration = Configuration::create($data);
            
            if (!empty($options)) {
                $configuration->options()->attach($options);
            }
            
            return $configuration->load('options');
        });
    }

    public function update(Configuration $configuration, array $data)
    {
        $options = $data['options'] ?? null;
        unset($data['options']);
        
        return DB::transaction(function () use ($configuration, $data, $options) {
            $configuration->update($data);
            
            if ($options !== null) {
                $configuration->options()->sync($options);
            }
            
            return $configuration->load('options');
        });
    }

    public function delete(Configuration $configuration)
    {
        return $configuration->delete();
    }
} 