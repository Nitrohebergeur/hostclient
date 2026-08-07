@extends('layouts.admin')

@section('title', 'Factures')

@section('content')
    <x-page-header title="Factures" subtitle="Suivi des factures émises et de leur règlement" />

    {{-- Statistiques --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-6);">
        <x-stat label="Total factures" :value="$stats['total'] ?? 0" icon="file-text" color="primary" />
        <x-stat label="Payées" :value="$stats['paid'] ?? 0" icon="check-circle" color="success" />
        <x-stat label="En attente" :value="$stats['pending'] ?? 0" icon="clock" color="warning" />
        <x-stat label="En retard" :value="$stats['overdue'] ?? 0" icon="alert-triangle" color="danger" />
    </div>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" class="hc-filters">
            <div class="hc-filters-field">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="N° facture, client..." class="hc-input">
            </div>
            <div class="hc-filters-field-fixed">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select">
                    <option value="">Tous</option>
                    <option value="paid" @selected(request('status') === 'paid')>Payées</option>
                    <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                    <option value="overdue" @selected(request('status') === 'overdue')>En retard</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Annulées</option>
                </select>
            </div>
            <div class="hc-filters-actions">
                <x-button type="submit" variant="primary">
                    <i data-lucide="filter" style="width: 14px; height: 14px;"></i>
                    Filtrer
                </x-button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.invoices.index') }}" class="hc-btn hc-btn-ghost">Réinitialiser</a>
                @endif
            </div>
        </form>
    </x-card>

    @if($invoices->count() === 0)
        <x-card>
            <x-empty-state title="Aucune facture" description="Les factures émises apparaîtront ici." icon="📄" />
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['N° facture', 'Client', 'Total', 'Payé', 'Solde', 'Échéance', 'Statut', '']">
                @foreach($invoices as $invoice)
                    <tr>
                        <td>
                            <a href="{{ route('admin.invoices.show', $invoice) }}" style="font-family: var(--hc-font-mono); font-weight: 600; color: var(--hc-primary); text-decoration: none;">
                                {{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td>
                            @if($invoice->user)
                                <div style="display: flex; align-items: center; gap: var(--hc-space-2);">
                                    <div class="hc-avatar hc-avatar-sm hc-avatar-primary">
                                        {{ strtoupper(substr($invoice->user->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 500;">{{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</div>
                                    </div>
                                </div>
                            @else
                                <span style="color: var(--hc-text-muted);">—</span>
                            @endif
                        </td>
                        <td><span style="font-weight: 600;">{{ number_format($invoice->total ?? 0, 2) }} €</span></td>
                        <td>
                            <span style="color: var(--hc-success); font-weight: 500;">{{ number_format($invoice->amount_paid ?? 0, 2) }} €</span>
                        </td>
                        <td>
                            @if(($invoice->balance ?? 0) > 0)
                                <span style="color: var(--hc-danger); font-weight: 700;">{{ number_format($invoice->balance, 2) }} €</span>
                            @else
                                <span style="color: var(--hc-text-muted);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($invoice->due_date && $invoice->due_date->isPast() && ($invoice->balance ?? 0) > 0)
                                <span style="color: var(--hc-danger); font-weight: 500;">{{ $invoice->due_date->format('d/m/Y') }}</span>
                            @else
                                <span style="color: var(--hc-text-muted);">{{ $invoice->due_date?->format('d/m/Y') ?? '—' }}</span>
                            @endif
                        </td>
                        <td>
                            <x-badge :variant="match($invoice->status) {
                                'paid' => 'success',
                                'pending', 'sent' => 'warning',
                                'overdue' => 'danger',
                                'cancelled' => 'neutral',
                                default => 'neutral'
                            }">{{ ucfirst($invoice->status ?? 'pending') }}</x-badge>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="hc-btn hc-btn-ghost hc-btn-sm" title="Voir le détail">
                                <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        <div style="margin-top: var(--hc-space-6);">{{ $invoices->links() }}</div>
    @endif
@endsection