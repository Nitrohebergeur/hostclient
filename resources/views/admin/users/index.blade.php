@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('content')

    <x-page-header title="Utilisateurs" subtitle="Gérez tous vos utilisateurs et leurs rôles">
        <x-slot:actions>
            <x-button :href="route('admin.users.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouvel utilisateur
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Stats --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background:var(--hc-bg-elevated); border:1px solid var(--hc-border); border-radius:0.75rem; padding:1.25rem; text-align:center;">
            <div style="font-size:1.75rem; font-weight:800; color:var(--hc-text);">{{ $stats['total'] }}</div>
            <div style="font-size:0.8rem; color:var(--hc-text-muted); margin-top:0.25rem;">Total</div>
        </div>
        <div style="background:var(--hc-bg-elevated); border:1px solid var(--hc-border); border-radius:0.75rem; padding:1.25rem; text-align:center;">
            <div style="font-size:1.75rem; font-weight:800; color:#2563eb;">{{ $stats['admins'] }}</div>
            <div style="font-size:0.8rem; color:var(--hc-text-muted); margin-top:0.25rem;">Admins</div>
        </div>
        <div style="background:var(--hc-bg-elevated); border:1px solid var(--hc-border); border-radius:0.75rem; padding:1.25rem; text-align:center;">
            <div style="font-size:1.75rem; font-weight:800; color:#7c3aed;">{{ $stats['clients'] }}</div>
            <div style="font-size:0.8rem; color:var(--hc-text-muted); margin-top:0.25rem;">Clients</div>
        </div>
        <div style="background:var(--hc-bg-elevated); border:1px solid var(--hc-border); border-radius:0.75rem; padding:1.25rem; text-align:center;">
            <div style="font-size:1.75rem; font-weight:800; color:#16a34a;">{{ $stats['active'] }}</div>
            <div style="font-size:0.8rem; color:var(--hc-text-muted); margin-top:0.25rem;">Actifs</div>
        </div>
    </div>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: 1.5rem;">
        <form method="GET" style="padding: 1rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 2; min-width: 180px;">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, entreprise..." class="hc-input">
            </div>
            <div style="min-width: 140px;">
                <label class="hc-label">Rôle</label>
                <select name="role" class="hc-select">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="min-width: 130px;">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select">
                    <option value="">Tous</option>
                    <option value="active"   @selected(request('status') === 'active')>Actifs</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactifs</option>
                </select>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <x-button type="submit" variant="primary">
                    <i data-lucide="filter" style="width:14px; height:14px;"></i>
                    Filtrer
                </x-button>
                @if(request('search') || request('role') || request('status'))
                    <a href="{{ route('admin.users.index') }}" class="hc-btn hc-btn-ghost">Réinitialiser</a>
                @endif
            </div>
        </form>
    </x-card>

    @if(session('success'))
        <x-alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="danger" style="margin-bottom:1rem;">{{ session('error') }}</x-alert>
    @endif

    @if($users->count() === 0)
        <x-card>
            <x-empty-state title="Aucun utilisateur trouvé" description="Essayez de modifier vos filtres ou créez un nouvel utilisateur." icon="👤">
                <x-button :href="route('admin.users.create')" variant="primary">
                    <i data-lucide="plus" style="width:14px; height:14px;"></i>
                    Nouvel utilisateur
                </x-button>
            </x-empty-state>
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Utilisateur', 'Email', 'Rôle', 'Services', 'Factures', 'Statut', 'Inscrit le', '']">
                @foreach($users as $user)
                    <tr>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" style="display:flex; align-items:center; gap:0.75rem; text-decoration:none; color:var(--hc-text);">
                                <div style="width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.875rem; flex-shrink:0;
                                    background: {{ $user->hasRole('admin') ? '#2563eb' : ($user->hasRole('support') ? '#7c3aed' : 'var(--hc-primary-50)') }};
                                    color: {{ $user->hasRole('admin') || $user->hasRole('support') ? 'white' : 'var(--hc-primary)' }};">
                                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;">{{ $user->first_name }} {{ $user->last_name }}</div>
                                    @if($user->company)
                                        <div style="font-size:0.75rem; color:var(--hc-text-muted);">{{ $user->company }}</div>
                                    @endif
                                </div>
                            </a>
                        </td>
                        <td>
                            <span style="font-family:monospace; font-size:0.8rem; color:var(--hc-text-muted);">{{ $user->email }}</span>
                        </td>
                        <td>
                            @foreach($user->roles as $role)
                                <span style="display:inline-block; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600; margin-right:3px;
                                    background: {{ $role->name === 'admin' ? '#dbeafe' : ($role->name === 'support' ? '#ede9fe' : '#f3f4f6') }};
                                    color: {{ $role->name === 'admin' ? '#1d4ed8' : ($role->name === 'support' ? '#6d28d9' : '#374151') }};">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                            @if($user->roles->isEmpty())
                                <span style="color:var(--hc-text-muted); font-size:0.8rem;">—</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight:600;">{{ $user->services_count ?? 0 }}</span>
                        </td>
                        <td>
                            <span style="font-weight:600;">{{ $user->invoices_count ?? 0 }}</span>
                        </td>
                        <td>
                            <span style="display:inline-block; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.75rem; font-weight:600;
                                background: {{ ($user->is_active ?? true) ? '#dcfce7' : '#f3f4f6' }};
                                color: {{ ($user->is_active ?? true) ? '#16a34a' : '#6b7280' }};">
                                {{ ($user->is_active ?? true) ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>
                            <span style="color:var(--hc-text-muted); font-size:0.8rem;">{{ $user->created_at?->format('d/m/Y') ?? '—' }}</span>
                        </td>
                        <td style="text-align:right; white-space:nowrap;">
                            <a href="{{ route('admin.users.show', $user) }}" class="hc-btn hc-btn-ghost hc-btn-sm" title="Voir le profil">
                                <i data-lucide="eye" style="width:14px; height:14px;"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="hc-btn hc-btn-ghost hc-btn-sm" title="Modifier">
                                <i data-lucide="edit" style="width:14px; height:14px;"></i>
                            </a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;" onsubmit="return confirm('Supprimer {{ $user->first_name }} {{ $user->last_name }} ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hc-btn hc-btn-ghost hc-btn-sm" style="color:var(--hc-danger);" title="Supprimer">
                                        <i data-lucide="trash-2" style="width:14px; height:14px;"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        <div style="margin-top:1.5rem;">
            {{ $users->links() }}
        </div>
    @endif
@endsection
