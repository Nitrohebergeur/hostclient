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

namespace App\Console\Commands\Translations;

use App\Services\Core\LocaleService;
use Illuminate\Console\Command;

class ImportTranslationCommand extends Command
{
    protected $signature = 'translations:import {--locale=fr_FR}';

    protected $description = 'Import translations from github repository';

    public function handle(): void
    {
        try {
            $locale = $this->option('locale');
            LocaleService::downloadFiles($locale);
            $this->info('Translations imported successfully.');
        } catch (\Exception $e) {
            $this->error('Error importing translations: '.$e->getMessage());
        }
    }
}
