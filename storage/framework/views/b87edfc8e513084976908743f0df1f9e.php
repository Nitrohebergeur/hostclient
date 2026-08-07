<?php $__env->startSection('title', 'Serveurs'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Serveurs de Provisionnement</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pterodactyl, cPanel, Plesk, Proxmox, Docker…</p>
        </div>
        <a href="<?php echo e(route('admin.servers.create')); ?>" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter un Serveur
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $servers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $server): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card hover:shadow-md transition-shadow">
            <div class="card-body">
                <div class="flex items-start justify-between gap-4">
                    <!-- Statut & nom -->
                    <div class="flex items-start gap-4">
                        <div class="mt-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($server->status === 'online'): ?>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                                    <span class="text-xs font-medium text-green-600 dark:text-green-400">En ligne</span>
                                </span>
                            <?php elseif($server->status === 'maintenance'): ?>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                    <span class="text-xs font-medium text-yellow-600 dark:text-yellow-400">Maintenance</span>
                                </span>
                            <?php else: ?>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                    <span class="text-xs font-medium text-red-600 dark:text-red-400">Hors ligne</span>
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white"><?php echo e($server->name); ?></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <?php echo e($server->getTypeLabel()); ?> — <?php echo e($server->hostname); ?>:<?php echo e($server->port); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$server->use_ssl): ?><span class="text-yellow-500 ml-1 text-xs">(HTTP)</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($server->notes): ?>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?php echo e($server->notes); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- Compteurs -->
                    <div class="flex items-center gap-6 text-center flex-shrink-0">
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($server->services_count); ?></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Services</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                <?php echo e($server->current_accounts); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($server->max_accounts): ?><span class="text-sm text-gray-400">/ <?php echo e($server->max_accounts); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Comptes</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form action="<?php echo e(route('admin.servers.test', $server)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-secondary" title="Tester la connexion">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Tester
                            </button>
                        </form>
                        <a href="<?php echo e(route('admin.servers.edit', $server)); ?>" class="btn btn-sm btn-secondary">Modifier</a>
                        <form action="<?php echo e(route('admin.servers.destroy', $server)); ?>" method="POST" onsubmit="return confirm('Supprimer ce serveur ?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                        </form>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($server->last_checked_at): ?>
                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500">
                    Dernière vérification : <?php echo e($server->last_checked_at->diffForHumans()); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($server->last_check_data && isset($server->last_check_data['message'])): ?>
                        — <?php echo e($server->last_check_data['message']); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card">
            <div class="card-body text-center py-16">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/></svg>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Aucun serveur configuré.</p>
                <a href="<?php echo e(route('admin.servers.create')); ?>" class="btn btn-primary">Ajouter votre premier serveur</a>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/servers/index.blade.php ENDPATH**/ ?>