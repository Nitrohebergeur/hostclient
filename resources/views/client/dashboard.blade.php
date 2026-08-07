@extends('layouts.client')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Section -->
    <div class="card" data-aos="fade-up">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Bienvenue, {{ auth()->user()->name ?? 'Client' }} ! 👋
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">
                        Voici un aperçu de vos services et activités récentes.
                    </p>
                </div>
                <a href="/client/orders/new" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nouvelle Commande
                </a>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Services -->
        <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Services Actifs</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">12</p>
                        <p class="text-sm text-success-600 dark:text-success-400 mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span>+2 ce mois</span>
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pending Invoices -->
        <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="200">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Factures en Attente</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">3</p>
                        <p class="text-sm text-warning-600 dark:text-warning-400 mt-2">
                            Total: 89,97 €
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-warning-100 dark:bg-warning-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Support Tickets -->
        <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Tickets Ouverts</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">2</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            1 en attente de réponse
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-secondary-100 dark:bg-secondary-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Domains -->
        <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Domaines</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">8</p>
                        <p class="text-sm text-danger-600 dark:text-danger-400 mt-2">
                            2 expirent bientôt
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-success-100 dark:bg-success-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid lg:grid-cols-2 gap-6">
        
        <!-- Recent Services -->
        <div class="card" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Services Récents</h3>
                    <a href="/client/services" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Voir tout</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Service 1 -->
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Hébergement Web Premium</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">monsite.com</p>
                                </div>
                            </div>
                            <span class="badge badge-success">Actif</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Renouvellement: 15 mars 2024</span>
                            <span class="font-medium text-gray-900 dark:text-white">19,99 €/mois</span>
                        </div>
                    </div>
                    
                    <!-- Service 2 -->
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-secondary-100 dark:bg-secondary-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">VPS Cloud Standard</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">192.168.1.100</p>
                                </div>
                            </div>
                            <span class="badge badge-success">Actif</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Renouvellement: 20 mars 2024</span>
                            <span class="font-medium text-gray-900 dark:text-white">29,99 €/mois</span>
                        </div>
                    </div>
                    
                    <!-- Service 3 -->
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-success-100 dark:bg-success-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Domaine .com</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">monsite.com</p>
                                </div>
                            </div>
                            <span class="badge badge-warning">Expire bientôt</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Expiration: 05 mars 2024</span>
                            <span class="font-medium text-gray-900 dark:text-white">12,99 €/an</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Invoices -->
        <div class="card" data-aos="fade-up" data-aos-delay="600">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Factures Récentes</h3>
                    <a href="/client/invoices" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Voir tout</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Invoice 1 -->
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">#INV-2024-003</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">01 février 2024</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900 dark:text-white">29,99 €</p>
                                <span class="badge badge-warning">En attente</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn btn-primary btn-sm flex-1">Payer</button>
                            <button class="btn btn-secondary btn-sm">PDF</button>
                        </div>
                    </div>
                    
                    <!-- Invoice 2 -->
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">#INV-2024-002</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">15 janvier 2024</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900 dark:text-white">19,99 €</p>
                                <span class="badge badge-success">Payée</span>
                            </div>
                        </div>
                        <button class="btn btn-secondary btn-sm w-full">Télécharger PDF</button>
                    </div>
                    
                    <!-- Invoice 3 -->
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">#INV-2024-001</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">01 janvier 2024</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900 dark:text-white">39,99 €</p>
                                <span class="badge badge-success">Payée</span>
                            </div>
                        </div>
                        <button class="btn btn-secondary btn-sm w-full">Télécharger PDF</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Recent Activity -->
    <div class="card" data-aos="fade-up" data-aos-delay="700">
        <div class="card-header">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Activité Récente</h3>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                <!-- Activity 1 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-success-100 dark:bg-success-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Paiement reçu</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Facture #INV-2024-002 payée avec succès (19,99 €)</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Il y a 2 heures</p>
                    </div>
                </div>
                
                <!-- Activity 2 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Réponse au ticket</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Le support a répondu à votre ticket #TKT-1234</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Il y a 5 heures</p>
                    </div>
                </div>
                
                <!-- Activity 3 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-warning-100 dark:bg-warning-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Domaine expire bientôt</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Le domaine monsite.com expire dans 5 jours</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Il y a 1 jour</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vous pouvez ajouter ici des graphiques Chart.js
});
</script>
@endpush
