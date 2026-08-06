<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'description',
        'quantity',
        'unit_price',
        'setup_fee',
        'total',
        'billing_cycle',
        'config',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'setup_fee'  => 'decimal:2',
        'total'      => 'decimal:2',
        'config'     => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
