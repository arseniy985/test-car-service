<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class CarService
{
    public function getAll()
    {
        return Car::with('configurations.options')->get();
    }

    public function findById(int $id)
    {
        return Car::findOrFail($id);
    }

    public function create(array $data)
    {
        return Car::create($data);
    }

    public function update(Car $car, array $data)
    {
        $car->update($data);
        return $car;
    }

    public function delete(Car $car)
    {
        return $car->delete();
    }

    public function getAvailable(): Collection
    {
        return Cache::remember('available_cars', 600, function () {
            $today = now()->toDateString();
            
            $cars = Car::with([
                'configurations' => function ($query) use ($today) {
                    $query->with([
                        'options' => fn ($query) => $query->select('name')
                    ]);
                    
                    $query->whereHas('prices', function ($query) use ($today) {
                        $this->applyDateConstraints($query, $today);
                    });
                },
                'configurations.prices' => function ($query) use ($today) {
                    $this->applyDateConstraints($query, $today);
                    $query->orderBy('start_date', 'desc')
                          ->limit(1);
                }
            ])->get();
            
            return $cars->map(function ($car) {
                $formattedCar = [
                    'id' => $car->id,
                    'name' => $car->name,
                    'configurations' => []
                ];
                
                // Форматируем комплектации
                foreach ($car->configurations as $config) {
                    $price = $config->prices->first();
                    
                    if ($price) {
                        $formattedCar['configurations'][] = [
                            'id' => $config->id,
                            'name' => $config->name,
                            'options' => $config->options->pluck('name')->toArray(),
                            'current_price' => $price->price
                        ];
                    }
                }
                
                if (empty($formattedCar['configurations'])) {
                    return null;
                }
                
                return $formattedCar;
            })
            ->filter() 
            ->values();
        });
    }

    /**
     * Apply date range constraints to a query.
     */
    private function applyDateConstraints($query, string $date): void
    {
        $query->where('start_date', '<=', $date)
              ->where(function ($query) use ($date) {
                  $query->where('end_date', '>=', $date)
                        ->orWhereNull('end_date');
              });
    }
} 