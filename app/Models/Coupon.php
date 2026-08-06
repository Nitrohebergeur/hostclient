<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'max_uses',
        'uses',
        'max_uses_per_user',
        'minimum_order',
        'starts_at',
        'expires_at',
        'is_active',
        'apply_to_setup_fee',
        'product_ids',
        'category_ids',
    ];

    protected $casts = [
        'value'              => 'decimal:2',
        'minimum_order'      => 'decimal:2',
        'starts_at'          => 'date',
        'expires_at'         => 'date',
        'is_active'          => 'boolean',
        'apply_to_setup_fee' => 'boolean',
        'product_ids'        => 'array',
        'category_ids'       => 'array',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses && $this->uses >= $this->max_uses) return false;

        return true;
    }

    public function calculateDiscount(float $subtotal, float $setupFee = 0): float
    {
        $discount = match ($this->type) {
            'percentage' => $subtotal * ($this->value / 100),
            'fixed'      => min($this->value, $subtotal),
            'free_setup' => $this->apply_to_setup_fee ? $setupFee : 0,
            default      => 0,
        };

        return round($discount, 2);
    }
}
