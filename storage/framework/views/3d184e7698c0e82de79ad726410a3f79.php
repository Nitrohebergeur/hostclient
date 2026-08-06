<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'HostClient')); ?> - <?php echo $__env->yieldContent('title', 'Admin'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <aside 
            x-show="sidebarOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transition-transform duration-200 lg:translate-x-0"
        >
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="server" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Admin</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i data-lucide="x" class="w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Tableau de bord
                </a>

                <!-- Clients -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.clients.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                            Clients
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                        <a href="<?php echo e(route('admin.clients.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Tous les clients
                        </a>
                        <a href="<?php echo e(route('admin.clients.create')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Ajouter un client
                        </a>
                    </div>
                </div>

                <!-- Products & Services -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.services.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                            Produits & Services
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                        <a href="<?php echo e(route('admin.products.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Produits
                        </a>
                        <a href="<?php echo e(route('admin.categories.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Catégories
                        </a>
                        <a href="<?php echo e(route('admin.services.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Services actifs
                        </a>
                    </div>
                </div>

                <!-- Orders -->
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('admin.orders.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                    <i data-lucide="shopping-bag" class="w-5 h-5 mr-3"></i>
                    Commandes
                </a>

                <!-- Invoices -->
                <a href="<?php echo e(route('admin.invoices.index')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('admin.invoices.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                    <i data-lucide="file-text" class="w-5 h-5 mr-3"></i>
                    Factures
                </a>

                <!-- Transactions -->
                <a href="<?php echo e(route('admin.transactions.index')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('admin.transactions.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                    <i data-lucide="credit-card" class="w-5 h-5 mr-3"></i>
                    Transactions
                </a>

                <!-- Tickets -->
                <a href="<?php echo e(route('admin.tickets.index')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('admin.tickets.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                    <i data-lucide="message-circle" class="w-5 h-5 mr-3"></i>
                    Support
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($openTicketsCount) && $openTicketsCount > 0): ?>
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full"><?php echo e($openTicketsCount); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>

                <!-- Divider -->
                <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>

                <!-- Billing -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.payment-gateways.*') || request()->routeIs('admin.coupons.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <i data-lucide="dollar-sign" class="w-5 h-5 mr-3"></i>
                            Facturation
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                        <a href="<?php echo e(route('admin.payment-gateways.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Passerelles de paiement
                        </a>
                        <a href="<?php echo e(route('admin.coupons.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Coupons
                        </a>
                    </div>
                </div>

                <!-- Modules -->
                <a href="<?php echo e(route('admin.modules.index')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->routeIs('admin.modules.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                    <i data-lucide="puzzle" class="w-5 h-5 mr-3"></i>
                    Modules
                </a>

                <!-- System -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.settings.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.activity.*') ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                            Système
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                        <a href="<?php echo e(route('admin.settings.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Paramètres
                        </a>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Utilisateurs
                        </a>
                        <a href="<?php echo e(route('admin.roles.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Rôles & Permissions
                        </a>
                        <a href="<?php echo e(route('admin.activity.index')); ?>" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Journal d'activité
                        </a>
                    </div>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-medium"><?php echo e(substr(auth()->user()->first_name, 0, 1)); ?></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            <?php echo e(auth()->user()->first_name); ?> <?php echo e(auth()->user()->last_name); ?>

                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            <?php echo e(auth()->user()->email); ?>

                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Navigation -->
            <header class="sticky top-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 lg:hidden">
                            <i data-lucide="menu" class="w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                            <?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
                        </h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i data-lucide="sun" class="w-5 h-5 text-gray-500 dark:text-gray-400" x-show="!darkMode"></i>
                            <i data-lucide="moon" class="w-5 h-5 text-gray-500 dark:text-gray-400" x-show="darkMode" style="display: none;"></i>
                        </button>

                        <!-- Client View -->
                        <a href="<?php echo e(route('client.dashboard')); ?>" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title="Vue client">
                            <i data-lucide="eye" class="w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                        </a>

                        <!-- Logout -->
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title="Déconnexion">
                                <i data-lucide="log-out" class="w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success') || session('error') || session('warning') || session('info')): ?>
                <div class="px-6 py-4">
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
            <main class="p-6">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /root/hostclient/resources/views/layouts/admin.blade.php ENDPATH**/ ?>