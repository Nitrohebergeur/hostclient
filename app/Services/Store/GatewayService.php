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

namespace App\Services\Store;

use App\Models\Billing\Gateway;
use Illuminate\Support\Facades\Cache;

class GatewayService
{
    /**
     * Get available gateways
     * 0 to get only balance gateways
     * Any other number to get all gateways with amount
     *
     * @return array|void
     */
    public static function getAvailable()
    {
        if (! is_installed()) {
            return [];
        }

        return Cache::remember('gateways', 60 * 60 * 24, function () {
            return Gateway::getAvailable()->get();
        });
    }

    public static function forgotAvailable()
    {
        Cache::forget('gateways');
    }
}
