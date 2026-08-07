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

namespace App\View\Components\Provisioning;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServiceDaysRemaining extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (request()->is(admin_prefix('*'))) {
            return view('admin.components.provisioning.service-days-remaining');
        }

        return view('components.provisioning.service-days-remaining');
    }
}
