<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = User::role('client')
            ->when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            }))
            ->when($request->status, fn($q, $s) => $q->where('is_active', $s === 'active'))
            ->withCount(['services', 'invoices'])
            ->latest()
            ->paginate(20);

        return view('admin.clients.index', compact('clients'));
    }

    public function show(User $client)
    {
        $client->load(['services.product', 'invoices', 'tickets', 'transactions']);

        $stats = [
            'services'   => $client->services()->count(),
            'invoices'   => $client->invoices()->count(),
            'tickets'    => $client->tickets()->count(),
            'total_paid' => $client->transactions()->where('type', 'payment')->where('status', 'completed')->sum('amount'),
        ];

        return view('admin.clients.show', compact('client', 'stats'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:8|confirmed',
            'phone'      => 'nullable|string|max:20',
            'company'    => 'nullable|string|max:255',
            'country'    => 'nullable|string|max:2',
        ]);

        $user = User::create([
            ...$validated,
            'password'       => Hash::make($validated['password']),
            'email_verified' => true,
            'is_active'      => true,
        ]);

        $user->assignRole('client');

        return redirect()->route('admin.clients.show', $user)
            ->with('success', 'Client créé avec succès.');
    }

    public function edit(User $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, User $client)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $client->id,
            'phone'       => 'nullable|string|max:20',
            'company'     => 'nullable|string|max:255',
            'country'     => 'nullable|string|max:2',
            'is_active'   => 'boolean',
        ]);

        $client->update($validated);

        return redirect()->route('admin.clients.show', $client)
            ->with('success', 'Client mis à jour.');
    }

    public function destroy(User $client)
    {
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé.');
    }
}
