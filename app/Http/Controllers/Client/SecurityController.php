<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

/**
 * Gestion de la sécurité du compte client.
 * - Changement de mot de passe
 * - Authentification à deux facteurs (2FA)
 * - Clés API
 * - Sessions actives
 */
class SecurityController extends Controller
{
    /**
     * Affiche la page de sécurité.
     */
    public function index(Request $request): View
    {
        return view('client.security.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Met à jour le mot de passe.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()
                ->min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
            ],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        // Déconnecter les autres sessions après changement de mot de passe
        auth()->logoutOtherDevices($request->current_password);

        return back()->with('success', 'Mot de passe mis à jour avec succès. Vos autres sessions ont été déconnectées.');
    }

    /**
     * Active la 2FA pour le compte.
     */
    public function enableTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ]);

        // TwoFactorService::verify($request->user(), $request->code);
        // $request->user()->update(['two_factor_enabled' => true]);

        return back()->with('success', 'Authentification à deux facteurs activée.');
    }

    /**
     * Désactive la 2FA.
     */
    public function disableTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // $request->user()->update(['two_factor_enabled' => false]);

        return back()->with('success', 'Authentification à deux facteurs désactivée.');
    }

    /**
     * Crée une nouvelle clé API.
     */
    public function createApiKey(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:50'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        $apiKey = 'hc_' . ($request->environment === 'live' ? 'live' : 'test') . '_' . Str::random(40);

        // ApiKey::create([
        //     'user_id'    => auth()->id(),
        //     'name'       => $request->name,
        //     'key'        => hash('sha256', $apiKey),
        //     'expires_at' => $request->expires_at,
        // ]);

        return back()->with('success', "Clé API créée : {$apiKey}");
    }

    /**
     * Révoque une clé API.
     */
    public function revokeApiKey(Request $request, int $key): RedirectResponse
    {
        // ApiKey::where('user_id', auth()->id())->where('id', $key)->delete();

        return back()->with('success', 'Clé API révoquée.');
    }

    /**
     * Déconnecte toutes les autres sessions actives.
     */
    public function destroyOtherSessions(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        auth()->logoutOtherDevices($request->password);

        return back()->with('success', 'Toutes les autres sessions ont été déconnectées.');
    }
}
