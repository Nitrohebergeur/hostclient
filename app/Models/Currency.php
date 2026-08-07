<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'symbol', 'symbol_position', 'decimal_places',
        'decimal_separator', 'thousands_separator', 'exchange_rate',
        'is_default', 'is_active', 'rate_updated_at',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'rate_updated_at' => 'datetime',
    ];

    /**
     * Formater un montant dans cette devise
     */
    public function format(float $amount): string
    {
        $formatted = number_format(
            $amount,
            $this->decimal_places,
            $this->decimal_separator,
            $this->thousands_separator
        );

        return $this->symbol_position === 'left'
            ? $this->symbol . $formatted
            : $formatted . ' ' . $this->symbol;
    }

    /**
     * Convertir un montant depuis la devise de base
     */
    public function convertFromBase(float $amount): float
    {
        return round($amount * $this->exchange_rate, $this->decimal_places);
    }

    /**
     * Convertir un montant vers la devise de base
     */
    public function convertToBase(float $amount): float
    {
        if ($this->exchange_rate == 0) return 0;
        return round($amount / $this->exchange_rate, 2);
    }

    /**
     * Obtenir la devise par défaut
     */
    public static function getDefault(): static
    {
        return Cache::remember('default_currency', 3600, function () {
            return static::where('is_default', true)->first()
                ?? static::where('code', 'EUR')->first()
                ?? static::first();
        });
    }

    /**
     * Obtenir toutes les devises actives (cachées)
     */
    public static function getActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('active_currencies', 3600, function () {
            return static::where('is_active', true)->orderBy('code')->get();
        });
    }

    /**
     * Définir comme devise par défaut
     */
    public function setAsDefault(): void
    {
        static::where('is_default', true)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
        Cache::forget('default_currency');
        Cache::forget('active_currencies');
    }

    /**
     * Mettre à jour les taux depuis une API externe
     */
    public static function updateRates(string $baseCurrency = 'EUR'): void
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get(
                "https://api.exchangerate-api.com/v4/latest/{$baseCurrency}"
            );

            if ($response->successful()) {
                $rates = $response->json('rates', []);

                foreach ($rates as $code => $rate) {
                    static::where('code', $code)->update([
                        'exchange_rate' => $rate,
                        'rate_updated_at' => now(),
                    ]);
                }

                Cache::forget('active_currencies');
                Cache::forget('default_currency');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to update currency rates', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
