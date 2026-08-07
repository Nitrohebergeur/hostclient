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

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showForm(Request $request)
    {
        if (app('extension')->extensionIsEnabled('socialauth')) {
            $providers = \App\Addons\SocialAuth\Models\ProviderEntity::where('enabled', true)->get();
        } else {
            $providers = collect([]);
        }

        return view('front.auth.login', [
            'providers' => $providers,
            'redirect' => $request->query('redirect'),
            'email' => $request->query('email'),
        ]);
    }
}
