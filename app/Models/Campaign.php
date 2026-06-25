<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'channel',
        'status',
        'budget',
        'spent',
        'start_date',
        'end_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'budget'     => 'decimal:2',
            'spent'      => 'decimal:2',
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /** Byudjet bandligi foizda (0-100). */
    public function getBudgetUsageAttribute(): float
    {
        if ((float) $this->budget <= 0) {
            return 0;
        }

        return min(100, round(((float) $this->spent / (float) $this->budget) * 100, 1));
    }
}
