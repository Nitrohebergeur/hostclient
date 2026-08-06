<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware to share common data with all views.
 * Named HandleInertiaRequests for compatibility but works with Blade.
 */
class HandleInertiaRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Share flash messages and common data with all views
        if ($user = $request->user()) {
            view()->share('authUser', $user);
            view()->share('unreadNotifications', $user->unreadNotifications->count());
        }

        return $next($request);
    }
}
