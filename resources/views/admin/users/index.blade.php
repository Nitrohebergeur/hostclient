@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('content')
    <x-page-header title="Utilisateurs">
        <x-slot:actions>
            <x-button :href="route('admin.users.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouvel utilisateur
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" style="padding: var(--hc-space-4); display: flex; gap: var(--hc-space-3); flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou email..." class="hc-input">
            </div>
            <x-button type="submit" variant="primary">Filtrer</x-button>
        </form>
    </x-card>

    @if(($users ?? collect())->count() === 0)
        <x-card>
            <x-empty-state title="Aucun utilisateur" icon="👤">
                <x-button :href="route('admin.users.create')" variant="primary">Créer</x-button>
            </x-empty-state>
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Utilisateur', 'Email', 'Rôles', 'Statut', 'Inscrit le', '']">
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                                <div style="width: 36px; height: 36px; background: var(--hc-primary-50); color: var(--hc-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <div style="font-weight: 600;">{{ $user->first_name }} {{ $user->last_name }}</div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <x-badge :variant="$role->name === 'admin' ? 'warning' : 'info'" style="margin-right: 4px;">
                                    {{ ucfirst($role->name) }}
                                </x-badge>
                            @endforeach
                        </td>
                        <td>
                            <x-badge :variant="($user->is_active ?? true) ? 'success' : 'neutral'">
                                {{ ($user->is_active ?? true) ? 'Actif' : 'Inactif' }}
                            </x-badge>
                        </td>
                        <td>{{ $user->created_at?->format('d/m/Y') }}</td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.users.edit', $user) }}" class="hc-btn hc-btn-ghost hc-btn-sm">
                                <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                            </a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hc-btn hc-btn-ghost hc-btn-sm" style="color: var(--hc-danger);">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        <div style="margin-top: var(--hc-space-6);">
            {{ $users->links() }}
        </div>
    @endif
@endsection