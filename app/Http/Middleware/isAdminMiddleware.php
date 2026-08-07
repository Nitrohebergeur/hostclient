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

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('admin')->check()) {
            return $request->expectsJson()
                ? abort(401, 'Unauthorized')
                : redirect()->guest(route('admin.login'));
        }
        if (! Auth::guard('admin')->user()->isActive()) {
            Auth::guard('admin')->logout();
            \Session::flash('error', 'Your account is not active');

            return $request->expectsJson()
                ? abort(401, 'Unauthorized')
                : redirect()->guest(route('admin.login'));
        }

        return $next($request);
    }
}
