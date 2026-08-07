@extends('layouts.admin')

@section('title', 'Commandes')

@section('content')
    <x-page-header title="Commandes" />

    @if($orders->count() === 0)
        <x-card>
            <x-empty-state title="Aucune commande" description="Les commandes passées apparaîtront ici." icon="📦" />
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['N° commande', 'Client', 'Articles', 'Total', 'Statut', 'Paiement', 'Date', '']">
                @foreach($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->user?->first_name ?? '—' }} {{ $order->user?->last_name ?? '' }}</td>
                        <td>{{ $order->items_count ?? $order->items->count() }}</td>
                        <td><strong>{{ number_format($order->total, 2) }} €</strong></td>
                        <td>
                            <x-badge :variant="match($order->status) {
                                'completed', 'paid' => 'success',
                                'pending' => 'warning',
                                'cancelled', 'failed' => 'danger',
                                default => 'neutral'
                            }">{{ ucfirst($order->status) }}</x-badge>
                        </td>
                        <td>{{ ucfirst($order->payment_method ?? '—') }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.orders.show', $order) }}" class="hc-btn hc-btn-ghost hc-btn-sm">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        <div style="margin-top: var(--hc-space-6);">{{ $orders->links() }}</div>
    @endif
@endsection