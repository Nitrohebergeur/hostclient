@extends('layouts.admin')

@section('title', 'Nouveau rôle')
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.roles.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux rôles
        </a>
    </div>

    <x-page-header title="Nouveau rôle" />

    <x-card>
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <div style="margin-bottom: var(--hc-space-6);">
                <label class="hc-label">Nom du rôle</label>
                <input type="text" name="name" class="hc-input" placeholder="Ex: support" required>
            </div>

            <h3 style="font-size: var(--hc-text-lg); font-weight: 600; margin-bottom: var(--hc-space-4);">Permissions</h3>
            @forelse($permissions ?? [] as $group => $perms)
                <x-card header="{{ ucfirst($group) }}" style="margin-bottom: var(--hc-space-3);">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--hc-space-2);">
                        @foreach($perms as $perm)
                            <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}">
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
                <x-button type="submit" variant="primary">Créer le rôle</x-button>
            </div>
        </form>
    </x-card>
@endsection