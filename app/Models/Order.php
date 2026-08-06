<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'tax',
        'discount',
        'total',
        'currency',
        'payment_method',
        'payment_id',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Invoice::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Methods
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Create services for each order item
        foreach ($this->items as $item) {
            $this->createServiceFromItem($item);
        }
    }

    protected function createServiceFromItem(OrderItem $item): Service
    {
        return Service::create([
            'user_id' => $this->user_id,
            'product_id' => $item->product_id,
            'order_id' => $this->id,
            'name' => $item->name,
            'status' => 'pending',
            'price' => $item->unit_price,
            'setup_fee' => $item->setup_fee,
            'billing_cycle' => $item->billing_cycle,
            'next_due_date' => now()->addMonth(),
            'next_invoice_date' => now()->addMonth()->subDays(7),
            'config' => $item->config,
        ]);
    }

    public function calculateTotal(): void
    {
        $subtotal = $this->items->sum('total');
        $tax = $subtotal * (config('hostclient.tax_rate', 0) / 100);
        $total = $subtotal + $tax - $this->discount;

        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => max(0, $total),
        ]);
    }

    public static function generateOrderNumber(): string
    {
        $prefix = config('hostclient.order_prefix', 'ORD-');
        $number = str_pad(self::max('id') + 1, 6, '0', STR_PAD_LEFT);
        return $prefix . date('Ymd') . '-' . $number;
    }
}
