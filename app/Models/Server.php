<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Server extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'hostname',
        'port',
        'use_ssl',
        'api_key',
        'api_secret',
        'username',
        'password',
        'config',
        'is_active',
        'max_accounts',
        'current_accounts',
        'status',
        'last_checked_at',
        'last_check_data',
        'notes',
    ];

    protected $casts = [
        'use_ssl' => 'boolean',
        'config' => 'array',
        'is_active' => 'boolean',
        'last_check_data' => 'array',
        'last_checked_at' => 'datetime',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
        'password',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('priority');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Crypter l'API key avant stockage
     */
    public function setApiKeyAttribute($value): void
    {
        if ($value) {
            $this->attributes['api_key'] = Crypt::encryptString($value);
        }
    }

    /**
     * Décrypter l'API key
     */
    public function getApiKeyAttribute($value): ?string
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Crypter l'API secret avant stockage
     */
    public function setApiSecretAttribute($value): void
    {
        if ($value) {
            $this->attributes['api_secret'] = Crypt::encryptString($value);
        }
    }

    /**
     * Décrypter l'API secret
     */
    public function getApiSecretAttribute($value): ?string
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Crypter le mot de passe avant stockage
     */
    public function setPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['password'] = Crypt::encryptString($value);
        }
    }

    /**
     * Décrypter le mot de passe
     */
    public function getPasswordAttribute($value): ?string
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Vérifier si le serveur peut accepter plus de comptes
     */
    public function canAcceptAccounts(): bool
    {
        if (!$this->is_active || $this->status !== 'online') {
            return false;
        }

        if ($this->max_accounts === null) {
            return true; // Illimité
        }

        return $this->current_accounts < $this->max_accounts;
    }

    /**
     * Incrémenter le nombre de comptes
     */
    public function incrementAccounts(): void
    {
        $this->increment('current_accounts');
    }

    /**
     * Décrémenter le nombre de comptes
     */
    public function decrementAccounts(): void
    {
        if ($this->current_accounts > 0) {
            $this->decrement('current_accounts');
        }
    }

    /**
     * Obtenir l'URL complète du serveur
     */
    public function getFullUrlAttribute(): string
    {
        $protocol = $this->use_ssl ? 'https' : 'http';
        $port = $this->port != 443 && $this->port != 80 ? ":{$this->port}" : '';
        return "{$protocol}://{$this->hostname}{$port}";
    }

    /**
     * Obtenir le label du type de serveur
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'pterodactyl' => 'Pterodactyl Panel',
            'cpanel' => 'cPanel/WHM',
            'plesk' => 'Plesk',
            'proxmox' => 'Proxmox VE',
            'docker' => 'Docker',
            'directadmin' => 'DirectAdmin',
            'virtualizor' => 'Virtualizor',
            'solusvm' => 'SolusVM',
            'custom' => 'Custom Module',
            default => ucfirst($this->type),
        };
    }
}
