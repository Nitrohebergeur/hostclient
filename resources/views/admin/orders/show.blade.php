@extends('layouts.admin')

@section('title', 'Commande ' . $order->order_number)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.orders.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
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
                'refunded' => 'danger',
                default => 'neutral'
            }">{{ ucfirst($order->status) }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);" class="hc-detail-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Articles" padding="false">
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
                                <td><strong>{{ $item->name }}</strong></td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                <td style="text-align: right; font-weight: 500;">{{ number_format($item->total, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>

            <x-card header="Modifier le statut">
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                        <div>
                            <label class="hc-label">Statut</label>
                            <select name="status" class="hc-select">
                                @foreach(['pending', 'processing', 'completed', 'cancelled', 'refunded'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="hc-label">Notes</label>
                            <textarea name="notes" class="hc-textarea" rows="3">{{ old('notes', $order->notes) }}</textarea>
                        </div>
                    </div>

                    <x-button type="submit" variant="primary">Mettre à jour</x-button>
                </form>
            </x-card>
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

            <x-card header="Client">
                @if($order->user)
                    <div style="display: flex; align-items: center; gap: var(--hc-space-3); margin-bottom: var(--hc-space-3);">
                        <div style="width: 40px; height: 40px; background: var(--hc-primary-50); color: var(--hc-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            {{ strtoupper(substr($order->user->first_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ $order->user->first_name }} {{ $order->user->last_name }}</div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $order->user->email }}</div>
                        </div>
                    </div>
                    <x-button :href="route('admin.clients.show', $order->user)" variant="secondary" size="sm" style="width: 100%;">
                        Voir le client
                    </x-button>
                @endif
            </x-card>

            <x-card header="Informations">
                <dl style="margin: 0; font-size: var(--hc-text-sm);">
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Date de commande</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($order->paid_at)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Payée le</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ $order->paid_at->format('d/m/Y H:i') }}</dd>
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

            <x-card>
                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Supprimer cette commande ?')">
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
