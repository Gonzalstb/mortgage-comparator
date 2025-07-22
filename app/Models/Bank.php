<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo_url',
        'website',
        'api_endpoint',
        'api_credentials',
        'is_active',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'api_credentials' => 'encrypted:array',
            'is_active' => 'boolean',
        ];
    }

    public function mortgageProducts()
    {
        return $this->hasMany(MortgageProduct::class);
    }

    public function activeProducts()
    {
        return $this->mortgageProducts()->where('is_active', true);
    }
}