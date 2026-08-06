@extends('layouts.app')

@section('title', 'Dashboard Client')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Active Services -->
        <div class="card animate-slide-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Services Actifs</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['active_services'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <i data-lucide="server" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="card animate-slide-up" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Commandes</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>

        <!-- Unpaid Invoices -->
        <div class="card animate-slide-up" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Factures Impayées</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['unpaid_invoices'] }}</p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <i data-lucide="file-text" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <!-- Open Tickets -->
        <div class="card animate-slide-up" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tickets Ouverts</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['open_tickets'] }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <i data-lucide="message-circle" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <!-- Balance -->
        <div class="card animate-slide-up" style="animation-delay: 0.4s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Solde</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['balance'], 2) }}€</p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                    <i data-lucide="wallet" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Services -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Services Récents</h2>
                    <a href="{{ route('client.services.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Voir tous →
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($recentServices as $service)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <div class="flex items-center space-x-4">
                                <div class="p-2 bg-white dark:bg-gray-800 rounded-lg">
                                    <i data-lucide="server" class="w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $service->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $service->product->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-{{ $service->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($service->status) }}
                                </span>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $service->price }}€/{{ $service->billing_cycle }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Aucun service</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Activity Feed -->
        <div>
            <div class="card">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Activité Récente</h2>

                <div class="space-y-4">
                    @foreach($activities as $activity)
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-full">
                                    <i data-lucide="{{ $activity['icon'] }}" class="w-4 h-4 text-primary-600 dark:text-primary-400"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['title'] }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $activity['date']->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
