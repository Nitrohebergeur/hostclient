@extends('layouts.admin')

@section('title', 'Clients')

@section('content')
    <x-page-header title="Clients">
        <x-slot:actions>
            <x-button :href="route('admin.clients.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouveau client
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" style="padding: var(--hc-space-4); display: flex; gap: var(--hc-space-3); flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email..." class="hc-input">
            </div>
            <div style="min-width: 160px;">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select">
                    <option value="">Tous</option>
                    <option value="active" @selected(request('status') === 'active')>Actif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactif</option>
                </select>
            </div>
            <x-button type="submit" variant="primary">Filtrer</x-button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.clients.index') }}" class="hc-btn hc-btn-ghost">Réinitialiser</a>
            @endif
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
                <x-button :href="route('admin.clients.create')" variant="primary">Nouveau client</x-button>
            </x-empty-state>
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Client', 'Email', 'Services', 'Factures', 'Statut', 'Inscrit le', '']">
                @foreach($clients as $client)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                                <div style="width: 36px; height: 36px; background: var(--hc-primary-50); color: var(--hc-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                    {{ strtoupper(substr($client->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $client->first_name }} {{ $client->last_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->services_count ?? 0 }}</td>
                        <td>{{ $client->invoices_count ?? 0 }}</td>
                        <td>
                            <x-badge :variant="($client->is_active ?? true) ? 'success' : 'neutral'">
                                {{ ($client->is_active ?? true) ? 'Actif' : 'Inactif' }}
                            </x-badge>
                        </td>
                        <td>{{ $client->created_at?->format('d/m/Y') ?? '—' }}</td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.clients.show', $client) }}" class="hc-btn hc-btn-ghost hc-btn-sm">Voir</a>
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