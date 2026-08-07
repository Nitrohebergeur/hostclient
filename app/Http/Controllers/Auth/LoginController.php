<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Gestion de la connexion des utilisateurs.
 */
class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirection selon le rôle
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/client/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('Ces identifiants ne correspondent pas à nos enregistrements.'),
        ]);
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
