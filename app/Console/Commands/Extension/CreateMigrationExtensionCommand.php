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

namespace App\Console\Commands\Extension;

use Illuminate\Console\Command;

class CreateMigrationExtensionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hostclient:create-migration-extension {--model=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration extension for the Hostclient.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folders = [base_path('modules'), base_path('addons')];
        $extensions = [];
        foreach ($folders as $folder) {
            $directories = \File::directories($folder);
            foreach ($directories as $directory) {
                $extensions[] = basename($folder).'/'.basename($directory).'/database/migrations';
            }
        }
        $extension = $this->choice('Which extension do you want to create a migration for?', $extensions);
        $name = $this->ask('What is the name of the migration?');
        if ($this->option('model') == 'true') {
            $model = $this->ask('What is the name of the model?');
            $this->call('make:model', [
                'name' => $model,
                '--path' => $extension,
            ]);
        }
        \Artisan::call('make:migration', [
            'name' => $name,
            '--path' => $extension,
        ]);
        $this->comment(\Artisan::output());
    }
}
