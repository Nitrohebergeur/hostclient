<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'type',
        'module',
        'price_hourly',
        'allow_hourly_billing',
        'price_monthly',
        'price_quarterly',
        'price_semiannually',
        'price_annually',
        'price_biennially',
        'setup_fee',
        'currency',
        'resources',
        'config_options',
        'order',
        'is_active',
        'is_featured',
        'auto_provision',
        'stock',
    ];

    protected $casts = [
        'resources' => 'array',
        'config_options' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'auto_provision' => 'boolean',
        'allow_hourly_billing' => 'boolean',
        'price_hourly' => 'decimal:4',
        'price_monthly' => 'decimal:2',
        'price_quarterly' => 'decimal:2',
        'price_semiannually' => 'decimal:2',
        'price_annually' => 'decimal:2',
        'price_biennially' => 'decimal:2',
        'setup_fee' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(ProductGroup::class);
    }

    public function servers(): BelongsToMany
    {
        return $this->belongsToMany(Server::class)->withPivot('priority');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Obtenir le prix selon le cycle de facturation
     */
    public function getPrice(string $billingCycle): float
    {
        return match ($billingCycle) {
            'hourly' => (float) $this->price_hourly,
            'monthly' => (float) $this->price_monthly,
            'quarterly' => (float) $this->price_quarterly,
            'semiannually' => (float) $this->price_semiannually,
            'annually' => (float) $this->price_annually,
            'biennially' => (float) $this->price_biennially,
            default => 0,
        };
    }

    /**
     * Obtenir tous les prix disponibles
     */
    public function getAvailablePrices(): array
    {
        $prices = [];

        if ($this->allow_hourly_billing && $this->price_hourly > 0) {
            $prices['hourly'] = [
                'price' => $this->price_hourly,
                'label' => __('Hourly'),
                'per' => __('/hour'),
            ];
        }

        if ($this->price_monthly > 0) {
            $prices['monthly'] = [
                'price' => $this->price_monthly,
                'label' => __('Monthly'),
                'per' => __('/month'),
            ];
        }

        if ($this->price_quarterly > 0) {
            $prices['quarterly'] = [
                'price' => $this->price_quarterly,
                'label' => __('Quarterly'),
                'per' => __('/3 months'),
            ];
        }

        if ($this->price_semiannually > 0) {
            $prices['semiannually'] = [
                'price' => $this->price_semiannually,
                'label' => __('Semi-Annually'),
                'per' => __('/6 months'),
            ];
        }

        if ($this->price_annually > 0) {
            $prices['annually'] = [
                'price' => $this->price_annually,
                'label' => __('Annually'),
                'per' => __('/year'),
            ];
        }

        if ($this->price_biennially > 0) {
            $prices['biennially'] = [
                'price' => $this->price_biennially,
                'label' => __('Biennially'),
                'per' => __('/2 years'),
            ];
        }

        return $prices;
    }

    /**
     * Vérifier si le produit est en stock
     */
    public function isInStock(): bool
    {
        if ($this->stock === null) {
            return true; // Stock illimité
        }

        return $this->stock > 0;
    }

    /**
     * Décrémenter le stock
     */
    public function decrementStock(): void
    {
        if ($this->stock !== null) {
            $this->decrement('stock');
        }
    }

    /**
     * Incrémenter le stock
     */
    public function incrementStock(): void
    {
        if ($this->stock !== null) {
            $this->increment('stock');
        }
    }
}
