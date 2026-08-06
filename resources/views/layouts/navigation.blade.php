<nav class="bg-white dark:bg-gray-800 shadow" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img class="h-8 w-auto" src="{{ config('hostclient.company_logo', '/images/logo.png') }}" alt="{{ config('hostclient.company_name') }}">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">{{ config('hostclient.company_name') }}</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                @auth
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 inline mr-1"></i>
                            Admin
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('client'))
                        <a href="{{ route('client.dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="home" class="w-4 h-4 inline mr-1"></i>
                            Tableau de bord
                        </a>
                        <a href="{{ route('client.services.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="server" class="w-4 h-4 inline mr-1"></i>
                            Services
                        </a>
                        <a href="{{ route('client.invoices.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="file-text" class="w-4 h-4 inline mr-1"></i>
                            Factures
                        </a>
                        <a href="{{ route('client.tickets.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="message-circle" class="w-4 h-4 inline mr-1"></i>
                            Support
                        </a>
                    @endif

                    <a href="{{ route('store.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i data-lucide="shopping-cart" class="w-4 h-4 inline mr-1"></i>
                        Boutique
                    </a>
                @else
                    <a href="{{ route('store.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        Produits
                    </a>
                @endauth
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="$dispatch('toggle-dark-mode')" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i data-lucide="sun" class="w-5 h-5 text-gray-500 dark:text-gray-400 dark:hidden"></i>
                    <i data-lucide="moon" class="w-5 h-5 text-gray-500 dark:text-gray-400 hidden dark:block"></i>
                </button>

                @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="dropdown()">
                        <button @click="toggle()" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                            <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium text-sm">{{ substr(auth()->user()->first_name, 0, 1) }}</span>
                            </div>
                            <span class="hidden md:block">{{ auth()->user()->first_name }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>

                        <div x-show="open" @click.away="close()" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-50">
                            <a href="{{ route('client.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                                Profil
                            </a>
                            <a href="{{ route('client.api-keys.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="key" class="w-4 h-4 inline mr-2"></i>
                                Clés API
                            </a>
                            <div class="border-t border-gray-200 dark:border-gray-700"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary">
                        S'inscrire
                    </a>
                @endauth

                <!-- Mobile menu button -->
                <button @click="open = !open" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i data-lucide="menu" class="w-6 h-6 text-gray-500 dark:text-gray-400"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="open" @click.away="open = false" x-transition class="md:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @auth
                @if(auth()->user()->hasRole('client'))
                    <a href="{{ route('client.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Tableau de bord
                    </a>
                    <a href="{{ route('client.services.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Services
                    </a>
                    <a href="{{ route('client.invoices.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Factures
                    </a>
                    <a href="{{ route('client.tickets.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Support
                    </a>
                @endif
                <a href="{{ route('store.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Boutique
                </a>
            @else
                <a href="{{ route('store.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Produits
                </a>
                <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Connexion
                </a>
                <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    S'inscrire
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- Flash Messages -->
@if(session('success') || session('error') || session('warning'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" data-auto-dismiss="5000">
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" data-auto-dismiss="8000">
                <div class="flex items-center">
                    <i data-lucide="x-circle" class="w-5 h-5 mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded" data-auto-dismiss="6000">
                <div class="flex items-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5 mr-2"></i>
                    {{ session('warning') }}
                </div>
            </div>
        @endif
    </div>
@endif