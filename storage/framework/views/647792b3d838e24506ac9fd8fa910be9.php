<?php $__env->startSection('title', 'Mes Services'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mes Services</h1>
            <p class="text-gray-600 dark:text-gray-400">Gérez vos services d'hébergement</p>
        </div>
        <a href="<?php echo e(route('store.index')); ?>" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Commander un service
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="p-4">
            <form method="GET" class="flex flex-wrap gap-4">
                <select name="status" class="input flex-1 min-w-[200px]" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Actif</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>En attente</option>
                    <option value="suspended" <?php echo e(request('status') === 'suspended' ? 'selected' : ''); ?>>Suspendu</option>
                    <option value="terminated" <?php echo e(request('status') === 'terminated' ? 'selected' : ''); ?>>Résilié</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Services Grid -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($services->count()): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card hover:shadow-lg transition-shadow duration-200">
                    <div class="p-6">
                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="badge badge-<?php echo e($service->status === 'active' ? 'success' : ($service->status === 'suspended' ? 'warning' : 'info')); ?>">
                                <?php echo e(ucfirst($service->status)); ?>

                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($service->product->name); ?></span>
                        </div>

                        <!-- Service Info -->
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2"><?php echo e($service->name); ?></h3>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->identifier): ?>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                <i data-lucide="hash" class="w-4 h-4 inline mr-1"></i>
                                <?php echo e($service->identifier); ?>

                            </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Pricing -->
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($service->price); ?>€</span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm">/<?php echo e($service->billing_cycle); ?></span>
                            </div>
                        </div>

                        <!-- Dates -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->next_due_date): ?>
                            <div class="mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                                    <span>Expire le <?php echo e($service->next_due_date->format('d/m/Y')); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->next_due_date->isPast()): ?>
                                    <div class="text-sm text-red-600 dark:text-red-400 mt-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4 inline mr-1"></i>
                                        Service expiré
                                    </div>
                                <?php elseif($service->next_due_date->diffInDays() <= 7): ?>
                                    <div class="text-sm text-orange-600 dark:text-orange-400 mt-1">
                                        <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i>
                                        Expire bientôt
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Auto Renew -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       <?php echo e($service->auto_renew ? 'checked' : ''); ?>

                                       class="rounded text-primary-600 focus:ring-primary-500"
                                       disabled>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Renouvellement automatique</span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('client.services.show', $service)); ?>" class="btn-primary flex-1 text-center text-sm">
                                <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>
                                Gérer
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            <?php echo e($services->links()); ?>

        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-12">
            <i data-lucide="server" class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun service</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Vous n'avez pas encore de services actifs.</p>
            <a href="<?php echo e(route('store.index')); ?>" class="btn-primary">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Commander votre premier service
            </a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /root/hostclient/resources/views/client/services/index.blade.php ENDPATH**/ ?>