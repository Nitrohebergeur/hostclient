<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'number',
        'status',
        'issued_at',
        'due_at',
        'paid_at',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total',
        'currency',
        'billing_address',
        'notes',
        'admin_notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'billing_address' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Marquer la facture comme payée
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Vérifier si la facture est payée
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Vérifier si la facture est en retard
     */
    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->due_at->isPast();
    }

    /**
     * Calculer le total de la facture
     */
    public function calculateTotal(): void
    {
        $this->subtotal = $this->items->sum('total');
        $this->total = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->save();
    }

    /**
     * Générer un numéro de facture unique
     */
    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $lastInvoice = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastInvoice ? (int) substr($lastInvoice->number, -6) + 1 : 1;

        return sprintf('INV-%d-%06d', $year, $number);
    }
}
