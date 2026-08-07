@extends('layouts.admin')

@section('title', $service->name)
@section('content')
    <div class="hc-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.services.index') }}">Services</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <span class="hc-breadcrumb-current">{{ $service->name }}</span>
    </div>

    <x-page-header :title="$service->name" :subtitle="($service->product?->name ?? 'Service') . ' · ' . ($service->user?->first_name ?? '') . ' ' . ($service->user?->last_name ?? '')">
        <x-slot:actions>
            <x-badge :variant="match($service->status) {
                'active' => 'success',
                'suspended' => 'danger',
                'pending' => 'warning',
                'terminated' => 'neutral',
                default => 'neutral'
            }">{{ ucfirst($service->status) }}</x-badge>
            <x-button :href="route('admin.services.edit', $service)" variant="secondary">
                <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                Modifier
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="hc-info-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Informations générales">
                <dl style="margin: 0; display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-5);">
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1); font-weight: 600;">Client</dt>
                        <dd style="margin: 0; font-weight: 600;">
                            @if($service->user)
                                <a href="{{ route('admin.clients.show', $service->user) }}" style="color: var(--hc-primary); text-decoration: none; display: inline-flex; align-items: center; gap: var(--hc-space-2);">
                                    <div class="hc-avatar hc-avatar-sm hc-avatar-primary">{{ strtoupper(substr($service->user->first_name, 0, 1)) }}</div>
                                    <span>{{ $service->user->first_name }} {{ $service->user->last_name }}</span>
                                </a>
                            @else — @endif
                        </dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1); font-weight: 600;">Produit</dt>
                        <dd style="margin: 0; font-weight: 600;">{{ $service->product?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1); font-weight: 600;">Identifiant</dt>
                        <dd style="margin: 0; font-family: var(--hc-font-mono); font-size: var(--hc-text-sm);">{{ $service->identifier ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1); font-weight: 600;">Prix</dt>
                        <dd style="margin: 0; font-weight: 700; color: var(--hc-primary);">{{ number_format($service->price, 2) }} € / {{ $service->billing_cycle }}</dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1); font-weight: 600;">Prochaine échéance</dt>
                        <dd style="margin: 0; font-weight: 600;">
                            @if($service->next_due_date)
                                <span style="color: {{ $service->next_due_date->isPast() ? 'var(--hc-danger)' : 'var(--hc-text)' }};">{{ $service->next_due_date->format('d/m/Y') }}</span>
                            @else — @endif
                        </dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1); font-weight: 600;">Renouvellement</dt>
                        <dd style="margin: 0;">
                            <x-badge :variant="$service->auto_renew ? 'success' : 'neutral'">{{ $service->auto_renew ? 'Automatique' : 'Manuel' }}</x-badge>
                        </dd>
                    </div>
                </dl>
            </x-card>

            @if($service->history && $service->history->count())
                <x-card header="Historique" :padding="false">
                    @foreach($service->history->take(15) as $entry)
                        <div class="hc-activity-item">
                            <div class="hc-activity-icon">
                                <i data-lucide="activity"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: var(--hc-text-sm); font-weight: 600;">{{ $entry->description ?? $entry->action }}</div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: 2px;">{{ $entry->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </x-card>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Actions rapides">
                <div style="display: flex; flex-direction: column; gap: var(--hc-space-3);">
                    @if(!$service->isActive())
                        <form method="POST" action="{{ route('admin.services.activate', $service) }}">
                            @csrf
                            <x-button type="submit" variant="primary" style="width: 100%;">
                                <i data-lucide="play" style="width: 14px; height: 14px;"></i>
                                Activer le service
                            </x-button>
                        </form>
                    @endif
                    @if(!$service->isSuspended() && !$service->isTerminated())
                        <form method="POST" action="{{ route('admin.services.suspend', $service) }}">
                            @csrf
                            <input type="hidden" name="reason" value="Suspendu par l'administrateur">
                            <x-button type="submit" variant="secondary" style="width: 100%;">
                                <i data-lucide="pause" style="width: 14px; height: 14px;"></i>
                                Suspendre
                            </x-button>
                        </form>
                    @endif
                    @if(!$service->isTerminated())
                        <form method="POST" action="{{ route('admin.services.terminate', $service) }}" onsubmit="return confirm('Résilier définitivement ce service ?')">
                            @csrf
                            <input type="hidden" name="reason" value="Résilié par l'administrateur">
                            <x-button type="submit" variant="danger" style="width: 100%;">
                                <i data-lucide="x-circle" style="width: 14px; height: 14px;"></i>
                                Résilier
                            </x-button>
                        </form>
                    @endif
                </div>
            </x-card>

            <x-card header="Zone dangereuse">
                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Supprimer définitivement ce service ?')">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" style="width: 100%;">
                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                        Supprimer définitivement
                    </x-button>
                </form>
            </x-card>
        </div>
    </div>
@endsection