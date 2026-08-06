<?php

namespace App\Providers;

use App\Models\ApiKey;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use App\Models\Ticket;
use App\Policies\ApiKeyPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\OrderPolicy;
use App\Policies\ServicePolicy;
use App\Policies\TicketPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Service::class => ServicePolicy::class,
        Invoice::class => InvoicePolicy::class,
        Ticket::class  => TicketPolicy::class,
        Order::class   => OrderPolicy::class,
        ApiKey::class  => ApiKeyPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Admin can do everything
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });
    }
}
