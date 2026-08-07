<?php

/*
 * This file is part of the Hostclient project.
 * It is the property of the Hostclient association.
 *
 * Personal and non-commercial use of this source code is permitted.
 * However, any use in a project that generates profit (directly or indirectly),
 * or any reuse for commercial purposes, requires prior authorization from Hostclient.
 *
 * To request permission or for more information, please contact our support:
 * https://Hostclient.com/client/support
 *
 * Learn more about Hostclient License at:
 * https://Hostclient.com/eula
 *
 * Year: 2025
 */

namespace App\Events\Core;

use App\Models\Billing\Invoice;
use App\Models\Store\Basket\Basket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckoutCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Basket $basket;

    public Invoice $invoice;

    public function __construct(Basket $basket, Invoice $invoice)
    {
        $this->basket = $basket;
        $this->invoice = $invoice;
    }
}
