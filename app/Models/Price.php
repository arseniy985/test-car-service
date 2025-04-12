<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'configuration_id',
        'price',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function configuration(): BelongsTo
    {
        return $this->belongsTo(Configuration::class);
    }

    public function scopeActive($query, $date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return $query->where('start_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->where('end_date', '>=', $date)
                    ->orWhereNull('end_date');
            });
    }
}
