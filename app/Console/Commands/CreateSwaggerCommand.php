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

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateSwaggerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hostclient:create-swagger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create a swagger file for the Hostclient project.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app('extension')->autoload(app(), false);
        Artisan::call('l5-swagger:generate');
    }
}
