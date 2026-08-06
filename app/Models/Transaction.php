<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'invoice_id',
        'payment_gateway_id',
        'type',
        'status',
        'amount',
        'fee',
        'currency',
        'payment_method',
        'external_id',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'fee'      => 'decimal:2',
        'metadata' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePayments($query)
    {
        return $query->where('type', 'payment');
    }

    public static function generateId(): string
    {
        return 'TXN-' . strtoupper(uniqid());
    }
}
