<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('hostclient.company_name', 'HostClient') }} - Hébergement Web Premium</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-gray-900/95 backdrop-blur-sm border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <span class="text-2xl font-bold">N</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold">{{ config('hostclient.company_name', 'HostClient') }}</div>
                        <div class="text-xs text-gray-400">Hébergement Premium</div>
                    </div>
                </div>
                
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#hebergement" class="text-gray-300 hover:text-white transition">Hébergement</a>
                    <a href="#vps" class="text-gray-300 hover:text-white transition">VPS</a>
                    <a href="#dedicace" class="text-gray-300 hover:text-white transition">Dédié</a>
                    <a href="#support" class="text-gray-300 hover:text-white transition">Support</a>
                    
                    @auth
                        <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('client.dashboard') }}" 
                           class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition">
                            Mon Espace
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition">Connexion</a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition">
                            S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 px-4 overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-purple-900/20 to-pink-900/20 animate-gradient"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0); background-size: 40px 40px;"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 text-sm font-medium mb-8 animate-float">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 animate-pulse"></span>
                    Hébergement Nouvelle Génération
                </div>
                
                <!-- Main Title -->
                <h1 class="text-6xl md:text-8xl font-black mb-6">
                    <span class="bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                        Hébergement Web
                    </span>
                    <br>
                    <span class="text-white">Ultra-Rapide</span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-400 mb-12 max-w-3xl mx-auto leading-relaxed">
                    Hébergez vos sites et applications avec des performances exceptionnelles. 
                    <span class="text-white font-semibold">99.9% d'uptime garanti</span>, support 24/7 et déploiement instantané.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                    @guest
                        <a href="{{ route('register') }}" 
                           class="group px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl font-bold text-lg hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-105 shadow-2xl shadow-blue-500/50">
                            Commencer Gratuitement
                            <span class="inline-block ml-2 group-hover:translate-x-1 transition">→</span>
                        </a>
                        <a href="#pricing" 
                           class="px-8 py-4 bg-white/5 backdrop-blur border border-white/10 rounded-xl font-bold text-lg hover:bg-white/10 transition">
                            Voir les Tarifs
                        </a>
                    @else
                        <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('client.dashboard') }}" 
                           class="group px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl font-bold text-lg hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-105 shadow-2xl shadow-blue-500/50">
                            Accéder à mon Espace
                            <span class="inline-block ml-2 group-hover:translate-x-1 transition">→</span>
                        </a>
                    @endguest
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-5xl mx-auto">
                    <div class="p-6 bg-white/5 backdrop-blur border border-white/10 rounded-2xl">
                        <div class="text-4xl font-black bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent mb-2">99.9%</div>
                        <div class="text-gray-400 text-sm">Uptime SLA</div>
                    </div>
                    <div class="p-6 bg-white/5 backdrop-blur border border-white/10 rounded-2xl">
                        <div class="text-4xl font-black bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent mb-2">24/7</div>
                        <div class="text-gray-400 text-sm">Support Expert</div>
                    </div>
                    <div class="p-6 bg-white/5 backdrop-blur border border-white/10 rounded-2xl">
                        <div class="text-4xl font-black bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent mb-2">15K+</div>
                        <div class="text-gray-400 text-sm">Clients Actifs</div>
                    </div>
                    <div class="p-6 bg-white/5 backdrop-blur border border-white/10 rounded-2xl">
                        <div class="text-4xl font-black bg-gradient-to-r from-orange-400 to-red-400 bg-clip-text text-transparent mb-2">2min</div>
                        <div class="text-gray-400 text-sm">Déploiement</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="hebergement" class="py-20 px-4 bg-gray-800/50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-black mb-4">
                    Nos <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Solutions</span>
                </h2>
                <p class="text-xl text-gray-400">Choisissez la solution adaptée à vos besoins</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Hébergement Web -->
                <div class="group relative p-8 bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl border border-gray-700 hover:border-blue-500/50 transition overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 text-3xl">
                            🌐
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Hébergement Web</h3>
                        <p class="text-gray-400 mb-6">Parfait pour sites WordPress, e-commerce et applications web</p>
                        <div class="space-y-3 mb-8">
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                SSD NVMe Ultra-Rapide
                            </div>
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                SSL Gratuit inclus
                            </div>
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                Sauvegardes Automatiques
                            </div>
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                Protection Anti-DDoS
                            </div>
                        </div>
                        <div class="text-3xl font-black mb-2">À partir de <span class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">4.99€</span></div>
                        <div class="text-sm text-gray-400 mb-6">par mois</div>
                        <a href="{{ route('store.index') }}" class="block w-full py-3 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl font-semibold text-center hover:from-blue-700 hover:to-cyan-700 transition">
                            Commander
                        </a>
                    </div>
                </div>

                <!-- VPS -->
                <div class="group relative p-8 bg-gradient-to-br from-purple-900/50 to-gray-900 rounded-3xl border-2 border-purple-500 overflow-hidden transform scale-105">
                    <div class="absolute -top-4 -right-4 px-4 py-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full text-sm font-bold transform rotate-12">
                        POPULAIRE
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-transparent"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 text-3xl">
                            🚀
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Serveur VPS</h3>
                        <p class="text-gray-400 mb-6">Puissance et flexibilité avec ressources dédiées</p>
                        <div class="space-y-3 mb-8">
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                Ressources 100% Dédiées
                            </div>
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                Accès Root Complet
                            </div>
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                Snapshots Illimités
                            </div>
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                IP Dédiée Incluse
                            </div>
                        </div>
                        <div class="text-3xl font-black mb-2">À partir de <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">9.99€</span></div>
                        <div class="text-sm text-gray-400 mb-6">par mois</div>
                        <a href="{{ route('store.index') }}" class="block w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl font-semibold text-center hover:from-purple-700 hover:to-pink-700 transition shadow-lg shadow-purple-500/50">
                            Commander
                        </a>
                    </div>
                </div>

                <!-- Dédié -->
                <div class="group relative p-8 bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl border border-gray-700 hover:border-orange-500/50 transition overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-600/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mb-6 text-3xl">
                            ⚡
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Serveur Dédié</h3>
                        <p class="text-gray-400 mb-6">Performance maximale pour projets exigeants</p>
                        <div class="space-y-3 mb-8">
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                Serveur 100% Dédié
                            </div>
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                Hardware Haute-Performance
                            </div>
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                Bande Passante Illimitée
                            </div>
                            <div class="flex items-center text-gray-300">
                                <span class="w-5 h-5 bg-green-500/20 rounded-full flex items-center justify-center mr-3 text-green-400 text-xs">✓</span>
                                Support Prioritaire 24/7
                            </div>
                        </div>
                        <div class="text-3xl font-black mb-2">À partir de <span class="bg-gradient-to-r from-orange-400 to-red-400 bg-clip-text text-transparent">49.99€</span></div>
                        <div class="text-sm text-gray-400 mb-6">par mois</div>
                        <a href="{{ route('store.index') }}" class="block w-full py-3 bg-gradient-to-r from-orange-600 to-red-600 rounded-xl font-semibold text-center hover:from-orange-700 hover:to-red-700 transition">
                            Commander
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-20 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="p-12 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-3xl relative overflow-hidden">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative z-10">
                    <h2 class="text-4xl md:text-5xl font-black mb-6">Prêt à démarrer ?</h2>
                    <p class="text-xl mb-8 text-blue-100">Créez votre compte et lancez votre projet en quelques minutes</p>
                    @guest
                        <a href="{{ route('register') }}" class="inline-block px-10 py-4 bg-white text-purple-600 rounded-xl font-bold text-lg hover:bg-gray-100 transition transform hover:scale-105 shadow-2xl">
                            Créer mon compte →
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-950 border-t border-gray-800 py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="text-xl font-bold mb-4">{{ config('hostclient.company_name', 'HostClient') }}</div>
                    <p class="text-gray-400 text-sm">Hébergement web professionnel avec support 24/7</p>
                </div>
                <div>
                    <div class="font-semibold mb-4">Services</div>
                    <div class="space-y-2 text-gray-400 text-sm">
                        <div><a href="#" class="hover:text-white transition">Hébergement Web</a></div>
                        <div><a href="#" class="hover:text-white transition">Serveurs VPS</a></div>
                        <div><a href="#" class="hover:text-white transition">Serveurs Dédiés</a></div>
                    </div>
                </div>
                <div>
                    <div class="font-semibold mb-4">Support</div>
                    <div class="space-y-2 text-gray-400 text-sm">
                        <div><a href="#" class="hover:text-white transition">Documentation</a></div>
                        <div><a href="#" class="hover:text-white transition">Ouvrir un ticket</a></div>
                        <div><a href="#" class="hover:text-white transition">FAQ</a></div>
                    </div>
                </div>
                <div>
                    <div class="font-semibold mb-4">Entreprise</div>
                    <div class="space-y-2 text-gray-400 text-sm">
                        <div><a href="#" class="hover:text-white transition">À propos</a></div>
                        <div><a href="#" class="hover:text-white transition">Contact</a></div>
                        <div><a href="#" class="hover:text-white transition">CGV</a></div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400 text-sm">
                © {{ date('Y') }} {{ config('hostclient.company_name', 'HostClient') }}. Tous droits réservés.
            </div>
        </div>
    </footer>

</body>
</html>
