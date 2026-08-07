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

use App\Providers\AppServiceProvider;
use Illuminate\Console\Command;

class HostclientUpdateVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hostclient:update-version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the version of the Hostclient application.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating the version of the Hostclient application...');
        file_put_contents(storage_path('version'), 'version='.AppServiceProvider::VERSION.';time='.time());
        $this->info('The version of the Hostclient application has been updated.');
    }
}
