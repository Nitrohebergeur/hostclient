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

namespace App\DTO\Core\Gateway;

use App\Models\Billing\Gateway;
use App\Models\Billing\Invoice;

class GatewayUriDTO
{
    public Gateway $gateway;

    public string $cancelUri;

    public string $returnUri;

    public string $notificationUri;

    public function __construct(Gateway $gateway, Invoice $invoice)
    {
        $this->gateway = $gateway;
        $this->cancelUri = route('gateways.cancel', ['gateway' => $gateway->uuid, 'invoice' => $invoice]);
        $this->returnUri = route('gateways.return', ['gateway' => $gateway->uuid, 'invoice' => $invoice]);
        $this->notificationUri = route('gateways.notification', ['gateway' => $gateway->uuid]);
    }
}
