<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Simulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_code',
        'property_price',
        'loan_amount',
        'term_years',
        'user_data',
        'results',
        'is_favorite',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'property_price' => 'decimal:2',
            'loan_amount' => 'decimal:2',
            'user_data' => 'array',
            'results' => 'array',
            'is_favorite' => 'boolean',
            'viewed_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($simulation) {
            $simulation->reference_code = 'SIM-' . strtoupper(Str::random(8));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLtvAttribute(): float
    {
        if ($this->property_price <= 0) {
            return 0;
        }
        
        return ($this->loan_amount / $this->property_price) * 100;
    }

    public function markAsViewed(): void
    {
        $this->update(['viewed_at' => now()]);
    }

    public function toggleFavorite(): void
    {
        $this->update(['is_favorite' => !$this->is_favorite]);
    }
}