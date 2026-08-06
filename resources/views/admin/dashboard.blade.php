@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Tableau de bord')

@section('content')
    @if($customHtml && $customCss)
        <!-- Page d'accueil personnalisée -->
        <style>
            {!! $customCss !!}
        </style>
        
        <div class="custom-homepage-wrapper">
            {!! $customHtml !!}
        </div>
    @else
        <!-- Dashboard par défaut -->

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Clients -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Clients Totaux</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $stats['total_clients'] ?? 0 }}
                    </p>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                        +{{ $stats['new_clients_this_month'] ?? 0 }} ce mois
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <!-- Active Services -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Services Actifs</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $stats['active_services'] ?? 0 }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $stats['pending_services'] ?? 0 }} en attente
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="server" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <!-- Revenue This Month -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenu ce mois</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($stats['revenue_this_month'] ?? 0, 2) }} €
                    </p>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                        +{{ $stats['revenue_growth'] ?? 0 }}% vs mois dernier
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>

        <!-- Pending Tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tickets Ouverts</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $stats['open_tickets'] ?? 0 }}
                    </p>
                    <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">
                        {{ $stats['urgent_tickets'] ?? 0 }} urgents
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="message-circle" class="w-6 h-6 text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Revenus (30 derniers jours)</h3>
            <div class="h-64 flex items-center justify-center text-gray-400">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Commandes Récentes</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    Voir tout
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recent_orders ?? [] as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                <i data-lucide="shopping-bag" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user->full_name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($order->total, 2) }} €</p>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">Aucune commande récente</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Unpaid Invoices -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Factures Impayées</h3>
                <a href="{{ route('admin.invoices.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    Voir tout
                </a>
            </div>
            <div class="space-y-3">
                @forelse($unpaid_invoices ?? [] as $invoice)
                    <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/10 rounded-lg border border-red-200 dark:border-red-800/30">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
                                <i data-lucide="file-text" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $invoice->user->full_name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($invoice->balance, 2) }} €</p>
                            <p class="text-xs text-red-600 dark:text-red-400">Échéance: {{ $invoice->due_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">Aucune facture impayée</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tickets Récents</h3>
                <a href="{{ route('admin.tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    Voir tout
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recent_tickets ?? [] as $ticket)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center">
                                <i data-lucide="message-circle" class="w-5 h-5 text-orange-600 dark:text-orange-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $ticket->subject }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->user->full_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300' }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">Aucun ticket récent</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chart_labels ?? []),
                datasets: [{
                    label: 'Revenus',
                    data: @json($chart_data ?? []),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' €';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
