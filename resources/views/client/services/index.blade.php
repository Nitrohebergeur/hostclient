@extends('layouts.client')

@section('title', 'Mes Services')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Mes Services</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gérez tous vos services d'hébergement</p>
        </div>
        <a href="/client/orders/new" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Commander un service
        </a>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Rechercher un service..." class="form-input pl-10">
                </div>
                <!-- Filter by type -->
                <select class="form-input w-full sm:w-48">
                    <option value="">Tous les types</option>
                    <option value="hosting">Hébergement Web</option>
                    <option value="vps">VPS</option>
                    <option value="dedicated">Serveur Dédié</option>
                    <option value="game">Serveur de Jeux</option>
                    <option value="domain">Domaine</option>
                </select>
                <!-- Filter by status -->
                <select class="form-input w-full sm:w-40">
                    <option value="">Tous les statuts</option>
                    <option value="active">Actif</option>
                    <option value="suspended">Suspendu</option>
                    <option value="cancelled">Résilié</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">

        <!-- Service Card: Hosting -->
        <div class="card hover:shadow-lg transition-all group" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Hébergement Premium</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">monsite.com</p>
                        </div>
                    </div>
                    <span class="badge badge-success">Actif</span>
                </div>

                <!-- Resources -->
                <div class="space-y-3 mb-4">
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Stockage (SSD)</span>
                            <span>4.2 Go / 20 Go</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-2 bg-primary-500 rounded-full" style="width: 21%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Bande Passante</span>
                            <span>12 Go / 100 Go</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-2 bg-success-500 rounded-full" style="width: 12%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Bases de données</span>
                            <span>3 / 10</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-2 bg-secondary-500 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Renouvellement: 15/03/2024
                    </span>
                    <span class="font-bold text-gray-900 dark:text-white">19,99 €/mois</span>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <a href="/client/services/1" class="btn btn-primary flex-1">Gérer</a>
                    <div x-data="dropdown" class="relative">
                        <button @click="toggle" class="btn btn-secondary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="close" x-transition class="absolute right-0 bottom-full mb-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10">
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Renouveler
                            </a>
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                Mettre à niveau
                            </a>
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-warning-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Suspendre
                            </a>
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-danger-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Résilier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Card: VPS -->
        <div class="card hover:shadow-lg transition-all group" data-aos="fade-up" data-aos-delay="200">
            <div class="card-body">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-secondary-100 dark:bg-secondary-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">VPS Cloud Standard</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">192.168.1.100</p>
                        </div>
                    </div>
                    <span class="badge badge-success">Actif</span>
                </div>

                <div class="space-y-3 mb-4">
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>CPU</span>
                            <span>35%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-2 bg-success-500 rounded-full" style="width: 35%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>RAM</span>
                            <span>1.8 Go / 4 Go</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-2 bg-warning-500 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Disque SSD</span>
                            <span>22 Go / 80 Go</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-2 bg-primary-500 rounded-full" style="width: 27%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Renouvellement: 20/03/2024
                    </span>
                    <span class="font-bold text-gray-900 dark:text-white">29,99 €/mois</span>
                </div>

                <div class="flex gap-2">
                    <a href="/client/services/2" class="btn btn-primary flex-1">Gérer</a>
                    <button class="btn btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Service Card: Game Server -->
        <div class="card hover:shadow-lg transition-all group" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Minecraft — Survie</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">play.monserveur.com</p>
                        </div>
                    </div>
                    <span class="badge badge-warning">Suspendu</span>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2">
                            <p class="text-lg font-bold text-gray-900 dark:text-white">0/20</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Joueurs</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2">
                            <p class="text-lg font-bold text-gray-900 dark:text-white">4 Go</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">RAM</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2">
                            <p class="text-lg font-bold text-gray-900 dark:text-white">50 Go</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Stockage</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <span class="flex items-center gap-1 text-warning-600 dark:text-warning-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Facture impayée
                    </span>
                    <span class="font-bold text-gray-900 dark:text-white">9,99 €/mois</span>
                </div>

                <div class="flex gap-2">
                    <button class="btn btn-warning flex-1">Payer & Réactiver</button>
                    <button class="btn btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
