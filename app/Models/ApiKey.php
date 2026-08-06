<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'key',
        'permissions',
        'last_used_at',
        'expires_at',
        'is_active',
    ];

    protected $hidden = ['key'];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at'   => 'datetime',
        'is_active'    => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generate(): string
    {
        return 'hc_' . Str::random(40);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    public function markUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }
}
