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

namespace App\Exceptions;

use App\Models\Provisioning\Service;

class ServiceDeliveryException extends \Exception
{
    private Service $service;

    public function __construct(string $message, Service $service, int $code)
    {
        parent::__construct('Service delivery Exception : '.$message, $code, null);
        $this->service = $service;
    }
}
