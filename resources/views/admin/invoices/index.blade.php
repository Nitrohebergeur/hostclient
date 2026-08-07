@extends('layouts.admin')

@section('title', 'Factures')

@section('content')
    <x-page-header title="Factures" />

    @if($invoices->count() === 0)
        <x-card>
            <x-empty-state title="Aucune facture" description="Les factures émises apparaîtront ici." icon="📄" />
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['N° facture', 'Client', 'Total', 'Payé', 'Solde', 'Échéance', 'Statut', '']">
                @foreach($invoices as $invoice)
                    <tr>
                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        <td>{{ $invoice->user?->first_name ?? '—' }} {{ $invoice->user?->last_name ?? '' }}</td>
                        <td>{{ number_format($invoice->total ?? 0, 2) }} €</td>
                        <td>{{ number_format($invoice->amount_paid ?? 0, 2) }} €</td>
                        <td>
                            @if(($invoice->balance ?? 0) > 0)
                                <strong style="color: var(--hc-danger);">{{ number_format($invoice->balance, 2) }} €</strong>
                            @else
                                <span style="color: var(--hc-text-muted);">—</span>
                            @endif
                        </td>
                        <td>{{ $invoice->due_date?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            <x-badge :variant="match($invoice->status) {
                                'paid' => 'success',
                                'pending', 'sent' => 'warning',
                                'overdue', 'cancelled' => 'danger',
                                default => 'neutral'
                            }">{{ ucfirst($invoice->status ?? 'pending') }}</x-badge>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="hc-btn hc-btn-ghost hc-btn-sm">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        <div style="margin-top: var(--hc-space-6);">{{ $invoices->links() }}</div>
    @endif
@endsection