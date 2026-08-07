@extends('layouts.app')

@section('title', 'Paiement confirmé — ' . config('hostclient.company_name', 'HostClient'))

@section('content')
<div class="hc-container" style="padding-top: var(--hc-space-16); padding-bottom: var(--hc-space-16); max-width: 720px;">

    <div style="text-align: center; margin-bottom: var(--hc-space-8);">
        <div style="width: 80px; height: 80px; background: var(--hc-success-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--hc-space-4);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width: 40px; height: 40px; color: var(--hc-success);">
                <path d="M5 13 L9 17 L19 7"/>
            </svg>
        </div>
        <h1 style="font-size: var(--hc-text-4xl); font-weight: 800; margin-bottom: var(--hc-space-3);">Paiement confirmé</h1>
        <p style="color: var(--hc-text-muted); font-size: var(--hc-text-lg);">
            Merci pour votre commande. Vos services sont en cours de déploiement.
        </p>
    </div>

    <x-card header="Récapitulatif de la commande">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-3); margin-bottom: var(--hc-space-6);">
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--hc-text-muted);">Numéro de commande</span>
                <strong>{{ $order->order_number }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--hc-text-muted);">Date</span>
                <span>{{ $order->created_at->format(config('hostclient.datetime_format', 'd/m/Y H:i')) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--hc-text-muted);">Statut</span>
                <x-badge variant="success">Payée</x-badge>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--hc-text-muted);">Mode de paiement</span>
                <span>{{ ucfirst($order->payment_method ?? '—') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <span style="font-weight: 600;">Total payé</span>
                <span style="font-size: var(--hc-text-xl); font-weight: 800;">{{ number_format($order->total, 2) }} €</span>
            </div>
        </div>

        {{-- Articles --}}
        <h3 style="font-size: var(--hc-text-lg); font-weight: 600; margin-bottom: var(--hc-space-3);">Articles</h3>
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-3);">
            @foreach($order->items as $item)
                <div style="display: flex; justify-content: space-between; padding: var(--hc-space-3) 0; border-bottom: 1px solid var(--hc-border);">
                    <div>
                        <div style="font-weight: 500;">{{ $item->name }}</div>
                        <div style="font-size: var(--hc-text-sm); color: var(--hc-text-muted);">
                            {{ ucfirst($item->billing_cycle) }} · Quantité {{ $item->quantity }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600;">{{ number_format($item->total, 2) }} €</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: var(--hc-space-8); display: flex; gap: var(--hc-space-3); flex-wrap: wrap;">
            <x-button :href="route('client.orders.show', $order)" variant="primary">
                Voir ma commande
            </x-button>
            <x-button :href="route('client.dashboard')" variant="secondary">
                Retour à mon espace
            </x-button>
        </div>
    </x-card>

    <p style="text-align: center; color: var(--hc-text-muted); font-size: var(--hc-text-sm); margin-top: var(--hc-space-6);">
        Un e-mail de confirmation vient de vous être envoyé à <strong>{{ auth()->user()->email }}</strong>.
    </p>
</div>
@endsection