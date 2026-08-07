@extends('layouts.admin')

@section('title', 'Services')

@section('content')
    <x-page-header title="Services" subtitle="Gérez les services actifs de vos clients" />

    {{-- Statistiques --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-6);">
        <x-stat label="Total services" :value="$stats['total'] ?? 0" icon="server" color="primary" />
        <x-stat label="Actifs" :value="$stats['active'] ?? 0" icon="check-circle" color="success" />
        <x-stat label="En attente" :value="$stats['pending'] ?? 0" icon="clock" color="warning" />
        <x-stat label="Suspendus" :value="$stats['suspended'] ?? 0" icon="alert-triangle" color="danger" />
    </div>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" class="hc-filters">
            <div class="hc-filters-field">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Service, client..." class="hc-input">
            </div>
            <div class="hc-filters-field-fixed">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select">
                    <option value="">Tous</option>
                    <option value="active" @selected(request('status') === 'active')>Actifs</option>
                    <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>Suspendus</option>
                    <option value="terminated" @selected(request('status') === 'terminated')>Terminés</option>
                </select>
            </div>
            <div class="hc-filters-actions">
                <x-button type="submit" variant="primary">
                    <i data-lucide="filter" style="width: 14px; height: 14px;"></i>
                    Filtrer
                </x-button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.services.index') }}" class="hc-btn hc-btn-ghost">Réinitialiser</a>
                @endif
            </div>
        </form>
    </x-card>

    @if($services->count() === 0)
        <x-card>
            <x-empty-state title="Aucun service" description="Les services activés apparaîtront ici." icon="🖥️" />
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Service', 'Client', 'Produit', 'Prix', 'Cycle', 'Statut', 'Activé le', '']">
                @foreach($services as $service)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                                <div style="width: 32px; height: 32px; background: var(--hc-primary-50); color: var(--hc-primary); border-radius: var(--hc-radius); display: flex; align-items: center; justify-content: center;">
                                    <i data-lucide="server" style="width: 16px; height: 16px;"></i>
                                </div>
                                <a href="{{ route('admin.services.show', $service) }}" style="font-weight: 600; color: var(--hc-text); text-decoration: none;">{{ $service->name }}</a>
                            </div>
                        </td>
                        <td>
                            @if($service->user)
                                <div style="font-weight: 500;">{{ $service->user->first_name }} {{ $service->user->last_name }}</div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $service->user->email }}</div>
                            @else
                                <span style="color: var(--hc-text-muted);">—</span>
                            @endif
                        </td>
                        <td>{{ $service->product?->name ?? '—' }}</td>
                        <td><span style="font-weight: 600;">{{ number_format($service->price ?? 0, 2) }} €</span></td>
                        <td><span style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ ucfirst($service->billing_cycle ?? '—') }}</span></td>
                        <td>
                            <x-badge :variant="match($service->status) {
                                'active' => 'success',
                                'pending' => 'warning',
                                'suspended' => 'danger',
                                'terminated' => 'neutral',
                                default => 'neutral'
                            }">
                                {{ ucfirst($service->status ?? 'unknown') }}
                            </x-badge>
                        </td>
                        <td><span style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">{{ $service->activated_at?->format('d/m/Y') ?? '—' }}</span></td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.services.show', $service) }}" class="hc-btn hc-btn-ghost hc-btn-sm" title="Voir le détail">
                                <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        <div style="margin-top: var(--hc-space-6);">{{ $services->links() }}</div>
    @endif
@endsection