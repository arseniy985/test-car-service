<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'name',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function currentPrice()
    {
        $today = now()->toDateString();
        
        return $this->prices()
            ->where('start_date', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->where('end_date', '>=', $today)
                    ->orWhereNull('end_date');
            })
            ->orderBy('start_date', 'desc')
            ->first();
    }
}
