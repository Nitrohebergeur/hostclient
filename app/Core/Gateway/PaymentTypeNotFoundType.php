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
 * Year: 2026
 */

namespace App\Core\Gateway;

use App\Abstracts\AbstractGatewayType;
use App\Abstracts\PaymentMethodSourceDTO;
use App\DTO\Core\Gateway\GatewayPayInvoiceResultDTO;
use App\DTO\Core\Gateway\GatewayUriDTO;
use App\Exceptions\WrongPaymentException;
use App\Models\Billing\Gateway;
use App\Models\Billing\Invoice;
use Illuminate\Http\Request;

class PaymentTypeNotFoundType extends AbstractGatewayType
{
    protected string $icon = 'bi bi-question-circle';

    protected string $image = 'none.png';

    public function __construct(string $uuid)
    {
        $this->name = $uuid;
        $this->uuid = $uuid;
    }

    public function createPayment(Invoice $invoice, Gateway $gateway, Request $request, GatewayUriDTO $dto)
    {
        throw new WrongPaymentException('Payment type not found');
    }

    public function processPayment(Invoice $invoice, Gateway $gateway, Request $request, GatewayUriDTO $dto)
    {
        throw new WrongPaymentException('Payment type not found');
    }

    public function payInvoice(Invoice $invoice, PaymentMethodSourceDTO $sourceDTO): GatewayPayInvoiceResultDTO
    {
        throw new WrongPaymentException('Payment type not found');
    }

    public function minimalAmount(): float
    {
        return 0;
    }
}
