<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'order_id',
        'status',
        'issue_date',
        'due_date',
        'paid_date',
        'subtotal',
        'tax',
        'tax_rate',
        'discount',
        'total',
        'amount_paid',
        'balance',
        'currency',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['unpaid', 'partially_paid']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'unpaid')
            ->where('due_date', '<', now());
    }

    // Methods
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
            'amount_paid' => $this->total,
            'balance' => 0,
        ]);
    }

    public function addPayment(float $amount): void
    {
        $newAmountPaid = $this->amount_paid + $amount;
        $newBalance = $this->total - $newAmountPaid;

        $status = $newBalance <= 0 ? 'paid' : 'partially_paid';

        $this->update([
            'amount_paid' => $newAmountPaid,
            'balance' => max(0, $newBalance),
            'status' => $status,
            'paid_date' => $status === 'paid' ? now() : null,
        ]);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'unpaid' && $this->due_date < now();
    }

    public function generatePDF()
    {
        // This will be implemented with DomPDF
        return null;
    }
}
