<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogsActivity;
use Spatie\Activitylog\Traits\LogsActivity as LogsActivityTrait;

class Service extends Model
{
    use HasFactory, SoftDeletes, LogsActivityTrait;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'name',
        'identifier',
        'status',
        'price',
        'setup_fee',
        'billing_cycle',
        'next_due_date',
        'next_invoice_date',
        'auto_renew',
        'config',
        'notes',
        'activated_at',
        'suspended_at',
        'terminated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'setup_fee' => 'decimal:2',
        'auto_renew' => 'boolean',
        'config' => 'array',
        'next_due_date' => 'date',
        'next_invoice_date' => 'date',
        'activated_at' => 'datetime',
        'suspended_at' => 'datetime',
        'terminated_at' => 'datetime',
    ];

    protected static $logAttributes = ['status', 'price', 'next_due_date'];
    protected static $logName = 'service';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function history()
    {
        return $this->hasMany(ServiceHistory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeDueSoon($query, $days = 7)
    {
        return $query->where('next_due_date', '<=', now()->addDays($days));
    }

    // Methods
    public function activate(): void
    {
        $this->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);

        $this->addHistory('activated', 'Service has been activated');
    }

    public function suspend(string $reason = null): void
    {
        $this->update([
            'status' => 'suspended',
            'suspended_at' => now(),
        ]);

        $this->addHistory('suspended', $reason ?? 'Service has been suspended');
    }

    public function terminate(string $reason = null): void
    {
        $this->update([
            'status' => 'terminated',
            'terminated_at' => now(),
        ]);

        $this->addHistory('terminated', $reason ?? 'Service has been terminated');
    }

    public function addHistory(string $action, string $description = null, array $metadata = []): void
    {
        $this->history()->create([
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'user_id' => auth()->id(),
        ]);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isTerminated(): bool
    {
        return $this->status === 'terminated';
    }
}
