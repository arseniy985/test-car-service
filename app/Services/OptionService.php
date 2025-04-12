<?php

namespace App\Services;

use App\Models\Option;

class OptionService
{
    public function getAll()
    {
        return Option::all();
    }

    public function findById(int $id)
    {
        return Option::findOrFail($id);
    }

    public function create(array $data)
    {
        return Option::create($data);
    }

    public function update(Option $option, array $data)
    {
        $option->update($data);
        return $option;
    }

    public function delete(Option $option)
    {
        return $option->delete();
    }
} 