<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HostClient') }} - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('client.dashboard') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                            H
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ config('hostclient.company_name', config('app.name')) }}
                        </span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('client.dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('client.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        🏠 Dashboard
                    </a>
                    <a href="{{ route('client.services.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('client.services.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        🖥️ Services
                    </a>
                    <a href="{{ route('client.invoices.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('client.invoices.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        📄 Factures
                    </a>
                    <a href="{{ route('client.tickets.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('client.tickets.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        💬 Support
                    </a>
                    
                    <div class="flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            💰 {{ number_format(auth()->user()->balance, 2) }} €
                        </span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg">
                            🚪 Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                ❌ {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            © {{ date('Y') }} {{ config('hostclient.company_name', config('app.name')) }}
        </div>
    </footer>

</body>
</html>
