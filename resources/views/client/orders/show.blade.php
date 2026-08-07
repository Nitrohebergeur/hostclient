@extends('layouts.client')

@section('title', 'Commande ' . $order->order_number)
@section('subtitle', 'Détails de votre commande')

@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('client.orders.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux commandes
        </a>
    </div>

    <x-page-header title="Commande {{ $order->order_number }}">
        <x-slot:actions>
            <x-badge :variant="match($order->status) {
                'completed' => 'success',
                'pending' => 'warning',
                'processing' => 'info',
                'cancelled' => 'neutral',
                default => 'neutral'
            }">{{ ucfirst($order->status) }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);" class="hc-detail-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Articles commandés" padding="false">
                <table class="hc-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div style="font-weight: 500;">{{ $item->name }}</div>
                                    @if($item->product)
                                        <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $item->product->name }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                <td style="text-align: right; font-weight: 500;">{{ number_format($item->total, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>

            @if($order->services && $order->services->count())
                <x-card header="Services activés">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($order->services as $service)
                            <li style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-3) 0; border-bottom: 1px solid var(--hc-border);">
                                <div>
                                    <div style="font-weight: 500;">{{ $service->name }}</div>
                                    <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $service->identifier ?? '—' }}</div>
                                </div>
                                <x-button :href="route('client.services.show', $service)" variant="ghost" size="sm">
                                    Gérer
                                    <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                                </x-button>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            @endif

            @if($order->invoice)
                <x-card header="Facture">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-family: var(--hc-font-mono); font-weight: 500;">{{ $order->invoice->invoice_number }}</div>
                            <div style="font-size: var(--hc-text-sm); color: var(--hc-text-muted);">
                                {{ number_format($order->invoice->total, 2) }} € —
                                <x-badge :variant="match($order->invoice->status) {
                                    'paid' => 'success',
                                    'unpaid' => 'danger',
                                    'partially_paid' => 'warning',
                                    default => 'neutral'
                                }">{{ ucfirst(str_replace('_', ' ', $order->invoice->status)) }}</x-badge>
                            </div>
                        </div>
                        <x-button :href="route('client.invoices.show', $order->invoice)" variant="secondary" size="sm">
                            Voir la facture
                        </x-button>
                    </div>
                </x-card>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Récapitulatif">
                <dl style="margin: 0;">
                    <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">Sous-total</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ number_format($order->subtotal, 2) }} €</dd>
                    </div>
                    @if($order->discount > 0)
                        <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">Remise</dt>
                            <dd style="margin: 0; font-weight: 500; color: var(--hc-success);">-{{ number_format($order->discount, 2) }} €</dd>
                        </div>
                    @endif
                    @if($order->tax > 0)
                        <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">TVA</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ number_format($order->tax, 2) }} €</dd>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; padding: var(--hc-space-3) 0; border-top: 1px solid var(--hc-border); margin-top: var(--hc-space-2);">
                        <dt style="font-weight: 600;">Total</dt>
                        <dd style="margin: 0; font-size: var(--hc-text-lg); font-weight: 700;">{{ number_format($order->total, 2) }} €</dd>
                    </div>
                </dl>
            </x-card>

            <x-card header="Informations">
                <dl style="margin: 0; font-size: var(--hc-text-sm);">
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Date de commande</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $order->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @if($order->paid_at)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Payée le</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ $order->paid_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                    @endif
                    @if($order->payment_method)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Paiement</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ ucfirst($order->payment_method) }}</dd>
                        </div>
                    @endif
                </dl>
            </x-card>

            @if($order->status === 'pending')
                <x-card>
                    <form method="POST" action="{{ route('client.orders.update', $order) }}" onsubmit="return confirm('Annuler cette commande ?')">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="cancel">
                        <x-button type="submit" variant="danger" style="width: 100%;">Annuler la commande</x-button>
                    </form>
                </x-card>
            @endif
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .hc-detail-grid { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection