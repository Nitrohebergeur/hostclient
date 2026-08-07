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

namespace App\DTO\Provisioning;

use App\Models\Provisioning\Service;
use Carbon\Carbon;

class ServiceStateChangeDTO
{
    public Service $service;

    public bool $success;

    public string $message;

    public Carbon $created_at;

    public array $data = [];

    public function __construct(Service $service, bool $success, string $message, array $data = [])
    {
        $this->service = $service;
        $this->success = $success;
        $this->message = $message;
        $this->created_at = Carbon::now();
        $this->data = $data;
    }
}
