<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index');
    }

    public function update(Request $request): RedirectResponse
    {
        // Sauvegarder dans la table settings (clé/valeur)
        // ou directement dans le fichier .env
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            // Setting::set($key, $value);
        }
        return back()->with('success', 'Paramètres enregistrés.');
    }
}
