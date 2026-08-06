<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(fn($p) => explode('.', $p->name)[0]);

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle créé.');
    }

    public function edit(Role $role)
    {
        $permissions        = Permission::all()->groupBy(fn($p) => explode('.', $p->name)[0]);
        $rolePermissions    = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle mis à jour.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['admin', 'client'])) {
            return back()->with('error', 'Ce rôle système ne peut pas être supprimé.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle supprimé.');
    }
}
