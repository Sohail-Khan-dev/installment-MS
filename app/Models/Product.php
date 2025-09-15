<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'stock_quantity',
        'sku',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function installmentPlans()
    {
        return $this->hasMany(InstallmentPlan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function decrementStock($quantity = 1)
    {
        return $this->decrement('stock_quantity', $quantity);
    }

    public function incrementStock($quantity = 1)
    {
        return $this->increment('stock_quantity', $quantity);
    }
}