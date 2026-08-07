@extends('layouts.client')

@section('title', $service->name)
@section('subtitle', 'Détails et gestion du service')

@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('client.services.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux services
        </a>
    </div>

    <x-page-header title="{{ $service->name }}">
        <x-slot:actions>
            <x-badge :variant="match($service->status) {
                'active' => 'success',
                'pending' => 'warning',
                'suspended' => 'danger',
                'terminated' => 'neutral',
                default => 'neutral'
            }">{{ ucfirst($service->status) }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);" class="hc-detail-grid">
        {{-- Colonne principale --}}
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Informations générales">
                <dl style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--hc-space-4); margin: 0;">
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
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1);">Frais d'installation</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ number_format($service->setup_fee ?? 0, 2) }} €</dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1);">Prochaine échéance</dt>
                        <dd style="margin: 0;">{{ $service->next_due_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--hc-space-1);">Activé le</dt>
                        <dd style="margin: 0;">{{ $service->activated_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                    </div>
                </dl>
            </x-card>

            @if($service->invoices && $service->invoices->count())
                <x-card header="Factures associées" padding="false">
                    <table class="hc-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service->invoices as $invoice)
                                <tr>
                                    <td style="font-family: var(--hc-font-mono);">{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->issue_date?->format('d/m/Y') }}</td>
                                    <td><strong>{{ number_format($invoice->total, 2) }} €</strong></td>
                                    <td>
                                        <x-badge :variant="match($invoice->status) {
                                            'paid' => 'success',
                                            'unpaid' => 'danger',
                                            'partially_paid' => 'warning',
                                            'cancelled' => 'neutral',
                                            default => 'neutral'
                                        }">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</x-badge>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('client.invoices.show', $invoice) }}" class="hc-btn hc-btn-ghost hc-btn-sm">Voir</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-card>
            @endif

            @if($service->history && $service->history->count())
                <x-card header="Historique" padding="false">
                    <div style="padding: var(--hc-space-4) var(--hc-space-5);">
                        @foreach($service->history->take(10) as $entry)
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

        {{-- Colonne latérale --}}
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">
            <x-card header="Renouvellement automatique">
                <form method="POST" action="{{ route('client.services.update', $service) }}">
                    @csrf
                    @method('PUT')
                    <label style="display: flex; align-items: center; gap: var(--hc-space-3); cursor: pointer; margin-bottom: var(--hc-space-4);">
                        <input type="hidden" name="auto_renew" value="0">
                        <input type="checkbox" name="auto_renew" value="1" @checked($service->auto_renew) style="width: 18px; height: 18px;">
                        <span style="font-size: var(--hc-text-sm);">Activer le renouvellement automatique</span>
                    </label>
                    <x-button type="submit" variant="primary" style="width: 100%;">Enregistrer</x-button>
                </form>
            </x-card>

            <x-card header="Actions">
                <div style="display: flex; flex-direction: column; gap: var(--hc-space-2);">
                    <x-button :href="route('client.tickets.create')" variant="secondary" style="width: 100%; justify-content: flex-start;">
                        <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
                        Contacter le support
                    </x-button>
                    @if($service->isTerminated())
                        <form method="POST" action="{{ route('client.services.destroy', $service) }}" onsubmit="return confirm('Supprimer définitivement ce service ?')">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger" style="width: 100%; justify-content: flex-start;">
                                <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                Supprimer le service
                            </x-button>
                        </form>
                    @endif
                </div>
            </x-card>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .hc-detail-grid { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection