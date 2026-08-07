@extends('layouts.admin')

@section('title', 'Commandes')

@section('content')
    <x-page-header title="Commandes" subtitle="Suivi des commandes et de leur statut" />

    {{-- Statistiques --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-6);">
        <x-stat label="Total commandes" :value="$stats['total'] ?? 0" icon="shopping-bag" color="primary" />
        <x-stat label="En attente" :value="$stats['pending'] ?? 0" icon="clock" color="warning" />
        <x-stat label="Payées" :value="$stats['paid'] ?? 0" icon="check-circle" color="success" />
        <x-stat label="Revenu total" :value="number_format($stats['revenue'] ?? 0, 2) . ' €'" icon="trending-up" color="info" />
    </div>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" class="hc-filters">
            <div class="hc-filters-field">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="N° commande, client..." class="hc-input">
            </div>
            <div class="hc-filters-field-fixed">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select">
                    <option value="">Tous</option>
                    <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                    <option value="completed" @selected(request('status') === 'completed')>Complétées</option>
                    <option value="paid" @selected(request('status') === 'paid')>Payées</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Annulées</option>
                </select>
            </div>
            <div class="hc-filters-actions">
                <x-button type="submit" variant="primary">
                    <i data-lucide="filter" style="width: 14px; height: 14px;"></i>
                    Filtrer
                </x-button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.orders.index') }}" class="hc-btn hc-btn-ghost">Réinitialiser</a>
                @endif
            </div>
        </form>
    </x-card>

    @if($orders->count() === 0)
        <x-card>
            <x-empty-state title="Aucune commande" description="Les commandes passées apparaîtront ici." icon="📦" />
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['N° commande', 'Client', 'Articles', 'Total', 'Statut', 'Paiement', 'Date', '']">
                @foreach($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" style="font-family: var(--hc-font-mono); font-weight: 600; color: var(--hc-primary); text-decoration: none;">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            @if($order->user)
                                <div style="display: flex; align-items: center; gap: var(--hc-space-2);">
                                    <div class="hc-avatar hc-avatar-sm hc-avatar-primary">
                                        {{ strtoupper(substr($order->user->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 500;">{{ $order->user->first_name }} {{ $order->user->last_name }}</div>
                                        <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $order->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <span style="color: var(--hc-text-muted);">—</span>
                            @endif
                        </td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                <i data-lucide="package" style="width: 14px; height: 14px; color: var(--hc-text-muted);"></i>
                                <span style="font-weight: 600;">{{ $order->items_count ?? $order->items->count() }}</span>
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 700; font-size: var(--hc-text-sm);">{{ number_format($order->total, 2) }} €</span>
                        </td>
                        <td>
                            <x-badge :variant="match($order->status) {
                                'completed', 'paid' => 'success',
                                'pending' => 'warning',
                                'cancelled', 'failed' => 'danger',
                                default => 'neutral'
                            }">{{ ucfirst($order->status) }}</x-badge>
                        </td>
                        <td>
                            <span style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ ucfirst($order->payment_method ?? '—') }}</span>
                        </td>
                        <td>
                            <div style="font-size: var(--hc-text-sm); font-weight: 500;">{{ $order->created_at->format('d/m/Y') }}</div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.orders.show', $order) }}" class="hc-btn hc-btn-ghost hc-btn-sm" title="Voir le détail">
                                <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        <div style="margin-top: var(--hc-space-6);">{{ $orders->links() }}</div>
    @endif
@endsection