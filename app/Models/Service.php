<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'server_id',
        'invoice_id',
        'name',
        'status',
        'billing_cycle',
        'activated_at',
        'next_due_date',
        'terminated_at',
        'price',
        'currency',
        'module_config',
        'custom_fields',
        'admin_notes',
        'client_notes',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'next_due_date' => 'datetime',
        'terminated_at' => 'datetime',
        'price' => 'decimal:2',
        'module_config' => 'array',
        'custom_fields' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function autoRenewal(): HasOne
    {
        return $this->hasOne(AutoRenewal::class);
    }

    /**
     * Calculer la prochaine date de facturation
     */
    public function calculateNextDueDate(?Carbon $from = null): Carbon
    {
        $date = $from ?? ($this->next_due_date ?? $this->activated_at ?? now());

        return match ($this->billing_cycle) {
            'hourly' => $date->copy()->addHour(),
            'monthly' => $date->copy()->addMonth(),
            'quarterly' => $date->copy()->addMonths(3),
            'semiannually' => $date->copy()->addMonths(6),
            'annually' => $date->copy()->addYear(),
            'biennially' => $date->copy()->addYears(2),
            default => $date->copy()->addMonth(),
        };
    }

    /**
     * Activer le service
     */
    public function activate(): void
    {
        $this->update([
            'status' => 'active',
            'activated_at' => now(),
            'next_due_date' => $this->calculateNextDueDate(now()),
        ]);
    }

    /**
     * Suspendre le service
     */
    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    /**
     * Réactiver le service suspendu
     */
    public function unsuspend(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Annuler le service
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Terminer le service
     */
    public function terminate(): void
    {
        $this->update([
            'status' => 'terminated',
            'terminated_at' => now(),
        ]);
    }

    /**
     * Vérifier si le service est actif
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Vérifier si le service est en retard de paiement
     */
    public function isOverdue(): bool
    {
        if (!$this->next_due_date) {
            return false;
        }

        return $this->next_due_date->isPast() && $this->status === 'active';
    }

    /**
     * Obtenir le label du statut
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => __('Pending'),
            'active' => __('Active'),
            'suspended' => __('Suspended'),
            'cancelled' => __('Cancelled'),
            'terminated' => __('Terminated'),
            default => ucfirst($this->status),
        };
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'active' => 'success',
            'suspended' => 'danger',
            'cancelled' => 'secondary',
            'terminated' => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Obtenir le label du cycle de facturation
     */
    public function getBillingCycleLabel(): string
    {
        return match ($this->billing_cycle) {
            'hourly' => __('Hourly'),
            'monthly' => __('Monthly'),
            'quarterly' => __('Quarterly'),
            'semiannually' => __('Semi-Annually'),
            'annually' => __('Annually'),
            'biennially' => __('Biennially'),
            default => ucfirst($this->billing_cycle),
        };
    }
}
