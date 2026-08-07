@extends('layouts.admin')

@section('title', 'Commande ' . $order->order_number)
@section('content')
    <div class="hc-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.orders.index') }}">Commandes</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <span class="hc-breadcrumb-current">{{ $order->order_number }}</span>
    </div>

    <x-page-header :title="'Commande ' . $order->order_number" :subtitle="'Passée le ' . $order->created_at->format('d/m/Y à H:i')">
        <x-slot:actions>
            <x-badge :variant="match($order->status) {
                'completed' => 'success',
                'pending' => 'warning',
                'processing' => 'info',
                'cancelled' => 'neutral',
                'refunded' => 'danger',
                default => 'neutral'
            }">{{ ucfirst($order->status) }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <div class="hc-info-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Articles commandés" :padding="false">
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
                                    <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                                        <div style="width: 32px; height: 32px; background: var(--hc-primary-50); color: var(--hc-primary); border-radius: var(--hc-radius); display: flex; align-items: center; justify-content: center;">
                                            <i data-lucide="package" style="width: 16px; height: 16px;"></i>
                                        </div>
                                        <div style="font-weight: 600;">{{ $item->name }}</div>
                                    </div>
                                </td>
                                <td><x-badge variant="neutral">{{ $item->quantity }}</x-badge></td>
                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                <td style="text-align: right; font-weight: 700;">{{ number_format($item->total, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>

            <x-card header="Modifier le statut">
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-5);">
                        <div>
                            <label class="hc-label">Statut de la commande</label>
                            <select name="status" class="hc-select">
                                @foreach(['pending', 'processing', 'completed', 'cancelled', 'refunded'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="hc-label">Notes internes</label>
                            <textarea name="notes" class="hc-textarea" rows="3">{{ old('notes', $order->notes) }}</textarea>
                        </div>
                    </div>

                    <x-button type="submit" variant="primary">
                        <i data-lucide="save" style="width: 14px; height: 14px;"></i>
                        Mettre à jour
                    </x-button>
                </form>
            </x-card>
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Récapitulatif">
                <dl class="hc-dl">
                    <div class="hc-dl-row">
                        <dt class="hc-dl-label">Sous-total</dt>
                        <dd class="hc-dl-value">{{ number_format($order->subtotal, 2) }} €</dd>
                    </div>
                    @if($order->discount > 0)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Remise</dt>
                            <dd class="hc-dl-value" style="color: var(--hc-success);">-{{ number_format($order->discount, 2) }} €</dd>
                        </div>
                    @endif
                    @if($order->tax > 0)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">TVA</dt>
                            <dd class="hc-dl-value">{{ number_format($order->tax, 2) }} €</dd>
                        </div>
                    @endif
                    <div class="hc-dl-row" style="border-top: 1px solid var(--hc-border); margin-top: var(--hc-space-2); padding-top: var(--hc-space-4);">
                        <dt style="font-weight: 700; color: var(--hc-text);">Total</dt>
                        <dd style="font-size: var(--hc-text-xl); font-weight: 700; color: var(--hc-primary);">{{ number_format($order->total, 2) }} €</dd>
                    </div>
                </dl>
            </x-card>

            @if($order->user)
            <x-card>
                <x-slot:header>
                    <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                        <div class="hc-avatar hc-avatar-primary">
                            {{ strtoupper(substr($order->user->first_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: var(--hc-text-sm); font-weight: 600;">{{ $order->user->first_name }} {{ $order->user->last_name }}</h3>
                            <p style="margin: 2px 0 0;">{{ $order->user->email }}</p>
                        </div>
                    </div>
                </x-slot:header>
                <x-button :href="route('admin.clients.show', $order->user)" variant="secondary" style="width: 100%;">
                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                    Voir le client
                </x-button>
            </x-card>
            @endif

            <x-card header="Informations">
                <dl class="hc-dl">
                    <div class="hc-dl-row">
                        <dt class="hc-dl-label">Date</dt>
                        <dd class="hc-dl-value">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($order->paid_at)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Payée le</dt>
                            <dd class="hc-dl-value">{{ $order->paid_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                    @if($order->payment_method)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Paiement</dt>
                            <dd class="hc-dl-value">{{ ucfirst($order->payment_method) }}</dd>
                        </div>
                    @endif
                    @if($order->invoice)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Facture</dt>
                            <dd class="hc-dl-value">
                                <a href="{{ route('admin.invoices.show', $order->invoice) }}" style="color: var(--hc-primary);">{{ $order->invoice->invoice_number }}</a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-card>

            <x-card header="Actions">
                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Supprimer définitivement cette commande ?')">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" style="width: 100%;">
                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                        Supprimer la commande
                    </x-button>
                </form>
            </x-card>
        </div>
    </div>
@endsection