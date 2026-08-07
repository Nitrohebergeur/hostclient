@extends('layouts.client')

@section('title', 'Mes commandes')
@section('subtitle', 'Historique de vos commandes')

@section('content')
    <x-page-header title="Mes commandes">
        <x-slot:actions>
            <x-button :href="route('store.index')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouvelle commande
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" style="padding: var(--hc-space-4); display: flex; gap: var(--hc-space-3); flex-wrap: wrap;">
            <div style="min-width: 200px;">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                    <option value="processing" @selected(request('status') === 'processing')>En cours</option>
                    <option value="completed" @selected(request('status') === 'completed')>Terminée</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Annulée</option>
                </select>
            </div>
        </form>
    </x-card>

    @if($orders->count())
        <x-card padding="false">
            <table class="hc-table">
                <thead>
                    <tr>
                        <th>N° de commande</th>
                        <th>Date</th>
                        <th>Articles</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="font-family: var(--hc-font-mono); font-weight: 500;">{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>{{ $order->items->count() }} article{{ $order->items->count() > 1 ? 's' : '' }}</td>
                            <td><strong>{{ number_format($order->total, 2) }} €</strong></td>
                            <td>
                                <x-badge :variant="match($order->status) {
                                    'completed' => 'success',
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'cancelled' => 'neutral',
                                    default => 'neutral'
                                }">{{ ucfirst($order->status) }}</x-badge>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('client.orders.show', $order) }}" class="hc-btn hc-btn-ghost hc-btn-sm">Détails</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>

        <div style="margin-top: var(--hc-space-6);">
            {{ $orders->links() }}
        </div>
    @else
        <x-card>
            <x-empty-state
                title="Aucune commande"
                description="Vous n'avez pas encore passé de commande."
                icon="📦"
            >
                <x-button :href="route('store.index')" variant="primary">Découvrir nos offres</x-button>
            </x-empty-state>
        </x-card>
    @endif
@endsection