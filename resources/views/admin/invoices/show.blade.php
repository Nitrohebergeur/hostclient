@extends('layouts.admin')

@section('title', 'Facture ' . $invoice->invoice_number)
@section('content')
    <div class="hc-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.invoices.index') }}">Factures</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <span class="hc-breadcrumb-current">{{ $invoice->invoice_number }}</span>
    </div>

    <x-page-header :title="'Facture ' . $invoice->invoice_number" :subtitle="'Émise le ' . $invoice->issue_date?->format('d/m/Y')">
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

    <div class="hc-info-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Articles facturés" :padding="false">
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
                                <td><x-badge variant="neutral">{{ $item->quantity }}</x-badge></td>
                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                <td style="text-align: right; font-weight: 700;">{{ number_format($item->total, 2) }} €</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--hc-text-muted); padding: var(--hc-space-8);">
                                    <div class="hc-empty-state" style="padding: 0;">
                                        <div class="hc-empty-icon">📄</div>
                                        <p>Aucun article</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

            <x-card header="Modifier la facture">
                <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-5);">
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

                    <div style="margin-bottom: var(--hc-space-5);">
                        <label class="hc-label">Notes</label>
                        <textarea name="notes" class="hc-textarea" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>

                    <x-button type="submit" variant="primary">
                        <i data-lucide="save" style="width: 14px; height: 14px;"></i>
                        Enregistrer
                    </x-button>
                </form>
            </x-card>
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            <x-card header="Récapitulatif">
                <dl class="hc-dl">
                    <div class="hc-dl-row">
                        <dt class="hc-dl-label">Sous-total</dt>
                        <dd class="hc-dl-value">{{ number_format($invoice->subtotal, 2) }} €</dd>
                    </div>
                    @if($invoice->discount > 0)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Remise</dt>
                            <dd class="hc-dl-value" style="color: var(--hc-success);">-{{ number_format($invoice->discount, 2) }} €</dd>
                        </div>
                    @endif
                    @if($invoice->tax > 0)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">TVA ({{ number_format($invoice->tax_rate, 1) }}%)</dt>
                            <dd class="hc-dl-value">{{ number_format($invoice->tax, 2) }} €</dd>
                        </div>
                    @endif
                    <div class="hc-dl-row" style="border-top: 1px solid var(--hc-border); margin-top: var(--hc-space-2); padding-top: var(--hc-space-4);">
                        <dt style="font-weight: 700; color: var(--hc-text);">Total</dt>
                        <dd style="font-size: var(--hc-text-xl); font-weight: 700; color: var(--hc-primary);">{{ number_format($invoice->total, 2) }} €</dd>
                    </div>
                    @if($invoice->amount_paid > 0)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label" style="color: var(--hc-success);">Payé</dt>
                            <dd class="hc-dl-value" style="color: var(--hc-success);">{{ number_format($invoice->amount_paid, 2) }} €</dd>
                        </div>
                        @if($invoice->balance > 0)
                            <div class="hc-dl-row">
                                <dt class="hc-dl-label" style="color: var(--hc-danger);">Solde dû</dt>
                                <dd class="hc-dl-value" style="color: var(--hc-danger);">{{ number_format($invoice->balance, 2) }} €</dd>
                            </div>
                        @endif
                    @endif
                </dl>
            </x-card>

            @if($invoice->user)
            <x-card>
                <x-slot:header>
                    <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                        <div class="hc-avatar hc-avatar-primary">
                            {{ strtoupper(substr($invoice->user->first_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: var(--hc-text-sm); font-weight: 600;">{{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</h3>
                            <p style="margin: 2px 0 0;">{{ $invoice->user->email }}</p>
                        </div>
                    </div>
                </x-slot:header>
                <x-button :href="route('admin.clients.show', $invoice->user)" variant="secondary" style="width: 100%;">
                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                    Voir le client
                </x-button>
            </x-card>
            @endif

            <x-card header="Transactions" :padding="false">
                @forelse($invoice->transactions ?? [] as $tx)
                    <div class="hc-activity-item">
                        <div class="hc-activity-icon" style="background: var(--hc-success-50); color: var(--hc-success);">
                            <i data-lucide="credit-card"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: var(--hc-space-2);">
                                <span style="font-weight: 600; font-size: var(--hc-text-sm);">{{ number_format($tx->amount, 2) }} €</span>
                                <x-badge :variant="match($tx->status ?? 'completed') {
                                    'completed' => 'success',
                                    'pending' => 'warning',
                                    'failed' => 'danger',
                                    default => 'neutral'
                                }">{{ ucfirst($tx->status ?? 'completed') }}</x-badge>
                            </div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: 2px;">{{ $tx->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="hc-empty-state">
                        <div class="hc-empty-icon">💳</div>
                        <p>Aucune transaction enregistrée</p>
                    </div>
                @endforelse
            </x-card>

            <x-card header="Actions">
                <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" onsubmit="return confirm('Supprimer définitivement cette facture ?')">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" style="width: 100%;">
                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                        Supprimer la facture
                    </x-button>
                </form>
            </x-card>
        </div>
    </div>
@endsection