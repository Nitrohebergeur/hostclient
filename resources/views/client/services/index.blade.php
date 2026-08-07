@extends('layouts.client')

@section('title', 'Mes services')
@section('subtitle', 'Gérez vos services actifs')

@section('content')
    <x-page-header title="Mes services">
        <x-slot:actions>
            <x-button :href="route('store.index')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Commander
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" style="padding: var(--hc-space-4); display: flex; gap: var(--hc-space-3); flex-wrap: wrap;">
            <div style="min-width: 200px;">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="active" @selected(request('status') === 'active')>Actif</option>
                    <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>Suspendu</option>
                    <option value="terminated" @selected(request('status') === 'terminated')>Résilié</option>
                </select>
            </div>
        </form>
    </x-card>

    @if($services->count())
        <div class="hc-grid hc-grid-3">
            @foreach($services as $service)
                <x-card padding="false">
                    <div style="padding: var(--hc-space-5);">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--hc-space-3);">
                            <x-badge :variant="match($service->status) {
                                'active' => 'success',
                                'pending' => 'warning',
                                'suspended' => 'danger',
                                'terminated' => 'neutral',
                                default => 'neutral'
                            }">{{ ucfirst($service->status) }}</x-badge>
                            <span style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $service->product?->name ?? '—' }}</span>
                        </div>

                        <h3 style="font-size: var(--hc-text-lg); font-weight: 600; margin-bottom: var(--hc-space-2);">{{ $service->name }}</h3>

                        @if($service->identifier)
                            <p style="font-size: var(--hc-text-sm); color: var(--hc-text-muted); margin-bottom: var(--hc-space-4);">
                                <i data-lucide="hash" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i>
                                {{ $service->identifier }}
                            </p>
                        @endif

                        <div style="padding: var(--hc-space-3) 0; border-top: 1px solid var(--hc-border); border-bottom: 1px solid var(--hc-border); margin-bottom: var(--hc-space-4);">
                            <span style="font-size: var(--hc-text-2xl); font-weight: 700;">{{ number_format($service->price, 2) }} €</span>
                            <span style="font-size: var(--hc-text-sm); color: var(--hc-text-muted);">/ {{ $service->billing_cycle }}</span>
                        </div>

                        @if($service->next_due_date)
                            <div style="font-size: var(--hc-text-sm); color: var(--hc-text-muted); margin-bottom: var(--hc-space-4);">
                                <i data-lucide="calendar" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i>
                                Expire le {{ $service->next_due_date->format('d/m/Y') }}
                            </div>
                        @endif

                        <x-button :href="route('client.services.show', $service)" variant="secondary" style="width: 100%;">
                            Gérer
                        </x-button>
                    </div>
                </x-card>
            @endforeach
        </div>

        <div style="margin-top: var(--hc-space-6);">
            {{ $services->links() }}
        </div>
    @else
        <x-card>
            <x-empty-state
                title="Aucun service"
                description="Vous n'avez pas encore de services actifs."
                icon="🖥️"
            >
                <x-button :href="route('store.index')" variant="primary">Commander un service</x-button>
            </x-empty-state>
        </x-card>
    @endif
@endsection
