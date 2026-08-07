@extends('layouts.admin')

@section('title', $service->name)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.services.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux services
        </a>
    </div>

    <x-page-header title="{{ $service->name }}">
        <x-slot:actions>
            <x-badge :variant="match($service->status) {
                'active' => 'success',
                'suspended' => 'danger',
                'pending' => 'warning',
                'terminated' => 'neutral',
                default => 'neutral'
            }">{{ ucfirst($service->status) }}</x-badge>
            <x-button :href="route('admin.services.edit', $service)" variant="secondary" size="sm">
                <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                Modifier
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);" class="hc-detail-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Informations">
                <dl style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--hc-space-4); margin: 0;">
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1);">Client</dt>
                        <dd style="margin: 0; font-weight: 500;">
                            <a href="{{ route('admin.clients.show', $service->user) }}" style="color: var(--hc-primary); text-decoration: none;">
                                {{ $service->user?->first_name }} {{ $service->user?->last_name }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1);">Produit</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $service->product?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1);">Identifiant</dt>
                        <dd style="margin: 0; font-family: var(--hc-font-mono);">{{ $service->identifier ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1);">Prix</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ number_format($service->price, 2) }} € / {{ $service->billing_cycle }}</dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1);">Prochaine échéance</dt>
                        <dd style="margin: 0;">{{ $service->next_due_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1);">Renouvellement</dt>
                        <dd style="margin: 0;">
                            <x-badge :variant="$service->auto_renew ? 'success' : 'neutral'">{{ $service->auto_renew ? 'Auto' : 'Manuel' }}</x-badge>
                        </dd>
                    </div>
                </dl>
            </x-card>

            @if($service->history && $service->history->count())
                <x-card header="Historique" padding="false">
                    <div style="padding: var(--hc-space-4) var(--hc-space-5);">
                        @foreach($service->history->take(15) as $entry)
                            <div style="display: flex; gap: var(--hc-space-3); padding: var(--hc-space-3) 0; border-bottom: 1px solid var(--hc-border);">
                                <i data-lucide="activity" style="width: 16px; height: 16px; color: var(--hc-text-muted); margin-top: 2px;"></i>
                                <div style="flex: 1;">
                                    <div style="font-size: var(--hc-text-sm); font-weight: 500;">{{ $entry->description ?? $entry->action }}</div>
                                    <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $entry->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Actions rapides">
                <div style="display: flex; flex-direction: column; gap: var(--hc-space-2);">
                    @if(!$service->isActive())
                        <form method="POST" action="{{ route('admin.services.activate', $service) }}">
                            @csrf
                            <x-button type="submit" variant="primary" style="width: 100%; justify-content: flex-start;">
                                <i data-lucide="play" style="width: 16px; height: 16px;"></i>
                                Activer
                            </x-button>
                        </form>
                    @endif
                    @if(!$service->isSuspended() && !$service->isTerminated())
                        <form method="POST" action="{{ route('admin.services.suspend', $service) }}">
                            @csrf
                            <input type="hidden" name="reason" value="Suspendu par l'administrateur">
                            <x-button type="submit" variant="secondary" style="width: 100%; justify-content: flex-start;">
                                <i data-lucide="pause" style="width: 16px; height: 16px;"></i>
                                Suspendre
                            </x-button>
                        </form>
                    @endif
                    @if(!$service->isTerminated())
                        <form method="POST" action="{{ route('admin.services.terminate', $service) }}" onsubmit="return confirm('Résilier définitivement ce service ?')">
                            @csrf
                            <input type="hidden" name="reason" value="Résilié par l'administrateur">
                            <x-button type="submit" variant="danger" style="width: 100%; justify-content: flex-start;">
                                <i data-lucide="x-circle" style="width: 16px; height: 16px;"></i>
                                Résilier
                            </x-button>
                        </form>
                    @endif
                </div>
            </x-card>

            <x-card>
                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Supprimer définitivement ce service ?')">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" style="width: 100%;">
                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                        Supprimer
                    </x-button>
                </form>
            </x-card>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .hc-detail-grid { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection
