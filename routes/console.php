<?php

use Illuminate\Support\Facades\Schedule;

// Renouvellements quotidiens — minuit
Schedule::command('renewals:process --reminders --suspend --terminate')
    ->dailyAt('00:05')
    ->withoutOverlapping()
    ->runInBackground();

// Mise à jour des taux de change — tous les jours à 6h
Schedule::command('currencies:update-rates')
    ->dailyAt('06:00')
    ->withoutOverlapping();

// Auto-update — selon le paramètre configuré
Schedule::call(function () {
    $interval = \App\Models\SystemSetting::get('auto_update_check_interval', 'daily');
    $enabled  = \App\Models\SystemSetting::get('auto_update_enabled', false);
    if ($enabled) {
        app(\App\Services\AutoUpdateService::class)->checkAndUpdate();
    }
})->when(fn() => \App\Models\SystemSetting::get('auto_update_enabled', false))
  ->dailyAt('03:00');

// Nettoyage des anciens logs d'auto-update (> 30 jours)
Schedule::call(fn() =>
    \App\Models\AutoUpdate::where('created_at', '<', now()->subDays(30))->delete()
)->weekly();
