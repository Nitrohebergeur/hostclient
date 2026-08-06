@extends('layouts.app')

@section('title', 'Mes Services')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mes Services</h1>
            <p class="text-gray-600 dark:text-gray-400">Gérez vos services d'hébergement</p>
        </div>
        <a href="{{ route('store.index') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Commander un service
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="p-4">
            <form method="GET" class="flex flex-wrap gap-4">
                <select name="status" class="input flex-1 min-w-[200px]" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                    <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Résilié</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Services Grid -->
    @if($services->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
                <div class="card hover:shadow-lg transition-shadow duration-200">
                    <div class="p-6">
                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="badge badge-{{ $service->status === 'active' ? 'success' : ($service->status === 'suspended' ? 'warning' : 'info') }}">
                                {{ ucfirst($service->status) }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $service->product->name }}</span>
                        </div>

                        <!-- Service Info -->
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $service->name }}</h3>
                        
                        @if($service->identifier)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                <i data-lucide="hash" class="w-4 h-4 inline mr-1"></i>
                                {{ $service->identifier }}
                            </p>
                        @endif

                        <!-- Pricing -->
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $service->price }}€</span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm">/{{ $service->billing_cycle }}</span>
                            </div>
                        </div>

                        <!-- Dates -->
                        @if($service->next_due_date)
                            <div class="mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                                    <span>Expire le {{ $service->next_due_date->format('d/m/Y') }}</span>
                                </div>
                                @if($service->next_due_date->isPast())
                                    <div class="text-sm text-red-600 dark:text-red-400 mt-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4 inline mr-1"></i>
                                        Service expiré
                                    </div>
                                @elseif($service->next_due_date->diffInDays() <= 7)
                                    <div class="text-sm text-orange-600 dark:text-orange-400 mt-1">
                                        <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i>
                                        Expire bientôt
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Auto Renew -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       {{ $service->auto_renew ? 'checked' : '' }}
                                       class="rounded text-primary-600 focus:ring-primary-500"
                                       disabled>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Renouvellement automatique</span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('client.services.show', $service) }}" class="btn-primary flex-1 text-center text-sm">
                                <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>
                                Gérer
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $services->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i data-lucide="server" class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun service</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Vous n'avez pas encore de services actifs.</p>
            <a href="{{ route('store.index') }}" class="btn-primary">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Commander votre premier service
            </a>
        </div>
    @endif
</div>
@endsection