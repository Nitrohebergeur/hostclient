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

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (setting('force_login_client') == 'true') {
            if (! $request->is($this->allowedRoutes())) {
                if (! Auth::check()) {
                    return redirect('/login');
                }
            }
        }

        return $next($request);
    }

    private function allowedRoutes()
    {
        return [
            'login',
            'register',
            'reset-password',
            'admin*',
            'verify-email',
            'forgot-password',
            'password/reset*',
            'password/email',
            'licensing/return',
            'gateways/**',
            'source/gateway/**',
        ];
    }
}
