<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

/**
 * Gestion de l'inscription des nouveaux utilisateurs.
 */
class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'company'  => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Password::defaults()->min(8)->letters()->numbers()],
            'terms'    => ['accepted'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'company'  => $request->company,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('client');

        Auth::login($user);

        return redirect('/client/dashboard');
    }
}
