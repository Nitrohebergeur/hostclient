@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Administrateur</h1>
        <p class="text-gray-600 dark:text-gray-400">Vue d'ensemble de votre plateforme</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Clients</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_clients'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Services Actifs</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['active_services'] }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <i data-lucide="server" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Commandes En Attente</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tickets Ouverts</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['open_tickets'] }}</p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <i data-lucide="message-circle" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Factures Impayées</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['unpaid_invoices'] }}</p>
                </div>
                <i data-lucide="file-text" class="w-8 h-8 text-orange-600 dark:text-orange-400"></i>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Revenus du Mois</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['monthly_revenue'], 2) }}€</p>
                </div>
                <i data-lucide="euro" class="w-8 h-8 text-green-600 dark:text-green-400"></i>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Inscriptions Aujourd'hui</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['today_signups'] }}</p>
                </div>
                <i data-lucide="user-plus" class="w-8 h-8 text-purple-600 dark:text-purple-400"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Chart -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Revenus des 30 derniers jours</h3>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Services Chart -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Répartition des Services</h3>
            <div class="h-64">
                <canvas id="servicesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Orders -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Commandes Récentes</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    Voir toutes →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="p-2 bg-white dark:bg-gray-800 rounded-lg">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->full_name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $order->total }}€</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">Aucune commande récente</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Tickets -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tickets Récents</h3>
                <a href="{{ route('admin.tickets.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    Voir tous →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentTickets as $ticket)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="p-2 bg-white dark:bg-gray-800 rounded-lg">
                                <i data-lucide="message-circle" class="w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $ticket->subject }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->user->full_name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info') }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">Aucun ticket récent</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($revenueChart['labels']),
        datasets: [{
            label: 'Revenus',
            data: @json($revenueChart['data']),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Services Chart
const servicesCtx = document.getElementById('servicesChart').getContext('2d');
new Chart(servicesCtx, {
    type: 'doughnut',
    data: {
        labels: @json($servicesChart['labels']),
        datasets: [{
            data: @json($servicesChart['data']),
            backgroundColor: [
                'rgb(34, 197, 94)',
                'rgb(249, 115, 22)',
                'rgb(239, 68, 68)',
                'rgb(156, 163, 175)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endpush
@endsection