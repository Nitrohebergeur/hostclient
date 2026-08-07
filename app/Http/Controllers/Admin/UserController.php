<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'unique:users,email,' . $user->id],
            'status'  => ['required', 'in:active,suspended,banned'],
        ]);
        $user->update($validated);
        return back()->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }

    public function suspend(User $user): RedirectResponse
    {
        $user->update(['status' => 'suspended']);
        return back()->with('success', "Compte de {$user->name} suspendu.");
    }

    public function ban(User $user): RedirectResponse
    {
        $user->update(['status' => 'banned']);
        return back()->with('success', "Compte de {$user->name} banni.");
    }

    public function impersonate(User $user): RedirectResponse
    {
        session(['impersonate' => $user->id]);
        auth()->loginUsingId($user->id);
        return redirect('/client/dashboard')->with('info', "Vous êtes connecté en tant que {$user->name}.");
    }
}
