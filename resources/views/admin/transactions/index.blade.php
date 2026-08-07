@extends('layouts.admin')

@section('title', 'Transactions')
@section('content')
    <x-page-header title="Transactions" />

    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" style="padding: var(--hc-space-4); display: flex; gap: var(--hc-space-3); flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ID transaction..." class="hc-input">
            </div>
            <div style="min-width: 140px;">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select">
                    <option value="">Tous</option>
                    <option value="completed" @selected(request('status') === 'completed')>Complétée</option>
                    <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                    <option value="failed" @selected(request('status') === 'failed')>Échouée</option>
                    <option value="refunded" @selected(request('status') === 'refunded')>Remboursée</option>
                </select>
            </div>
            <div style="min-width: 140px;">
                <label class="hc-label">Type</label>
                <select name="type" class="hc-select">
                    <option value="">Tous</option>
                    <option value="payment" @selected(request('type') === 'payment')>Paiement</option>
                    <option value="refund" @selected(request('type') === 'refund')>Remboursement</option>
                </select>
            </div>
            <x-button type="submit" variant="primary">Filtrer</x-button>
        </form>
    </x-card>

    @if(($transactions ?? collect())->count() === 0)
        <x-card>
            <x-empty-state title="Aucune transaction" icon="💳" />
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['ID', 'Client', 'Facture', 'Montant', 'Type', 'Statut', 'Méthode', 'Date', '']">
                @foreach($transactions as $tx)
                    <tr>
                        <td style="font-family: var(--hc-font-mono); font-size: var(--hc-text-xs);">{{ substr($tx->transaction_id, 0, 16) }}…</td>
                        <td>{{ $tx->user?->first_name }} {{ $tx->user?->last_name }}</td>
                        <td>
                            @if($tx->invoice)
                                <a href="{{ route('admin.invoices.show', $tx->invoice) }}" style="color: var(--hc-primary); text-decoration: none;">
                                    {{ $tx->invoice->invoice_number }}
                                </a>
                            @else — @endif
                        </td>
                        <td><strong>{{ number_format($tx->amount, 2) }} €</strong></td>
                        <td>{{ ucfirst($tx->type ?? '—') }}</td>
                        <td>
                            <x-badge :variant="match($tx->status) {
                                'completed' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                'refunded' => 'neutral',
                                default => 'neutral'
                            }">{{ ucfirst($tx->status ?? '—') }}</x-badge>
                        </td>
                        <td>{{ ucfirst($tx->payment_method ?? '—') }}</td>
                        <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.transactions.show', $tx) }}" class="hc-btn hc-btn-ghost hc-btn-sm">
                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        <div style="margin-top: var(--hc-space-6);">
            {{ $transactions->links() }}
        </div>
    @endif
@endsection