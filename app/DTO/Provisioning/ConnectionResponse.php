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

use GuzzleHttp\Psr7\Response;

class ConnectionResponse
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function toString(): string
    {
        return $this->response->getBody()->__toString();
    }

    public function successful(): bool
    {
        return $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() < 300;
    }

    public function status(): int
    {
        return $this->response->getStatusCode();
    }

    public function toJson(): array
    {
        return json_decode($this->toString(), true);
    }
}
