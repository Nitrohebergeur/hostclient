<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modèle utilisateur principal.
 *
 * @property int    $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $company
 * @property string|null $phone
 * @property string|null $website
 * @property string|null $avatar
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $postcode
 * @property string|null $city
 * @property string|null $state
 * @property string $country
 * @property string|null $vat_number
 * @property string $language
 * @property string $currency
 * @property float  $credit_balance
 * @property bool   $two_factor_enabled
 * @property array  $notification_preferences
 * @property string $status
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'company', 'phone', 'website',
        'avatar', 'address1', 'address2', 'postcode', 'city', 'state',
        'country', 'vat_number', 'language', 'currency', 'credit_balance',
        'two_factor_enabled', 'two_factor_secret', 'notification_preferences',
        'status',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at'         => 'datetime',
        'password'                  => 'hashed',
        'two_factor_enabled'        => 'boolean',
        'notification_preferences'  => 'array',
        'credit_balance'            => 'decimal:2',
    ];

    protected $attributes = [
        'country'  => 'FR',
        'language' => 'fr',
        'currency' => 'EUR',
        'credit_balance' => 0.00,
        'two_factor_enabled' => false,
        'status'   => 'active',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ── Accesseurs ─────────────────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0ea5e9&color=fff';
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
