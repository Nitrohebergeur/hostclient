@extends('layouts.admin')

@section('title', 'Rôles & permissions')
@section('content')
    <x-page-header title="Rôles & permissions">
        <x-slot:actions>
            <x-button :href="route('admin.roles.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouveau rôle
            </x-button>
        </x-slot:actions>
    </x-page-header>

    @if(($roles ?? collect())->count() === 0)
        <x-card>
            <x-empty-state title="Aucun rôle" icon="🔑" />
        </x-card>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--hc-space-4);">
            @foreach($roles as $role)
                <x-card>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--hc-space-3);">
                        <h3 style="font-size: var(--hc-text-base); font-weight: 600; margin: 0;">{{ ucfirst($role->name) }}</h3>
                        <x-badge variant="info">{{ $role->users_count ?? 0 }} utilisateurs</x-badge>
                    </div>

                    <div style="font-size: var(--hc-text-sm); color: var(--hc-text-muted); margin-bottom: var(--hc-space-4);">
                        {{ $role->permissions->count() }} permission(s)
                    </div>

                    <div style="display: flex; gap: var(--hc-space-2);">
                        <x-button :href="route('admin.roles.edit', $role)" variant="secondary" size="sm" style="flex: 1;">
                            Permissions
                        </x-button>
                        @if(!in_array($role->name, ['admin', 'client']))
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Supprimer ce rôle ?')">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="ghost" size="sm" style="color: var(--hc-danger);">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                </x-button>
                            </form>
                        @endif
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
@endsection