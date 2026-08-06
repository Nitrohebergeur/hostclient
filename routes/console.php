<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled tasks
Schedule::command('invoices:generate')->dailyAt('06:00');
Schedule::command('services:suspend')->hourly();
Schedule::command('services:terminate')->dailyAt('07:00');
Schedule::command('backup:run')->dailyAt('02:00');
