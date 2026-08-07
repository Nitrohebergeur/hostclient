<!DOCTYPE html>
<html lang="fr" x-data="darkMode">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>HostClient - Plateforme de Gestion d'Hébergement Web</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="antialiased" :class="{'dark': dark}">
    
    <!-- Header / Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 glass border-b border-gray-200 dark:border-gray-700">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">HostClient</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-400 transition-colors">Fonctionnalités</a>
                    <a href="#pricing" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-400 transition-colors">Tarifs</a>
                    <a href="#about" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-400 transition-colors">À propos</a>
                    <a href="#contact" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-400 transition-colors">Contact</a>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggle" class="p-2 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                    
                    <a href="/login" class="btn btn-ghost hidden md:inline-flex">Connexion</a>
                    <a href="/register" class="btn btn-primary">Commencer</a>
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 bg-gradient-to-br from-primary-50 via-white to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="container mx-auto">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                    </svg>
                    Plateforme Open Source
                </div>
                
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    La Plateforme Moderne<br>
                    <span class="text-gradient from-primary-600 to-secondary-600">de Gestion d'Hébergement</span>
                </h1>
                
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto">
                    Solution complète pour gérer vos services d'hébergement web, VPS, serveurs dédiés et noms de domaine avec une interface intuitive et moderne.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="/register" class="btn btn-primary text-lg px-8 py-3">
                        Commencer Gratuitement
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#demo" class="btn btn-outline text-lg px-8 py-3">
                        Voir la Démo
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </a>
                </div>
                
                <div class="mt-12 flex items-center justify-center gap-8 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Gratuit & Open Source
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Installation Rapide
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        100% Personnalisable
                    </div>
                </div>
            </div>
            
            <!-- Screenshot -->
            <div class="mt-16 max-w-6xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-500 to-secondary-500 blur-3xl opacity-20"></div>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-200 dark:border-gray-700">
                        <img src="/images/dashboard-preview.png" alt="Dashboard Preview" class="w-full" onerror="this.style.display='none'">
                        <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">Aperçu du Dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="py-20 px-4 bg-white dark:bg-gray-800">
        <div class="container mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    Fonctionnalités Complètes
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Tout ce dont vous avez besoin pour gérer votre activité d'hébergement
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body">
                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Facturation Automatisée</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Génération automatique des factures, rappels de paiement et renouvellements. Support de multiples devises et taxes.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body">
                        <div class="w-12 h-12 bg-secondary-100 dark:bg-secondary-900/30 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sécurité Avancée</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Authentification 2FA, chiffrement des données, audit logs complet et permissions granulaires pour une sécurité maximale.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body">
                        <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Support Client</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Système de tickets complet avec catégories, SLA, réponses rapides et notes internes pour un support efficace.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 4 -->
                <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body">
                        <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Provisionnement Auto</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Connexion à Pterodactyl, Proxmox, cPanel, Plesk et plus encore pour un provisionnement automatique des services.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 5 -->
                <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-body">
                        <div class="w-12 h-12 bg-danger-100 dark:bg-danger-900/30 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Système de Plugins</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Extensible à l'infini avec un système de plugins puissant. Ajoutez vos propres fonctionnalités facilement.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 6 -->
                <div class="card hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-body">
                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Thèmes Personnalisables</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Personnalisez l'apparence complète avec le système de thèmes. Mode clair et sombre inclus par défaut.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="py-20 px-4 bg-gradient-to-r from-primary-600 to-secondary-600">
        <div class="container mx-auto">
            <div class="grid md:grid-cols-4 gap-8 text-center text-white">
                <div data-aos="fade-up" data-aos-delay="100">
                    <div class="text-5xl font-bold mb-2">99.9%</div>
                    <div class="text-primary-100">Uptime Garanti</div>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <div class="text-5xl font-bold mb-2">24/7</div>
                    <div class="text-primary-100">Support Disponible</div>
                </div>
                <div data-aos="fade-up" data-aos-delay="300">
                    <div class="text-5xl font-bold mb-2">1000+</div>
                    <div class="text-primary-100">Clients Satisfaits</div>
                </div>
                <div data-aos="fade-up" data-aos-delay="400">
                    <div class="text-5xl font-bold mb-2">50+</div>
                    <div class="text-primary-100">Pays Couverts</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 px-4 bg-white dark:bg-gray-800">
        <div class="container mx-auto">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                    Prêt à Commencer ?
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-10">
                    Rejoignez des milliers d'entreprises qui font confiance à HostClient pour gérer leur activité d'hébergement.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register" class="btn btn-primary text-lg px-8 py-3">
                        Créer un Compte Gratuit
                    </a>
                    <a href="#contact" class="btn btn-outline text-lg px-8 py-3">
                        Nous Contacter
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 px-4">
        <div class="container mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">HostClient</span>
                    </div>
                    <p class="text-gray-400">
                        Plateforme moderne de gestion d'hébergement web open source.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-bold text-white mb-4">Produit</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="hover:text-primary-400 transition-colors">Fonctionnalités</a></li>
                        <li><a href="#pricing" class="hover:text-primary-400 transition-colors">Tarifs</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">API</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-white mb-4">Entreprise</h3>
                    <ul class="space-y-2">
                        <li><a href="#about" class="hover:text-primary-400 transition-colors">À propos</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Blog</a></li>
                        <li><a href="#contact" class="hover:text-primary-400 transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Carrières</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-white mb-4">Légal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Confidentialité</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Conditions</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Licence</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Sécurité</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo e(date('Y')); ?> HostClient. Tous droits réservés. Distribué sous licence MIT.</p>
            </div>
        </div>
    </footer>
    
</body>
</html>
<?php /**PATH /var/www/hostclient/resources/views/welcome.blade.php ENDPATH**/ ?>