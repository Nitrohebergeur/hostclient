@extends('layouts.client')

@section('title', 'Détail du Service')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="/client/services" class="hover:text-primary-600 dark:hover:text-primary-400">Services</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white">Hébergement Premium</span>
    </nav>

    <!-- Service Header -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col md:flex-row md:items-center gap-4 justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center">
                        <svg class="w-9 h-9 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Hébergement Premium</h2>
                            <span class="badge badge-success">Actif</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">monsite.com · Créé le 15 janvier 2024</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button class="btn btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Renouveler
                    </button>
                    <button class="btn btn-danger">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Résilier
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ tab: 'overview' }">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex gap-0 -mb-px">
                <button @click="tab = 'overview'" :class="tab === 'overview' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">Vue d'ensemble</button>
                <button @click="tab = 'config'" :class="tab === 'config' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">Configuration</button>
                <button @click="tab = 'logs'" :class="tab === 'logs' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">Logs</button>
                <button @click="tab = 'billing'" :class="tab === 'billing' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">Facturation</button>
            </nav>
        </div>

        <!-- Overview Tab -->
        <div x-show="tab === 'overview'" class="mt-6 space-y-6">
            <!-- Resource usage -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="relative w-24 h-24 mx-auto mb-3">
                            <svg class="w-24 h-24 -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#0ea5e9" stroke-width="3" stroke-dasharray="21 79" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">21%</span>
                            </div>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white">Stockage SSD</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">4.2 Go / 20 Go</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <div class="relative w-24 h-24 mx-auto mb-3">
                            <svg class="w-24 h-24 -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#22c55e" stroke-width="3" stroke-dasharray="12 88" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">12%</span>
                            </div>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white">Bande Passante</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">12 Go / 100 Go</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Informations</h4>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Serveur</dt>
                                <dd class="text-gray-900 dark:text-white font-medium">fr-par-01</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Adresse IP</dt>
                                <dd class="text-gray-900 dark:text-white font-medium">51.158.65.200</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">PHP</dt>
                                <dd class="text-gray-900 dark:text-white font-medium">8.3</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">MySQL</dt>
                                <dd class="text-gray-900 dark:text-white font-medium">8.0</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Panel</dt>
                                <dd><a href="#" class="text-primary-600 dark:text-primary-400">cPanel →</a></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Credentials -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Accès FTP / SSH</h3>
                </div>
                <div class="card-body">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <label class="form-label text-xs">Hôte FTP</label>
                                <div class="flex gap-2">
                                    <input type="text" value="ftp.monsite.com" readonly class="form-input bg-gray-50 dark:bg-gray-700/50">
                                    <button onclick="copyToClipboard('ftp.monsite.com')" class="btn btn-secondary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="form-label text-xs">Utilisateur</label>
                                <div class="flex gap-2">
                                    <input type="text" value="monsite_user" readonly class="form-input bg-gray-50 dark:bg-gray-700/50">
                                    <button class="btn btn-secondary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="form-label text-xs">Mot de passe</label>
                                <div class="flex gap-2">
                                    <input type="password" value="secretpassword" readonly class="form-input bg-gray-50 dark:bg-gray-700/50">
                                    <button class="btn btn-secondary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="alert alert-info">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-sm">Pour changer votre mot de passe, accédez au cPanel via le bouton ci-dessus ou ouvrez un ticket de support.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Config Tab -->
        <div x-show="tab === 'config'" class="mt-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Configuration du Service</h3>
                </div>
                <div class="card-body">
                    <p class="text-gray-500 dark:text-gray-400">Aucune configuration personnalisée disponible pour ce type de service.</p>
                </div>
            </div>
        </div>

        <!-- Logs Tab -->
        <div x-show="tab === 'logs'" class="mt-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Historique des Actions</h3>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach([
                            ['Service activé', '15 jan. 2024 09:00', 'success'],
                            ['Paiement reçu — 19,99 €', '15 jan. 2024 08:55', 'info'],
                            ['Commande créée', '15 jan. 2024 08:50', 'info'],
                        ] as $log)
                        <div class="flex items-center gap-4 p-4">
                            <span class="flex-shrink-0 w-2 h-2 rounded-full {{ $log[2] === 'success' ? 'bg-success-500' : 'bg-primary-500' }}"></span>
                            <span class="flex-1 text-sm text-gray-900 dark:text-white">{{ $log[0] }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $log[1] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Tab -->
        <div x-show="tab === 'billing'" class="mt-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Informations de Facturation</h3>
                </div>
                <div class="card-body">
                    <dl class="grid md:grid-cols-2 gap-4 text-sm">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <dt class="text-gray-500 dark:text-gray-400 mb-1">Prix mensuel</dt>
                            <dd class="text-xl font-bold text-gray-900 dark:text-white">19,99 €</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <dt class="text-gray-500 dark:text-gray-400 mb-1">Prochain renouvellement</dt>
                            <dd class="text-xl font-bold text-gray-900 dark:text-white">15 mars 2024</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <dt class="text-gray-500 dark:text-gray-400 mb-1">Cycle de facturation</dt>
                            <dd class="text-xl font-bold text-gray-900 dark:text-white">Mensuel</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <dt class="text-gray-500 dark:text-gray-400 mb-1">Renouvellement automatique</dt>
                            <dd class="text-xl font-bold text-success-600 dark:text-success-400">Activé</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
