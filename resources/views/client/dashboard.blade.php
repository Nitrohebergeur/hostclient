@extends('layouts.client')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 mb-8 text-white">
        <h1 class="text-3xl font-bold mb-2">Bienvenue, {{ auth()->user()->first_name }} !</h1>
        <p class="text-blue-100">Gérez vos services, factures et tickets de support depuis votre tableau de bord.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Services -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Services Actifs</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $stats['active_services'] ?? 0 }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="server" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <a href="{{ route('client.services.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline mt-3 block">
                Voir mes services →
            </a>
        </div>

        <!-- Unpaid Invoices -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Factures Impayées</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $stats['unpaid_invoices'] ?? 0 }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="file-text" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <a href="{{ route('client.invoices.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline mt-3 block">
                Voir mes factures →
            </a>
        </div>

        <!-- Open Tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tickets Ouverts</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $stats['open_tickets'] ?? 0 }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="message-circle" class="w-6 h-6 text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
            <a href="{{ route('client.tickets.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline mt-3 block">
                Voir mes tickets →
            </a>
        </div>

        <!-- Account Balance -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Solde du Compte</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format(auth()->user()->balance, 2) }} €
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="wallet" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <button class="text-sm text-blue-600 dark:text-blue-400 hover:underline mt-3 block">
                Recharger →
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Services -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Mes Services</h2>
            </div>
            <div class="p-6 space-y-4">
                @forelse($recentServices ?? [] as $service)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                <i data-lucide="server" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $service->product->name }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ucfirst($service->status) }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i data-lucide="server" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Vous n'avez pas encore de service</p>
                        <a href="{{ route('store.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i data-lucide="shopping-cart" class="w-4 h-4 mr-2"></i>
                            Commander un service
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Mes Factures</h2>
            </div>
            <div class="p-6 space-y-4">
                @forelse($recentInvoices ?? [] as $invoice)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/20 rounded-full flex items-center justify-center">
                                <i data-lucide="file-text" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Échéance: {{ $invoice->due_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($invoice->total, 2) }} €</p>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                {{ $invoice->status === 'paid' ? 'Payée' : 'Impayée' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i data-lucide="file-text" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">Aucune facture pour le moment</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
