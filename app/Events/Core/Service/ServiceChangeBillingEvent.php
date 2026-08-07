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

namespace App\Events\Core\Service;

use App\Models\Provisioning\Service;

class ServiceChangeBillingEvent extends ServiceEvent
{
    public string $billing;

    public function __construct(Service $service, string $billing)
    {
        parent::__construct($service);
        $this->billing = $billing;
    }
}
