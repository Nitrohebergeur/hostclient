@extends('layouts.admin')

@section('title', 'Transaction')
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.transactions.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux transactions
        </a>
    </div>

    <x-page-header title="Transaction {{ $transaction->transaction_id }}">
        <x-slot:actions>
            <x-badge :variant="match($transaction->status) {
                'completed' => 'success',
                'pending' => 'warning',
                'failed' => 'danger',
                default => 'neutral'
            }">{{ ucfirst($transaction->status) }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);">
        <x-card header="Détails">
            <dl style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--hc-space-4); margin: 0;">
                <div>
                    <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; margin-bottom: var(--hc-space-1);">ID transaction</dt>
                    <dd style="margin: 0; font-family: var(--hc-font-mono); font-size: var(--hc-text-sm);">{{ $transaction->transaction_id }}</dd>
                </div>
                <div>
                    <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; margin-bottom: var(--hc-space-1);">Montant</dt>
                    <dd style="margin: 0; font-size: var(--hc-text-lg); font-weight: 700;">{{ number_format($transaction->amount, 2) }} €</dd>
                </div>
                <div>
                    <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; margin-bottom: var(--hc-space-1);">Type</dt>
                    <dd style="margin: 0; font-weight: 500;">{{ ucfirst($transaction->type ?? '—') }}</dd>
                </div>
                <div>
                    <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; margin-bottom: var(--hc-space-1);">Méthode</dt>
                    <dd style="margin: 0; font-weight: 500;">{{ ucfirst($transaction->payment_method ?? '—') }}</dd>
                </div>
                <div>
                    <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; margin-bottom: var(--hc-space-1);">Date</dt>
                    <dd style="margin: 0;">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</dd>
                </div>
                <div>
                    <dt style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); text-transform: uppercase; margin-bottom: var(--hc-space-1);">Passerelle</dt>
                    <dd style="margin: 0; font-weight: 500;">{{ $transaction->gateway?->name ?? '—' }}</dd>
                </div>
            </dl>
        </x-card>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">
            <x-card header="Client">
                @if($transaction->user)
                    <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                        <div style="width: 40px; height: 40px; background: var(--hc-primary-50); color: var(--hc-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            {{ strtoupper(substr($transaction->user->first_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ $transaction->user->first_name }} {{ $transaction->user->last_name }}</div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $transaction->user->email }}</div>
                        </div>
                    </div>
                @endif
            </x-card>

            @if($transaction->invoice)
                <x-card header="Facture">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-family: var(--hc-font-mono);">{{ $transaction->invoice->invoice_number }}</span>
                        <x-button :href="route('admin.invoices.show', $transaction->invoice)" variant="secondary" size="sm">Voir</x-button>
                    </div>
                </x-card>
            @endif

            <x-card>
                <form method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}" onsubmit="return confirm('Supprimer cette transaction ?')">
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
@endsection