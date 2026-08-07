@extends('layouts.admin')

@section('title', $client->first_name . ' ' . $client->last_name)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.clients.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux clients
        </a>
    </div>

    <x-page-header title="{{ $client->first_name }} {{ $client->last_name }}">
        <x-slot:actions>
            <x-badge :variant="($client->is_active ?? true) ? 'success' : 'neutral'">
                {{ ($client->is_active ?? true) ? 'Actif' : 'Inactif' }}
            </x-badge>
            <x-button :href="route('admin.clients.edit', $client)" variant="secondary" size="sm">
                <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                Modifier
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-6);">
        <x-stat label="Services" :value="$stats['services'] ?? 0" />
        <x-stat label="Factures" :value="$stats['invoices'] ?? 0" />
        <x-stat label="Tickets" :value="$stats['tickets'] ?? 0" />
        <x-stat label="Total payé" :value="number_format($stats['total_paid'] ?? 0, 2) . ' €'" />
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);" class="hc-detail-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">
            <x-card header="Services" padding="false">
                @forelse($client->services ?? [] as $service)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-3) var(--hc-space-5); border-bottom: 1px solid var(--hc-border);">
                        <div>
                            <div style="font-weight: 500; font-size: var(--hc-text-sm);">{{ $service->name }}</div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $service->product?->name ?? '—' }}</div>
                        </div>
                        <x-badge :variant="match($service->status) {
                            'active' => 'success',
                            'suspended' => 'danger',
                            'pending' => 'warning',
                            default => 'neutral'
                        }">{{ ucfirst($service->status) }}</x-badge>
                    </div>
                @empty
                    <div style="padding: var(--hc-space-6); text-align: center; color: var(--hc-text-muted);">Aucun service</div>
                @endforelse
            </x-card>

            <x-card header="Factures" padding="false">
                @forelse($client->invoices ?? [] as $invoice)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-3) var(--hc-space-5); border-bottom: 1px solid var(--hc-border);">
                        <div>
                            <div style="font-weight: 500; font-size: var(--hc-text-sm); font-family: var(--hc-font-mono);">{{ $invoice->invoice_number }}</div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $invoice->issue_date?->format('d/m/Y') }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ number_format($invoice->total, 2) }} €</div>
                            <x-badge :variant="match($invoice->status) {
                                'paid' => 'success',
                                'unpaid' => 'danger',
                                'partially_paid' => 'warning',
                                default => 'neutral'
                            }">{{ ucfirst($invoice->status) }}</x-badge>
                        </div>
                    </div>
                @empty
                    <div style="padding: var(--hc-space-6); text-align: center; color: var(--hc-text-muted);">Aucune facture</div>
                @endforelse
            </x-card>
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">
            <x-card header="Informations">
                <dl style="margin: 0; font-size: var(--hc-text-sm);">
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Email</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $client->email }}</dd>
                    </div>
                    @if($client->phone)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Téléphone</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ $client->phone }}</dd>
                        </div>
                    @endif
                    @if($client->company)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Société</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ $client->company }}</dd>
                        </div>
                    @endif
                    @if($client->country)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Pays</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ $client->country }}</dd>
                        </div>
                    @endif
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Inscrit le</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $client->created_at?->format('d/m/Y') }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card>
                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Supprimer ce client ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" style="width: 100%;">
                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                        Supprimer le client
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
