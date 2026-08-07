<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutoRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'user_id', 'enabled', 'payment_gateway',
        'days_before_renewal', 'retry_attempts', 'current_retries',
        'last_attempted_at', 'next_attempt_at',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'last_attempted_at' => 'datetime',
        'next_attempt_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function canRetry(): bool
    {
        return $this->current_retries < $this->retry_attempts;
    }

    public function incrementRetries(): void
    {
        $this->increment('current_retries');
        $this->update([
            'last_attempted_at' => now(),
            'next_attempt_at' => now()->addHours(24),
        ]);
    }

    public function resetRetries(): void
    {
        $this->update(['current_retries' => 0]);
    }
}
