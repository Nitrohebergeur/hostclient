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

namespace App\Listeners\Core;

use App\Models\Admin\Setting;
use Illuminate\Console\Events\ScheduledTaskStarting;

class LastCronRunSaved
{
    public function handle(ScheduledTaskStarting $task): void
    {
        Setting::updateSettings(['app_cron_last_run' => now()], null, false);
    }
}
