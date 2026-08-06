<nav class="bg-white dark:bg-gray-800 shadow" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-2">
                    <img class="h-8 w-auto" src="<?php echo e(config('hostclient.company_logo', '/images/logo.png')); ?>" alt="<?php echo e(config('hostclient.company_name')); ?>">
                    <span class="text-xl font-bold text-gray-900 dark:text-white"><?php echo e(config('hostclient.company_name')); ?></span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasRole('admin')): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 inline mr-1"></i>
                            Admin
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasRole('client')): ?>
                        <a href="<?php echo e(route('client.dashboard')); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="home" class="w-4 h-4 inline mr-1"></i>
                            Tableau de bord
                        </a>
                        <a href="<?php echo e(route('client.services.index')); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="server" class="w-4 h-4 inline mr-1"></i>
                            Services
                        </a>
                        <a href="<?php echo e(route('client.invoices.index')); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="file-text" class="w-4 h-4 inline mr-1"></i>
                            Factures
                        </a>
                        <a href="<?php echo e(route('client.tickets.index')); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i data-lucide="message-circle" class="w-4 h-4 inline mr-1"></i>
                            Support
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <a href="<?php echo e(route('store.index')); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i data-lucide="shopping-cart" class="w-4 h-4 inline mr-1"></i>
                        Boutique
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('store.index')); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        Produits
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="$dispatch('toggle-dark-mode')" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i data-lucide="sun" class="w-5 h-5 text-gray-500 dark:text-gray-400 dark:hidden"></i>
                    <i data-lucide="moon" class="w-5 h-5 text-gray-500 dark:text-gray-400 hidden dark:block"></i>
                </button>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <!-- User Dropdown -->
                    <div class="relative" x-data="dropdown()">
                        <button @click="toggle()" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                            <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium text-sm"><?php echo e(substr(auth()->user()->first_name, 0, 1)); ?></span>
                            </div>
                            <span class="hidden md:block"><?php echo e(auth()->user()->first_name); ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>

                        <div x-show="open" @click.away="close()" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-50">
                            <a href="<?php echo e(route('client.profile.edit')); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                                Profil
                            </a>
                            <a href="<?php echo e(route('client.api-keys.index')); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="key" class="w-4 h-4 inline mr-2"></i>
                                Clés API
                            </a>
                            <div class="border-t border-gray-200 dark:border-gray-700"></div>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                        Connexion
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="btn-primary">
                        S'inscrire
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasRole('client')): ?>
                    <a href="<?php echo e(route('client.dashboard')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Tableau de bord
                    </a>
                    <a href="<?php echo e(route('client.services.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Services
                    </a>
                    <a href="<?php echo e(route('client.invoices.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Factures
                    </a>
                    <a href="<?php echo e(route('client.tickets.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Support
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <a href="<?php echo e(route('store.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Boutique
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('store.index')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Produits
                </a>
                <a href="<?php echo e(route('login')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Connexion
                </a>
                <a href="<?php echo e(route('register')); ?>" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    S'inscrire
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success') || session('error') || session('warning')): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" data-auto-dismiss="5000">
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    <?php echo e(session('success')); ?>

                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" data-auto-dismiss="8000">
                <div class="flex items-center">
                    <i data-lucide="x-circle" class="w-5 h-5 mr-2"></i>
                    <?php echo e(session('error')); ?>

                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('warning')): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded" data-auto-dismiss="6000">
                <div class="flex items-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5 mr-2"></i>
                    <?php echo e(session('warning')); ?>

                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH /root/hostclient/resources/views/layouts/navigation.blade.php ENDPATH**/ ?>