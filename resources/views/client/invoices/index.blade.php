@extends('layouts.client')

@section('title', 'Mes factures')
@section('subtitle', 'Historique et état de vos factures')

@section('content')
    <x-page-header title="Mes factures" />

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" style="padding: var(--hc-space-4); display: flex; gap: var(--hc-space-3); flex-wrap: wrap;">
            <div style="min-width: 200px;">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="paid" @selected(request('status') === 'paid')>Payée</option>
                    <option value="unpaid" @selected(request('status') === 'unpaid')>Impayée</option>
                    <option value="partially_paid" @selected(request('status') === 'partially_paid')>Partiellement payée</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Annulée</option>
                </select>
            </div>
        </form>
    </x-card>

    @if($invoices->count())
        <x-card padding="false">
            <table class="hc-table">
                <thead>
                    <tr>
                        <th>N° de facture</th>
                        <th>Date d'émission</th>
                        <th>Échéance</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td style="font-family: var(--hc-font-mono); font-weight: 500;">{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->issue_date?->format('d/m/Y') }}</td>
                            <td>
                                @if($invoice->due_date)
                                    <span style="{{ $invoice->isOverdue() ? 'color: var(--hc-danger); font-weight: 500;' : '' }}">
                                        {{ $invoice->due_date->format('d/m/Y') }}
                                    </span>
                                @else — @endif
                            </td>
                            <td><strong>{{ number_format($invoice->total, 2) }} €</strong></td>
                            <td>
                                <x-badge :variant="match($invoice->status) {
                                    'paid' => 'success',
                                    'unpaid' => 'danger',
                                    'partially_paid' => 'warning',
                                    'cancelled' => 'neutral',
                                    default => 'neutral'
                                }">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</x-badge>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('client.invoices.show', $invoice) }}" class="hc-btn hc-btn-ghost hc-btn-sm">Voir</a>
                                <a href="{{ route('client.invoices.download', $invoice) }}" class="hc-btn hc-btn-ghost hc-btn-sm">
                                    <i data-lucide="download" style="width: 14px; height: 14px;"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>

        <div style="margin-top: var(--hc-space-6);">
            {{ $invoices->links() }}
        </div>
    @else
        <x-card>
            <x-empty-state
                title="Aucune facture"
                description="Vous n'avez pas encore de facture."
                icon="🧾"
            />
        </x-card>
    @endif
@endsection