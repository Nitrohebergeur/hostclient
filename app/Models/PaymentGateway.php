<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'is_active',
        'order',
        'config',
        'supported_currencies',
        'fee_fixed',
        'fee_percentage',
        'supports_recurring',
        'supports_refunds',
        'supports_webhooks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
        'supported_currencies' => 'array',
        'fee_fixed' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
        'supports_recurring' => 'boolean',
        'supports_refunds' => 'boolean',
        'supports_webhooks' => 'boolean',
    ];

    /**
     * Crypter la configuration avant stockage
     */
    public function setConfigAttribute($value): void
    {
        if ($value && is_array($value)) {
            // Crypter les clés sensibles
            if (isset($value['api_key'])) {
                $value['api_key'] = Crypt::encryptString($value['api_key']);
            }
            if (isset($value['api_secret'])) {
                $value['api_secret'] = Crypt::encryptString($value['api_secret']);
            }
            if (isset($value['webhook_secret'])) {
                $value['webhook_secret'] = Crypt::encryptString($value['webhook_secret']);
            }
            $this->attributes['config'] = json_encode($value);
        }
    }

    /**
     * Décrypter la configuration
     */
    public function getConfigAttribute($value): ?array
    {
        if ($value) {
            $config = json_decode($value, true);
            
            // Décrypter les clés sensibles
            if (isset($config['api_key'])) {
                try {
                    $config['api_key'] = Crypt::decryptString($config['api_key']);
                } catch (\Exception $e) {
                    $config['api_key'] = null;
                }
            }
            if (isset($config['api_secret'])) {
                try {
                    $config['api_secret'] = Crypt::decryptString($config['api_secret']);
                } catch (\Exception $e) {
                    $config['api_secret'] = null;
                }
            }
            if (isset($config['webhook_secret'])) {
                try {
                    $config['webhook_secret'] = Crypt::decryptString($config['webhook_secret']);
                } catch (\Exception $e) {
                    $config['webhook_secret'] = null;
                }
            }
            
            return $config;
        }
        return null;
    }

    /**
     * Calculer les frais totaux pour un montant
     */
    public function calculateFees(float $amount): float
    {
        $fees = (float) $this->fee_fixed;
        $fees += ($amount * ((float) $this->fee_percentage / 100));
        return round($fees, 2);
    }

    /**
     * Vérifier si la devise est supportée
     */
    public function supportsCurrency(string $currency): bool
    {
        if (empty($this->supported_currencies)) {
            return true; // Toutes les devises
        }
        return in_array(strtoupper($currency), $this->supported_currencies);
    }

    /**
     * Obtenir l'icône de la passerelle
     */
    public function getIcon(): string
    {
        if ($this->logo) {
            return $this->logo;
        }

        return match ($this->slug) {
            'stripe' => 'fab fa-stripe',
            'paypal' => 'fab fa-paypal',
            'mollie' => 'fas fa-credit-card',
            'bank_transfer' => 'fas fa-university',
            'credit' => 'fas fa-wallet',
            default => 'fas fa-credit-card',
        };
    }
}
