<!DOCTYPE html>
<html lang="fr" x-data="darkMode">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — HostClient Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="antialiased" :class="{'dark': dark}">
<div x-data="sidebar" class="min-h-screen bg-gray-50 dark:bg-gray-900 flex">

    <!-- ── Sidebar ─────────────────────────────────────────────────── -->
    <aside
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-0 left-0 z-40 w-64 h-screen bg-gray-900 dark:bg-gray-950 border-r border-gray-800 flex flex-col"
    >
        <!-- Logo -->
        <div class="flex items-center gap-2 px-5 py-4 border-b border-gray-800">
            <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
            </div>
            <div>
                <span class="text-white font-bold text-base">HostClient</span>
                <span class="block text-xs text-primary-400 font-medium">Admin Panel</span>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 overflow-y-auto scrollbar-thin py-4 px-3 space-y-0.5">

            @php
            $nav = [
                ['label' => 'Vue d\'ensemble',  'icon' => 'home',        'route' => 'admin/dashboard'],
                ['divider' => 'Clients'],
                ['label' => 'Utilisateurs',     'icon' => 'users',       'route' => 'admin/users'],
                ['label' => 'Rôles',            'icon' => 'shield',      'route' => 'admin/roles'],
                ['divider' => 'Catalogue'],
                ['label' => 'Produits',         'icon' => 'cube',        'route' => 'admin/products'],
                ['label' => 'Catégories',       'icon' => 'tag',         'route' => 'admin/products/categories'],
                ['label' => 'Coupons',          'icon' => 'ticket',      'route' => 'admin/coupons'],
                ['divider' => 'Infrastructure'],
                ['label' => 'Serveurs',         'icon' => 'server',      'route' => 'admin/servers'],
                ['label' => 'Services',         'icon' => 'lightning',   'route' => 'admin/services'],
                ['label' => 'Domaines',         'icon' => 'globe',       'route' => 'admin/domains'],
                ['divider' => 'Facturation'],
                ['label' => 'Factures',         'icon' => 'document',    'route' => 'admin/invoices'],
                ['label' => 'Paiements',        'icon' => 'credit-card', 'route' => 'admin/payments'],
                ['label' => 'Passerelles',      'icon' => 'switch',      'route' => 'admin/payment-gateways'],
                ['label' => 'Devises',          'icon' => 'currency',    'route' => 'admin/currencies'],
                ['label' => 'Taxes',            'icon' => 'receipt-tax', 'route' => 'admin/taxes'],
                ['divider' => 'Support'],
                ['label' => 'Tickets',          'icon' => 'support',     'route' => 'admin/tickets', 'badge' => 5],
                ['label' => 'Annonces',         'icon' => 'speakerphone','route' => 'admin/announcements'],
                ['divider' => 'Extensions'],
                ['label' => 'Extensions',       'icon' => 'puzzle',      'route' => 'admin/extensions'],
                ['label' => 'Extensions Jeux',  'icon' => 'gamepad',     'route' => 'admin/game-extensions'],
                ['label' => 'Thèmes',           'icon' => 'color-swatch','route' => 'admin/themes'],
                ['divider' => 'Système'],
                ['label' => 'Mises à jour',     'icon' => 'refresh',     'route' => 'admin/auto-updates'],
                ['label' => 'API',              'icon' => 'code',        'route' => 'admin/api'],
                ['label' => 'Journaux',         'icon' => 'clipboard',   'route' => 'admin/logs'],
                ['label' => 'Sauvegardes',      'icon' => 'archive',     'route' => 'admin/backups'],
                ['label' => 'Paramètres',       'icon' => 'cog',         'route' => 'admin/settings'],
            ];
            @endphp

            @foreach($nav as $item)
                @if(isset($item['divider']))
                    <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $item['divider'] }}</p>
                @else
                    @php $active = request()->is($item['route'].'*'); @endphp
                    <a href="/{{ $item['route'] }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                              {{ $active
                                 ? 'bg-primary-600 text-white shadow'
                                 : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                        @include('layouts.partials.admin-icon', ['icon' => $item['icon']])
                        <span>{{ $item['label'] }}</span>
                        @if(isset($item['badge']))
                            <span class="ml-auto bg-danger-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endif
            @endforeach
        </nav>

        <!-- Admin user -->
        <div class="border-t border-gray-800 p-3">
            <div x-data="dropdown" class="relative">
                <button @click="toggle" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=d946ef&color=fff&size=36"
                         class="w-9 h-9 rounded-full flex-shrink-0" alt="Admin">
                    <div class="text-left overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Administrateur' }}</p>
                        <p class="text-xs text-gray-400 truncate">Super Admin</p>
                    </div>
                </button>
                <div x-show="open" @click.away="close" x-transition
                     class="absolute bottom-full left-0 right-0 mb-2 bg-gray-800 rounded-lg border border-gray-700 py-1 shadow-xl">
                    <a href="/client/dashboard" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Espace Client
                    </a>
                    <div class="border-t border-gray-700 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-danger-400 hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- ── Main ────────────────────────────────────────────────────── -->
    <div class="flex-1 transition-all duration-200" :class="open ? 'ml-64' : 'ml-0'">

        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30">
            <div class="px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
                <!-- Left -->
                <div class="flex items-center gap-3">
                    <button @click="toggle" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-lg font-bold text-gray-900 dark:text-white">@yield('title', 'Dashboard')</h1>
                </div>

                <!-- Right -->
                <div class="flex items-center gap-2">
                    <!-- Quick search -->
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                        <!-- Search modal -->
                        <div x-show="open" @keydown.escape.window="open = false" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 bg-black/50 flex items-start justify-center pt-24 px-4">
                            <div @click.away="open = false" class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden">
                                <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" placeholder="Rechercher un utilisateur, facture, service..." class="flex-1 bg-transparent text-gray-900 dark:text-white placeholder-gray-400 outline-none text-sm" autofocus>
                                    <kbd class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">ESC</kbd>
                                </div>
                                <div class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">Saisissez pour rechercher…</div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div x-data="dropdown" class="relative">
                        <button @click="toggle" class="relative p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-danger-500 rounded-full ring-2 ring-white dark:ring-gray-800"></span>
                        </button>
                        <div x-show="open" @click.away="close" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <span class="font-semibold text-gray-900 dark:text-white">Notifications</span>
                                <a href="#" class="text-xs text-primary-600 dark:text-primary-400">Tout marquer comme lu</a>
                            </div>
                            @foreach([
                                ['Nouveau ticket ouvert', '#TKT-1234 — Problème FTP', 'danger', '2 min'],
                                ['Paiement reçu', '29,99 € via Stripe', 'success', '15 min'],
                                ['Nouveau client inscrit', 'marie.martin@example.com', 'primary', '1h'],
                            ] as $notif)
                            <a href="#" class="flex gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <span class="flex-shrink-0 mt-1 w-2 h-2 rounded-full bg-{{ $notif[2] }}-500"></span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $notif[0] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $notif[1] }}</p>
                                </div>
                                <span class="text-xs text-gray-400 flex-shrink-0">{{ $notif[2] === 'danger' ? $notif[3] : $notif[3] }}</span>
                            </a>
                            @endforeach
                            <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-center">
                                <a href="#" class="text-sm text-primary-600 dark:text-primary-400">Voir toutes</a>
                            </div>
                        </div>
                    </div>

                    <!-- Dark mode -->
                    <button @click="toggle" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark"  class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

    </div>
</div>
@stack('scripts')
</body>
</html>
