@extends('layouts.admin')

@section('title', $client->first_name . ' ' . $client->last_name)
@section('content')
    <div class="hc-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.clients.index') }}">Clients</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <span class="hc-breadcrumb-current">{{ $client->first_name }} {{ $client->last_name }}</span>
    </div>

    <x-page-header :title="$client->first_name . ' ' . $client->last_name" :subtitle="$client->email">
        <x-slot:actions>
            <x-badge :variant="($client->is_active ?? true) ? 'success' : 'neutral'">
                {{ ($client->is_active ?? true) ? 'Actif' : 'Inactif' }}
            </x-badge>
            <x-button :href="route('admin.clients.edit', $client)" variant="secondary">
                <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                Modifier
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Statistiques client --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-6);">
        <x-stat label="Services" :value="$stats['services'] ?? 0" icon="server" color="primary" />
        <x-stat label="Factures" :value="$stats['invoices'] ?? 0" icon="file-text" color="info" />
        <x-stat label="Tickets" :value="$stats['tickets'] ?? 0" icon="message-circle" color="warning" />
        <x-stat label="Total payé" :value="number_format($stats['total_paid'] ?? 0, 2) . ' €'" icon="credit-card" color="success" />
    </div>

    <div class="hc-info-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">
            {{-- Profil --}}
            <x-card>
                <x-slot:header>
                    <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                        <div class="hc-avatar hc-avatar-lg hc-avatar-primary">
                            {{ strtoupper(substr($client->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($client->last_name ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <h3 style="margin: 0;">{{ $client->first_name }} {{ $client->last_name }}</h3>
                            <p style="margin: 2px 0 0;">{{ $client->email }}</p>
                        </div>
                    </div>
                </x-slot:header>
            </x-card>

            {{-- Services --}}
            <x-card header="Services" :padding="false">
                @forelse($client->services ?? [] as $service)
                    <div class="hc-activity-item">
                        <div class="hc-activity-icon" style="background: var(--hc-primary-50); color: var(--hc-primary);">
                            <i data-lucide="server"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: var(--hc-space-2);">
                                <a href="{{ route('admin.services.show', $service) }}" style="font-weight: 600; font-size: var(--hc-text-sm); color: var(--hc-text); text-decoration: none;">
                                    {{ $service->name }}
                                </a>
                                <x-badge :variant="match($service->status) {
                                    'active' => 'success',
                                    'suspended' => 'danger',
                                    'pending' => 'warning',
                                    'cancelled' => 'neutral',
                                    default => 'neutral'
                                }">{{ ucfirst($service->status) }}</x-badge>
                            </div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: 2px;">
                                {{ $service->product?->name ?? '—' }} · {{ number_format($service->price ?? 0, 2) }} € / {{ $service->billing_cycle ?? 'mois' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="hc-empty-state">
                        <div class="hc-empty-icon">📦</div>
                        <h3>Aucun service</h3>
                        <p>Ce client n'a pas encore de service actif.</p>
                    </div>
                @endforelse
            </x-card>

            {{-- Factures --}}
            <x-card header="Factures" :padding="false">
                @forelse($client->invoices ?? [] as $invoice)
                    <div class="hc-activity-item">
                        <div class="hc-activity-icon" style="background: var(--hc-info-50); color: var(--hc-info);">
                            <i data-lucide="file-text"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: var(--hc-space-2);">
                                <a href="{{ route('admin.invoices.show', $invoice) }}" style="font-family: var(--hc-font-mono); font-weight: 600; font-size: var(--hc-text-sm); color: var(--hc-text); text-decoration: none;">
                                    {{ $invoice->invoice_number }}
                                </a>
                                <div style="font-weight: 700; font-size: var(--hc-text-sm);">{{ number_format($invoice->total, 2) }} €</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2px;">
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $invoice->issue_date?->format('d/m/Y') }}</div>
                                <x-badge :variant="match($invoice->status) {
                                    'paid' => 'success',
                                    'unpaid' => 'danger',
                                    'partially_paid' => 'warning',
                                    'cancelled' => 'neutral',
                                    default => 'neutral'
                                }">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</x-badge>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="hc-empty-state">
                        <div class="hc-empty-icon">📄</div>
                        <h3>Aucune facture</h3>
                        <p>Aucune facture n'a été émise pour ce client.</p>
                    </div>
                @endforelse
            </x-card>
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">
            {{-- Coordonnées --}}
            <x-card header="Coordonnées">
                <dl class="hc-dl">
                    <div class="hc-dl-row">
                        <dt class="hc-dl-label">Email</dt>
                        <dd class="hc-dl-value">{{ $client->email }}</dd>
                    </div>
                    @if($client->phone)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Téléphone</dt>
                            <dd class="hc-dl-value">{{ $client->phone }}</dd>
                        </div>
                    @endif
                    @if($client->company)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Société</dt>
                            <dd class="hc-dl-value">{{ $client->company }}</dd>
                        </div>
                    @endif
                    @if($client->country)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Pays</dt>
                            <dd class="hc-dl-value">{{ $client->country }}</dd>
                        </div>
                    @endif
                    <div class="hc-dl-row">
                        <dt class="hc-dl-label">Inscrit le</dt>
                        <dd class="hc-dl-value">{{ $client->created_at?->format('d/m/Y') }}</dd>
                    </div>
                    @if($client->last_login_at ?? false)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Dernière connexion</dt>
                            <dd class="hc-dl-value">{{ $client->last_login_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </x-card>

            {{-- Statut du compte --}}
            <x-card header="Statut du compte">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-2) 0;">
                    <span style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">État</span>
                    <x-badge :variant="($client->is_active ?? true) ? 'success' : 'danger'">
                        {{ ($client->is_active ?? true) ? 'Actif' : 'Désactivé' }}
                    </x-badge>
                </div>
                @if($client->email_verified_at ?? false)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-2) 0;">
                        <span style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">Email vérifié</span>
                        <x-badge variant="success">Oui</x-badge>
                    </div>
                @endif
                @if($client->two_factor_enabled ?? false)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-2) 0;">
                        <span style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">2FA activé</span>
                        <x-badge variant="info">Oui</x-badge>
                    </div>
                @endif
            </x-card>

            {{-- Zone dangereuse --}}
            <x-card header="Actions">
                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Supprimer définitivement ce client ? Cette action est irréversible.')">
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
@endsection