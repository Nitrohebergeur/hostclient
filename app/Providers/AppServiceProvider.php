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

namespace App\Providers;

use App\Core\License\LicenseGateway;
use App\Services\Core\SeoService;
use App\View\Components\BadgeStateComponant;
use App\View\Components\Provisioning\ServiceDaysRemaining;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    const VERSION = '2.16';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('license', LicenseGateway::class);
        $this->app->singleton('seo', SeoService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        date_default_timezone_set('Europe/Paris');

        if ($this->app->environment('production') && config('app.debug')) {
            config(['app.debug' => false]);
            logger()->warning('APP_DEBUG was true in production environment - forced to false at boot. Operator must set APP_DEBUG=false explicitly in .env to silence this.');
        }

        if (env('APP_FORCE_HTTPS', $this->app->environment('production'))) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Builder::macro('whereLike', function (string $attribute, string $searchTerm) {
            return $this->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
        });
        Paginator::defaultView('shared.pagination.default');
        Blade::component('badge-state', BadgeStateComponant::class);
        Blade::component('service-days-remaining', ServiceDaysRemaining::class);
        \View::share('Hostclient_version', self::VERSION);
        Carbon::setLocale(setting('app.locale', 'fr_FR'));
    }
}
