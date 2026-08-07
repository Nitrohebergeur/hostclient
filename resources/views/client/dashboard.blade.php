@extends('layouts.client')

@section('title', 'Tableau de bord')
@section('subtitle', 'Aperçu de vos services et de votre facturation')

@section('content')
    {{-- Welcome --}}
    <div style="background: linear-gradient(135deg, var(--hc-primary), #6366f1); color: var(--hc-text-inverse); padding: var(--hc-space-8); border-radius: var(--hc-radius-lg); margin-bottom: var(--hc-space-8);">
        <h2 style="font-size: var(--hc-text-3xl); font-weight: 700; margin: 0 0 var(--hc-space-2) 0;">Bonjour, {{ auth()->user()->first_name }} 👋</h2>
        <p style="margin: 0; opacity: 0.9;">Voici un aperçu de vos services et de votre facturation.</p>
    </div>

    {{-- Stats --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-8);">
        <x-stat label="Services actifs" :value="$stats['active_services'] ?? 0" />
        <x-stat label="Factures impayées" :value="$stats['unpaid_invoices'] ?? 0" />
        <x-stat label="Tickets ouverts" :value="$stats['open_tickets'] ?? 0" />
        <x-stat label="Solde du compte" :value="number_format(auth()->user()->balance ?? 0, 2) . ' €'" />
    </div>

    {{-- Two columns --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-6);">
        {{-- Recent services --}}
        <x-card header="Mes services récents" :padding="false">
            <div style="padding: var(--hc-space-5);">
                @forelse($recentServices ?? [] as $service)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-3) 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--hc-border);' : '' }}">
                        <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                            <div style="width: 40px; height: 40px; background: var(--hc-primary-50); color: var(--hc-primary); border-radius: var(--hc-radius); display: flex; align-items: center; justify-content: center;">
                                <i data-lucide="server" style="width: 20px; height: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ $service->name }}</div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $service->product->name ?? '' }}</div>
                            </div>
                        </div>
                        <x-badge :variant="$service->status === 'active' ? 'success' : 'neutral'">
                            {{ ucfirst($service->status) }}
                        </x-badge>
                    </div>
                @empty
                    <x-empty-state
                        title="Aucun service actif"
                        description="Découvrez nos offres d'hébergement et lancez votre premier service."
                        icon="📦"
                    >
                        <x-button :href="route('store.index')" variant="primary">Voir la boutique</x-button>
                    </x-empty-state>
                @endforelse
            </div>
            @if(($recentServices ?? collect())->count() > 0)
                <div style="padding: var(--hc-space-3) var(--hc-space-5); border-top: 1px solid var(--hc-border); text-align: center;">
                    <a href="{{ route('client.services.index') }}" style="color: var(--hc-primary); font-size: var(--hc-text-sm); font-weight: 500;">
                        Voir tous mes services →
                    </a>
                </div>
            @endif
        </x-card>

        {{-- Recent invoices --}}
        <x-card header="Mes factures récentes" :padding="false">
            <div style="padding: var(--hc-space-5);">
                @forelse($recentInvoices ?? [] as $invoice)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-3) 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--hc-border);' : '' }}">
                        <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                            <div style="width: 40px; height: 40px; background: var(--hc-info-bg); color: var(--hc-info); border-radius: var(--hc-radius); display: flex; align-items: center; justify-content: center;">
                                <i data-lucide="file-text" style="width: 20px; height: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ $invoice->invoice_number }}</div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">
                                    Échéance : {{ $invoice->due_date?->format(config('hostclient.date_format', 'd/m/Y')) ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ number_format($invoice->total ?? 0, 2) }} €</div>
                            <x-badge :variant="$invoice->status === 'paid' ? 'success' : 'danger'">
                                {{ $invoice->status === 'paid' ? 'Payée' : 'Impayée' }}
                            </x-badge>
                        </div>
                    </div>
                @empty
                    <x-empty-state
                        title="Aucune facture"
                        description="Vos factures apparaîtront ici dès qu'une commande sera passée."
                        icon="📭"
                    />
                @endforelse
            </div>
            @if(($recentInvoices ?? collect())->count() > 0)
                <div style="padding: var(--hc-space-3) var(--hc-space-5); border-top: 1px solid var(--hc-border); text-align: center;">
                    <a href="{{ route('client.invoices.index') }}" style="color: var(--hc-primary); font-size: var(--hc-text-sm); font-weight: 500;">
                        Voir toutes mes factures →
                    </a>
                </div>
            @endif
        </x-card>
    </div>

    {{-- Quick actions --}}
    <div style="margin-top: var(--hc-space-8);">
        <h3 style="font-size: var(--hc-text-lg); font-weight: 600; margin-bottom: var(--hc-space-4);">Actions rapides</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--hc-space-3);">
            <x-button :href="route('store.index')" variant="primary">
                <i data-lucide="shopping-cart" style="width: 16px; height: 16px;"></i>
                Commander
            </x-button>
            <x-button :href="route('client.tickets.create')" variant="secondary">
                <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
                Ouvrir un ticket
            </x-button>
            <x-button :href="route('client.profile.edit')" variant="secondary">
                <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                Mon profil
            </x-button>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    main > div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection