<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')
            ->withCount(['services', 'invoices', 'tickets'])
            ->when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name',  'like', "%{$s}%")
                  ->orWhere('email',      'like', "%{$s}%")
                  ->orWhere('company',    'like', "%{$s}%");
            }))
            ->when($request->role,   fn($q, $r) => $q->role($r))
            ->when($request->status, fn($q, $s) => $q->where('is_active', $s === 'active'))
            ->latest();

        $users = $query->paginate(20)->withQueryString();

        $stats = [
            'total'        => User::count(),
            'admins'       => User::role('admin')->count(),
            'clients'      => User::role('client')->count(),
            'active'       => User::where('is_active', true)->count(),
        ];

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'stats', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8|confirmed',
            'role'        => 'required|exists:roles,name',
            'phone'       => 'nullable|string|max:50',
            'company'     => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:255',
            'city'        => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country'     => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'phone'          => $validated['phone'] ?? null,
            'company'        => $validated['company'] ?? null,
            'address'        => $validated['address'] ?? null,
            'city'           => $validated['city'] ?? null,
            'postal_code'    => $validated['postal_code'] ?? null,
            'country'        => $validated['country'] ?? null,
            'email_verified' => true,
            'is_active'      => $request->boolean('is_active', true),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Utilisateur {$user->full_name} créé avec le rôle « {$validated['role']} ».");
    }

    public function show(User $user)
    {
        $user->load(['roles', 'services.product', 'invoices', 'tickets', 'transactions']);

        $stats = [
            'services'   => $user->services()->count(),
            'invoices'   => $user->invoices()->count(),
            'tickets'    => $user->tickets()->count(),
            'total_paid' => $user->transactions()
                ->where('type', 'payment')
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'role'         => 'required|exists:roles,name',
            'phone'        => 'nullable|string|max:50',
            'company'      => 'nullable|string|max:255',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:100',
            'postal_code'  => 'nullable|string|max:20',
            'country'      => 'nullable|string|max:100',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'first_name'  => $validated['first_name'],
            'last_name'   => $validated['last_name'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'] ?? null,
            'company'     => $validated['company'] ?? null,
            'address'     => $validated['address'] ?? null,
            'city'        => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'country'     => $validated['country'] ?? null,
            'is_active'   => $request->boolean('is_active', $user->is_active),
        ];

        if (!empty($validated['new_password'])) {
            $data['password'] = Hash::make($validated['new_password']);
        }

        $user->update($data);

        // Ne pas laisser l'admin changer son propre rôle
        if ($user->id !== auth()->id()) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Utilisateur {$user->full_name} mis à jour.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $name = $user->full_name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur {$name} supprimé.");
    }
}
