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

namespace App\DTO\Admin\Dashboard\Earn;

class ServicesSubscriptionsCanvasDTO implements \App\Contracts\CanvasDTOInterface
{
    private int $services;

    private int $subscriptions;

    public function __construct(int $services, int $subscriptions)
    {
        $this->services = $services;
        $this->subscriptions = $subscriptions;
    }

    public function getLabels()
    {
        $total = $this->services;
        $percent = $total > 0 ? round(($this->services - $this->subscriptions) / $total * 100, 2) : 0;
        $percent2 = $total > 0 ? round($this->subscriptions / $total * 100, 2) : 0;

        return json_encode([[sprintf('Services (%s %%)', $percent), sprintf('Subscriptions (%s %%)', $percent2)]]);
    }

    public function getColors()
    {
        return json_encode(['#FF6384', '#36A2EB']);
    }

    public function isEmpty()
    {
        return count([$this->services, $this->subscriptions]) == 0;
    }

    public function getValues()
    {
        return json_encode([[$this->services, $this->subscriptions]]);
    }

    public function getTitles()
    {
        return json_encode(['']);
    }
}
