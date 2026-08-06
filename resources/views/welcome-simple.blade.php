@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
        <div class="text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 dark:text-white mb-6">
                {{ config('hostclient.company_name', 'HostClient') }}
            </h1>
            <p class="text-2xl text-gray-600 dark:text-gray-300 mb-12 max-w-3xl mx-auto">
                Hébergement Web Professionnel
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('client.dashboard') }}" 
                       class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-lg font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition">
                        📊 Mon Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-lg font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition">
                        🚀 Créer un compte
                    </a>
                    <a href="{{ route('login') }}" 
                       class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-lg font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-lg">
                        🔐 Se connecter
                    </a>
                @endauth
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-20">
                <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                    <div class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">99.9%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Uptime Garanti</div>
                </div>
                <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                    <div class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">24/7</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Support</div>
                </div>
                <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                    <div class="text-4xl font-bold bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent mb-2">10k+</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Clients</div>
                </div>
                <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                    <div class="text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent mb-2">5min</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Activation</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Nos Services
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300">
                Des solutions adaptées à tous vos besoins
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Service 1 -->
            <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-2">
                <div class="text-5xl mb-4">🌐</div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Hébergement Web</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Hébergement performant pour vos sites web et applications
                </p>
                <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                    <li>✅ SSD NVMe ultra-rapide</li>
                    <li>✅ SSL gratuit</li>
                    <li>✅ Sauvegardes automatiques</li>
                </ul>
            </div>

            <!-- Service 2 -->
            <div class="p-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-2 text-white">
                <div class="text-5xl mb-4">🖥️</div>
                <h3 class="text-2xl font-bold mb-4">Serveur VPS</h3>
                <p class="text-blue-100 mb-6">
                    Serveurs virtuels dédiés avec ressources garanties
                </p>
                <ul class="space-y-2 text-blue-100">
                    <li>✅ Ressources dédiées</li>
                    <li>✅ Accès root complet</li>
                    <li>✅ Snapshots instantanés</li>
                </ul>
            </div>

            <!-- Service 3 -->
            <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-2">
                <div class="text-5xl mb-4">⚡</div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Serveur Dédié</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Puissance maximale pour vos projets critiques
                </p>
                <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                    <li>✅ Hardware haute performance</li>
                    <li>✅ Bande passante illimitée</li>
                    <li>✅ Support prioritaire</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-3xl p-12 shadow-2xl">
            <h2 class="text-4xl font-bold text-white mb-6">
                Prêt à démarrer ?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Rejoignez des milliers de clients satisfaits
            </p>
            @guest
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-purple-600 text-lg font-semibold rounded-xl hover:bg-gray-100 transform hover:scale-105 transition shadow-xl">
                    Créer un compte gratuit
                </a>
            @endguest
        </div>
    </div>
</div>
@endsection
