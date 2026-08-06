<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'HostClient')); ?> - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="<?php echo e(route('client.dashboard')); ?>" class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i data-lucide="server" class="w-5 h-5 text-white"></i>
                            </div>
                            <span class="text-xl font-bold text-gray-900 dark:text-white hidden sm:block">
                                <?php echo e(config('hostclient.company_name', config('app.name'))); ?>

                            </span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex md:items-center md:space-x-1">
                        <a href="<?php echo e(route('client.dashboard')); ?>" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('client.dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                            Tableau de bord
                        </a>
                        <a href="<?php echo e(route('client.services.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('client.services.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i data-lucide="server" class="w-4 h-4 mr-2"></i>
                            Services
                        </a>
                        <a href="<?php echo e(route('client.invoices.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('client.invoices.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                            Factures
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($unpaidInvoicesCount) && $unpaidInvoicesCount > 0): ?>
                                <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?php echo e($unpaidInvoicesCount); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </a>
                        <a href="<?php echo e(route('client.tickets.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('client.tickets.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i data-lucide="message-circle" class="w-4 h-4 mr-2"></i>
                            Support
                        </a>
                        <a href="<?php echo e(route('store.index')); ?>" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i data-lucide="shopping-cart" class="w-4 h-4 mr-2"></i>
                            Boutique
                        </a>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-2">
                        <!-- Balance -->
                        <div class="hidden sm:flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <i data-lucide="wallet" class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e(number_format(auth()->user()->balance, 2)); ?> <?php echo e(auth()->user()->currency); ?>

                            </span>
                        </div>

                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i data-lucide="sun" class="w-5 h-5 text-gray-500 dark:text-gray-400" x-show="!darkMode"></i>
                            <i data-lucide="moon" class="w-5 h-5 text-gray-500 dark:text-gray-400" x-show="darkMode" style="display: none;"></i>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium text-sm"><?php echo e(substr(auth()->user()->first_name, 0, 1)); ?></span>
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <?php echo e(auth()->user()->first_name); ?>

                                </span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500 dark:text-gray-400 hidden md:block"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 border border-gray-200 dark:border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        <?php echo e(auth()->user()->first_name); ?> <?php echo e(auth()->user()->last_name); ?>

                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        <?php echo e(auth()->user()->email); ?>

                                    </p>
                                </div>

                                <a href="<?php echo e(route('client.profile.edit')); ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i data-lucide="user" class="w-4 h-4 mr-3"></i>
                                    Mon profil
                                </a>
                                <a href="<?php echo e(route('client.api-keys.index')); ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i data-lucide="key" class="w-4 h-4 mr-3"></i>
                                    Clés API
                                </a>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasRole('admin')): ?>
                                    <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i data-lucide="shield" class="w-4 h-4 mr-3"></i>
                                        Administration
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i data-lucide="log-out" class="w-4 h-4 mr-3"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Mobile menu button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i data-lucide="menu" class="w-6 h-6 text-gray-500 dark:text-gray-400"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition class="md:hidden border-t border-gray-200 dark:border-gray-700">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="<?php echo e(route('client.dashboard')); ?>" class="flex items-center px-3 py-2 text-base font-medium rounded-lg <?php echo e(request()->routeIs('client.dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                        <i data-lucide="home" class="w-5 h-5 mr-3"></i>
                        Tableau de bord
                    </a>
                    <a href="<?php echo e(route('client.services.index')); ?>" class="flex items-center px-3 py-2 text-base font-medium rounded-lg <?php echo e(request()->routeIs('client.services.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                        <i data-lucide="server" class="w-5 h-5 mr-3"></i>
                        Services
                    </a>
                    <a href="<?php echo e(route('client.invoices.index')); ?>" class="flex items-center px-3 py-2 text-base font-medium rounded-lg <?php echo e(request()->routeIs('client.invoices.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                        <i data-lucide="file-text" class="w-5 h-5 mr-3"></i>
                        Factures
                    </a>
                    <a href="<?php echo e(route('client.tickets.index')); ?>" class="flex items-center px-3 py-2 text-base font-medium rounded-lg <?php echo e(request()->routeIs('client.tickets.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                        <i data-lucide="message-circle" class="w-5 h-5 mr-3"></i>
                        Support
                    </a>
                    <a href="<?php echo e(route('store.index')); ?>" class="flex items-center px-3 py-2 text-base font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i data-lucide="shopping-cart" class="w-5 h-5 mr-3"></i>
                        Boutique
                    </a>

                    <!-- Balance (Mobile) -->
                    <div class="sm:hidden flex items-center px-3 py-2 mt-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <i data-lucide="wallet" class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            Solde: <?php echo e(number_format(auth()->user()->balance, 2)); ?> <?php echo e(auth()->user()->currency); ?>

                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success') || session('error') || session('warning') || session('info')): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-start" x-data="{ show: true }" x-show="show">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div class="flex-1"><?php echo e(session('success')); ?></div>
                        <button @click="show = false" class="ml-4">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-start" x-data="{ show: true }" x-show="show">
                        <i data-lucide="x-circle" class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div class="flex-1"><?php echo e(session('error')); ?></div>
                        <button @click="show = false" class="ml-4">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('warning')): ?>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200 px-4 py-3 rounded-lg flex items-start" x-data="{ show: true }" x-show="show">
                        <i data-lucide="alert-triangle" class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div class="flex-1"><?php echo e(session('warning')); ?></div>
                        <button @click="show = false" class="ml-4">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 px-4 py-3 rounded-lg flex items-start" x-data="{ show: true }" x-show="show">
                        <i data-lucide="info" class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div class="flex-1"><?php echo e(session('info')); ?></div>
                        <button @click="show = false" class="ml-4">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        © <?php echo e(date('Y')); ?> <?php echo e(config('hostclient.company_name', config('app.name'))); ?>. Tous droits réservés.
                    </p>
                    <div class="flex space-x-6 text-sm text-gray-500 dark:text-gray-400">
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300">Conditions d'utilisation</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300">Confidentialité</a>
                        <a href="<?php echo e(route('client.tickets.create')); ?>" class="hover:text-gray-700 dark:hover:text-gray-300">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
<?php /**PATH /root/hostclient/resources/views/layouts/client.blade.php ENDPATH**/ ?>