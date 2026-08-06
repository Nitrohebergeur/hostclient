<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'type',
        'price',
        'setup_fee',
        'billing_cycle',
        'stock',
        'is_unlimited_stock',
        'is_active',
        'is_featured',
        'auto_setup',
        'module',
        'config',
        'features',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'setup_fee' => 'decimal:2',
        'is_unlimited_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'auto_setup' => 'boolean',
        'config' => 'array',
        'features' => 'array',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('is_unlimited_stock', true)
              ->orWhere('stock', '>', 0);
        });
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function isInStock(): bool
    {
        return $this->is_unlimited_stock || $this->stock > 0;
    }

    public function decrementStock(): void
    {
        if (!$this->is_unlimited_stock && $this->stock > 0) {
            $this->decrement('stock');
        }
    }

    public function incrementStock(): void
    {
        if (!$this->is_unlimited_stock) {
            $this->increment('stock');
        }
    }

    public function getPriceForCycle(string $cycle): float
    {
        $multipliers = [
            'monthly' => 1,
            'quarterly' => 3,
            'semi_annually' => 6,
            'annually' => 12,
            'biennially' => 24,
            'triennially' => 36,
            'one_time' => 1,
        ];

        return $this->price * ($multipliers[$cycle] ?? 1);
    }
}
