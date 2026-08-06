@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="bg-gradient-to-br from-primary-50 to-secondary-50 dark:from-gray-900 dark:to-gray-800 min-h-screen">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                {{ config('hostclient.company_name', 'HostClient') }}
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                Panel client moderne pour hébergeurs web. Gérez vos services, factures et support en toute simplicité.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('client.dashboard') }}" class="btn-primary">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-2"></i>
                        Tableau de bord
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary">
                        <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                        Créer un compte
                    </a>
                    <a href="{{ route('login') }}" class="btn-outline">
                        <i data-lucide="log-in" class="w-5 h-5 mr-2"></i>
                        Se connecter
                    </a>
                @endauth
                
                <a href="{{ route('store.index') }}" class="btn-secondary">
                    <i data-lucide="shopping-cart" class="w-5 h-5 mr-2"></i>
                    Voir nos services
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                Pourquoi choisir {{ config('hostclient.company_name') }} ?
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Une solution complète pour la gestion de vos services d'hébergement
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="card text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full mb-4">
                    <i data-lucide="server" class="w-8 h-8 text-primary-600 dark:text-primary-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Gestion des services</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Gérez tous vos services d'hébergement depuis une interface intuitive et moderne.
                </p>
            </div>

            <div class="card text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full mb-4">
                    <i data-lucide="credit-card" class="w-8 h-8 text-green-600 dark:text-green-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Facturation automatisée</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Facturation automatique, rappels de paiement et intégration avec les principales passerelles.
                </p>
            </div>

            <div class="card text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-full mb-4">
                    <i data-lucide="headphones" class="w-8 h-8 text-purple-600 dark:text-purple-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Support intégré</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Système de tickets complet avec suivi en temps réel et notifications automatiques.
                </p>
            </div>

            <div class="card text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full mb-4">
                    <i data-lucide="shield-check" class="w-8 h-8 text-blue-600 dark:text-blue-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Sécurisé</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Authentification 2FA, chiffrement des données et logs d'activité complets.
                </p>
            </div>

            <div class="card text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 dark:bg-yellow-900/30 rounded-full mb-4">
                    <i data-lucide="zap" class="w-8 h-8 text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Provisionnement automatique</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Activation instantanée de vos services grâce aux modules d'automatisation.
                </p>
            </div>

            <div class="card text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full mb-4">
                    <i data-lucide="code" class="w-8 h-8 text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">API REST</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    API complète pour intégrer vos services avec vos outils existants.
                </p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-primary-600 dark:bg-primary-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white mb-4">
                    Prêt à commencer ?
                </h2>
                <p class="text-xl text-primary-100 mb-8">
                    Rejoignez des milliers de clients qui font confiance à notre plateforme.
                </p>
                
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-primary-600 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                        <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        Créer un compte gratuit
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection