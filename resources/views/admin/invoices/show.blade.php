@extends('layouts.admin')

@section('title', 'Facture ' . $invoice->invoice_number)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.invoices.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux factures
        </a>
    </div>

    <x-page-header title="Facture {{ $invoice->invoice_number }}">
        <x-slot:actions>
            <x-badge :variant="match($invoice->status) {
                'paid' => 'success',
                'unpaid' => 'danger',
                'partially_paid' => 'warning',
                'cancelled' => 'neutral',
                'refunded' => 'neutral',
                'draft' => 'neutral',
                default => 'neutral'
            }">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);" class="hc-detail-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Articles" padding="false">
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
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                <td style="text-align: right; font-weight: 500;">{{ number_format($item->total, 2) }} €</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--hc-text-muted); padding: var(--hc-space-6);">Aucun article</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

            <x-card header="Modifier la facture">
                <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                        <div>
                            <label class="hc-label">Statut</label>
                            <select name="status" class="hc-select">
                                @foreach(['draft', 'unpaid', 'paid', 'cancelled', 'refunded', 'partially_paid'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $invoice->status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="hc-label">Date d'échéance</label>
                            <input type="date" name="due_date" class="hc-input" value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div style="margin-bottom: var(--hc-space-4);">
                        <label class="hc-label">Notes</label>
                        <textarea name="notes" class="hc-textarea" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>

                    <x-button type="submit" variant="primary">Enregistrer</x-button>
                </form>
            </x-card>
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

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
                    @if($invoice->amount_paid > 0)
                        <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">Payé</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ number_format($invoice->amount_paid, 2) }} €</dd>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-danger); font-size: var(--hc-text-sm);">Solde dû</dt>
                            <dd style="margin: 0; font-weight: 600; color: var(--hc-danger);">{{ number_format($invoice->balance, 2) }} €</dd>
                        </div>
                    @endif
                </dl>
            </x-card>

            <x-card header="Client">
                @if($invoice->user)
                    <div style="display: flex; align-items: center; gap: var(--hc-space-3); margin-bottom: var(--hc-space-3);">
                        <div style="width: 40px; height: 40px; background: var(--hc-primary-50); color: var(--hc-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            {{ strtoupper(substr($invoice->user->first_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $invoice->user->email }}</div>
                        </div>
                    </div>
                    <x-button :href="route('admin.clients.show', $invoice->user)" variant="secondary" size="sm" style="width: 100%;">
                        Voir le client
                    </x-button>
                @endif
            </x-card>

            <x-card header="Transactions">
                @forelse($invoice->transactions ?? [] as $tx)
                    <div style="padding: var(--hc-space-2) 0; border-bottom: 1px solid var(--hc-border);">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: var(--hc-text-sm);">{{ number_format($tx->amount, 2) }} €</span>
                            <x-badge :variant="match($tx->status ?? 'completed') {
                                'completed' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                default => 'neutral'
                            }">{{ ucfirst($tx->status ?? 'completed') }}</x-badge>
                        </div>
                        <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $tx->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                @empty
                    <p style="text-align: center; color: var(--hc-text-muted); font-size: var(--hc-text-sm); margin: 0;">Aucune transaction</p>
                @endforelse
            </x-card>

            <x-card>
                <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" onsubmit="return confirm('Supprimer cette facture ?')">
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
