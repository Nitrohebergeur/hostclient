@extends('layouts.client')

@section('title', 'Facture ' . $invoice->invoice_number)
@section('subtitle', 'Détails de la facture')

@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('client.invoices.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux factures
        </a>
    </div>

    <x-page-header title="Facture {{ $invoice->invoice_number }}">
        <x-slot:actions>
            <x-button :href="route('client.invoices.download', $invoice)" variant="secondary" size="sm">
                <i data-lucide="download" style="width: 16px; height: 16px;"></i>
                Télécharger PDF
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);" class="hc-detail-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Articles facturés" padding="false">
                <table class="hc-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->items as $item)
                            <tr>
                                <td>
                                    <div style="font-weight: 500;">{{ $item->description }}</div>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                <td style="text-align: right; font-weight: 500;">{{ number_format($item->total, 2) }} €</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--hc-text-muted); padding: var(--hc-space-6);">
                                    Aucun article
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

            @if($invoice->transactions && $invoice->transactions->count())
                <x-card header="Paiements">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($invoice->transactions as $tx)
                            <li style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-3) 0; border-bottom: 1px solid var(--hc-border);">
                                <div>
                                    <div style="font-weight: 500;">{{ number_format($tx->amount, 2) }} €</div>
                                    <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">
                                        {{ ucfirst($tx->payment_method ?? '—') }} — {{ $tx->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <x-badge variant="success">{{ ucfirst($tx->status ?? 'completed') }}</x-badge>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Statut">
                <div style="text-align: center; padding: var(--hc-space-3) 0;">
                    <x-badge :variant="match($invoice->status) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'partially_paid' => 'warning',
                        'cancelled' => 'neutral',
                        default => 'neutral'
                    }" style="font-size: var(--hc-text-sm); padding: 6px 14px;">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</x-badge>
                </div>
            </x-card>

            <x-card header="Récapitulatif">
                <dl style="margin: 0;">
                    <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">Sous-total</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ number_format($invoice->subtotal, 2) }} €</dd>
                    </div>
                    @if($invoice->discount > 0)
                        <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">Remise</dt>
                            <dd style="margin: 0; font-weight: 500; color: var(--hc-success);">-{{ number_format($invoice->discount, 2) }} €</dd>
                        </div>
                    @endif
                    @if($invoice->tax > 0)
                        <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">TVA ({{ number_format($invoice->tax_rate, 1) }}%)</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ number_format($invoice->tax, 2) }} €</dd>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; padding: var(--hc-space-3) 0; border-top: 1px solid var(--hc-border); margin-top: var(--hc-space-2);">
                        <dt style="font-weight: 600;">Total</dt>
                        <dd style="margin: 0; font-size: var(--hc-text-lg); font-weight: 700;">{{ number_format($invoice->total, 2) }} €</dd>
                    </div>
                    @if($invoice->amount_paid > 0 && !$invoice->isPaid())
                        <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">Déjà payé</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ number_format($invoice->amount_paid, 2) }} €</dd>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-danger); font-size: var(--hc-text-sm); font-weight: 500;">Reste dû</dt>
                            <dd style="margin: 0; font-weight: 600; color: var(--hc-danger);">{{ number_format($invoice->balance, 2) }} €</dd>
                        </div>
                    @endif
                </dl>
            </x-card>

            @if(!$invoice->isPaid())
                <x-card header="Payer cette facture">
                    <form method="POST" action="{{ route('client.invoices.pay', $invoice) }}">
                        @csrf
                        <label class="hc-label">Mode de paiement</label>
                        <select name="payment_method" class="hc-select" style="margin-bottom: var(--hc-space-4);">
                            <option value="balance">Solde du compte ({{ number_format(auth()->user()->balance ?? 0, 2) }} €)</option>
                            <option value="stripe">Carte bancaire (Stripe)</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Virement bancaire</option>
                        </select>
                        <x-button type="submit" variant="primary" style="width: 100%;">
                            <i data-lucide="credit-card" style="width: 16px; height: 16px;"></i>
                            Régler {{ number_format($invoice->balance ?? $invoice->total, 2) }} €
                        </x-button>
                    </form>
                </x-card>
            @endif

            <x-card header="Informations">
                <dl style="margin: 0; font-size: var(--hc-text-sm);">
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Date d'émission</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $invoice->issue_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Date d'échéance</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $invoice->due_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    @if($invoice->paid_date)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Payée le</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ $invoice->paid_date->format('d/m/Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </x-card>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .hc-detail-grid { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection