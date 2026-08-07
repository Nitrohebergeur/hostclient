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

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Admin\Permission;
use App\Services\Core\LocaleService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function storeTranslations(Request $request)
    {
        staff_aborts_permission(Permission::MANAGE_PERSONALIZATION);
        $validated = $request->validate([
            'translations' => 'required|array',
            'model' => 'required|string',
            'model_id' => 'required|integer',
        ]);
        LocaleService::storeTranslations($validated['model'], $validated['model_id'], $validated['translations']);

        return back()->with('success', __('admin.locales.translations_saved'));
    }

    public function storeSettingsTranslations(Request $request)
    {
        staff_aborts_permission(Permission::MANAGE_PERSONALIZATION);
        $validated = $request->validate([
            'translations' => 'required|array',
        ]);
        LocaleService::storeSettingsTranslations($validated['translations']);

        return back()->with('success', __('admin.locales.translations_saved'));
    }
}
