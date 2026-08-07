<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

/**
 * Gestion du profil du client.
 */
class ProfileController extends Controller
{
    /**
     * Affiche la page profil.
     */
    public function index(Request $request): View
    {
        return view('client.profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Met à jour les informations personnelles.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'phone'      => ['nullable', 'string', 'max:20'],
            'company'    => ['nullable', 'string', 'max:100'],
            'website'    => ['nullable', 'url', 'max:255'],
            'avatar'     => ['nullable', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            // Supprime l'ancien avatar si existant
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        // Si l'email a changé, renvoyer une vérification
        if ($user->wasChanged('email')) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Met à jour l'adresse de facturation.
     */
    public function updateAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'address1'   => ['required', 'string', 'max:255'],
            'address2'   => ['nullable', 'string', 'max:255'],
            'postcode'   => ['required', 'string', 'max:10'],
            'city'       => ['required', 'string', 'max:100'],
            'state'      => ['nullable', 'string', 'max:100'],
            'country'    => ['required', 'string', 'size:2'],
            'vat_number' => ['nullable', 'string', 'max:30'],
        ]);

        $request->user()->update($validated);

        return back()->with('success', 'Adresse mise à jour avec succès.');
    }

    /**
     * Met à jour les préférences (langue, devise, notifications).
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language'  => ['required', 'string', 'in:fr,en,de,es,it,pt'],
            'currency'  => ['required', 'string', 'in:EUR,USD,GBP,CHF,CAD'],
            'notifications' => ['nullable', 'array'],
        ]);

        $request->user()->update([
            'language' => $validated['language'],
            'currency' => $validated['currency'],
            'notification_preferences' => $validated['notifications'] ?? [],
        ]);

        return back()->with('success', 'Préférences enregistrées.');
    }
}
