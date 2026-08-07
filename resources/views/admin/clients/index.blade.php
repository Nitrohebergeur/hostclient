@extends('layouts.admin')

@section('title', 'Clients')

@section('content')
    <x-page-header title="Clients" subtitle="Gérez vos clients et leurs informations">
        <x-slot:actions>
            <x-button :href="route('admin.clients.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouveau client
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Statistiques rapides --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-6);">
        <x-stat label="Total clients" :value="$stats['total'] ?? \App\Models\User::count()" icon="users" color="primary" />
        <x-stat label="Actifs" :value="$stats['active'] ?? 0" icon="user-check" color="success" />
        <x-stat label="Avec services" :value="$stats['with_services'] ?? 0" icon="server" color="info" />
        <x-stat label="Nouveaux (30j)" :value="$stats['new_30d'] ?? 0" icon="user-plus" color="warning" />
    </div>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" class="hc-filters">
            <div class="hc-filters-field">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email..." class="hc-input">
            </div>
            <div class="hc-filters-field-fixed">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select">
                    <option value="">Tous</option>
                    <option value="active" @selected(request('status') === 'active')>Actifs</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactifs</option>
                </select>
            </div>
            <div class="hc-filters-actions">
                <x-button type="submit" variant="primary">
                    <i data-lucide="filter" style="width: 14px; height: 14px;"></i>
                    Filtrer
                </x-button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.clients.index') }}" class="hc-btn hc-btn-ghost">Réinitialiser</a>
                @endif
            </div>
        </form>
    </x-card>

    @if(session('success')) <x-alert type="success">{{ session('success') }}</x-alert> @endif

    @if($clients->count() === 0)
        <x-card>
            <x-empty-state
                title="Aucun client"
                description="Commencez par créer votre premier client."
                icon="👥"
            >
                <x-button :href="route('admin.clients.create')" variant="primary">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                    Nouveau client
                </x-button>
            </x-empty-state>
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Client', 'Email', 'Services', 'Factures', 'Statut', 'Inscrit le', '']">
                @foreach($clients as $client)
                    <tr>
                        <td>
                            <a href="{{ route('admin.clients.show', $client) }}" style="display: flex; align-items: center; gap: var(--hc-space-3); text-decoration: none; color: var(--hc-text);">
                                <div class="hc-avatar hc-avatar-primary">
                                    {{ strtoupper(substr($client->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($client->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $client->first_name }} {{ $client->last_name }}</div>
                                    @if($client->company)
                                        <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $client->company }}</div>
                                    @endif
                                </div>
                            </a>
                        </td>
                        <td>
                            <span style="font-family: var(--hc-font-mono); font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $client->email }}</span>
                        </td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                <span style="font-weight: 600;">{{ $client->services_count ?? 0 }}</span>
                                @if(($client->active_services_count ?? 0) > 0)
                                    <span style="font-size: var(--hc-text-xs); color: var(--hc-success);">({{ $client->active_services_count }} actifs)</span>
                                @endif
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 600;">{{ $client->invoices_count ?? 0 }}</span>
                            @if(($client->unpaid_invoices_count ?? 0) > 0)
                                <x-badge variant="danger" style="margin-left: 6px;">{{ $client->unpaid_invoices_count }} impayées</x-badge>
                            @endif
                        </td>
                        <td>
                            <x-badge :variant="($client->is_active ?? true) ? 'success' : 'neutral'">
                                {{ ($client->is_active ?? true) ? 'Actif' : 'Inactif' }}
                            </x-badge>
                        </td>
                        <td>
                            <span style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">{{ $client->created_at?->format('d/m/Y') ?? '—' }}</span>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.clients.show', $client) }}" class="hc-btn hc-btn-ghost hc-btn-sm" title="Voir le détail">
                                <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        <div style="margin-top: var(--hc-space-6);">
            {{ $clients->links() }}
        </div>
    @endif
@endsection