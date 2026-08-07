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

namespace App\Contracts\Billing;

use App\Models\Billing\InvoiceItem;

interface InvoiceItemInterface
{
    public function uuid(): string;

    public function type(): string|array;

    /**
     * Get the related type for the invoice item
     */
    public function relatedType(InvoiceItem $item): mixed;

    /**
     * Try to deliver the invoice item
     */
    public function tryDeliver(InvoiceItem $item): bool;
}
