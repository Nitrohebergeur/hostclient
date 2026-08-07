@extends('layouts.admin')

@section('title', 'Permissions du rôle ' . $role->name)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.roles.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux rôles
        </a>
    </div>

    <x-page-header title="Permissions du rôle : {{ $role->name }}" />

    <x-card>
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf
            @method('PUT')

            @forelse($permissions ?? [] as $group => $perms)
                <x-card header="{{ ucfirst($group) }}" style="margin-bottom: var(--hc-space-3);">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--hc-space-2);">
                        @foreach($perms as $perm)
                            <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" @checked(in_array($perm->name, $rolePermissions ?? []))>
                                <span style="font-size: var(--hc-text-sm); font-family: var(--hc-font-mono);">{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </x-card>
            @empty
                <p style="color: var(--hc-text-muted);">Aucune permission définie.</p>
            @endforelse

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; margin-top: var(--hc-space-4); padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.roles.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Enregistrer</x-button>
            </div>
        </form>
    </x-card>
@endsection