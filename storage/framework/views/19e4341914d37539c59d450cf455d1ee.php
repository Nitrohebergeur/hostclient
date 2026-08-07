<?php $__env->startSection('title', 'Passerelles de Paiement'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Passerelles de Paiement</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Stripe, PayPal, Mollie, crypto et plus</p>
        </div>
        <a href="<?php echo e(route('admin.payment-gateways.create')); ?>" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter une Passerelle
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?> <div class="alert alert-success"><?php echo e(session('success')); ?></div> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?> <div class="alert alert-danger"><?php echo e(session('error')); ?></div> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card <?php echo e($gateway->is_active ? '' : 'opacity-60'); ?> hover:shadow-md transition-all">
            <div class="card-body">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl
                            <?php echo e($gateway->is_active ? 'bg-primary-100 dark:bg-primary-900/30' : 'bg-gray-100 dark:bg-gray-700'); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($gateway->slug):
                                case ('stripe'): ?> 💳 <?php break; ?>
                                <?php case ('paypal'): ?> 🅿 <?php break; ?>
                                <?php case ('mollie'): ?> 💶 <?php break; ?>
                                <?php case ('coinbase'): ?> ₿ <?php break; ?>
                                <?php case ('razorpay'): ?> 🪙 <?php break; ?>
                                <?php case ('bank_transfer'): ?> 🏦 <?php break; ?>
                                <?php case ('credit'): ?> 👛 <?php break; ?>
                                <?php default: ?> 💰 <?php break; ?>
                            <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white"><?php echo e($gateway->name); ?></h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($gateway->slug); ?></p>
                        </div>
                    </div>

                    <!-- Toggle On/Off -->
                    <form action="<?php echo e(route('admin.payment-gateways.toggle', $gateway)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="relative inline-flex items-center cursor-pointer" title="<?php echo e($gateway->is_active ? 'Désactiver' : 'Activer'); ?>">
                            <div class="w-11 h-6 rounded-full transition-colors <?php echo e($gateway->is_active ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'); ?> relative">
                                <span class="absolute top-[2px] <?php echo e($gateway->is_active ? 'left-[22px]' : 'left-[2px]'); ?> w-5 h-5 bg-white rounded-full shadow transition-all"></span>
                            </div>
                        </button>
                    </form>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4"><?php echo e($gateway->description); ?></p>

                <!-- Badges features -->
                <div class="flex flex-wrap gap-1 mb-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gateway->supports_recurring): ?>
                        <span class="badge badge-success text-xs">🔄 Récurrent</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gateway->supports_refunds): ?>
                        <span class="badge badge-secondary text-xs">↩ Remboursement</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gateway->supports_webhooks): ?>
                        <span class="badge badge-secondary text-xs">🔗 Webhooks</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Frais -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gateway->fee_fixed > 0 || $gateway->fee_percentage > 0): ?>
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Frais :
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gateway->fee_fixed > 0): ?> <?php echo e(number_format($gateway->fee_fixed, 2)); ?>€ <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gateway->fee_fixed > 0 && $gateway->fee_percentage > 0): ?> + <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gateway->fee_percentage > 0): ?> <?php echo e($gateway->fee_percentage); ?>% <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="flex gap-2">
                    <a href="<?php echo e(route('admin.payment-gateways.edit', $gateway)); ?>" class="btn btn-sm btn-secondary flex-1 text-center">
                        Configurer
                    </a>
                    <form action="<?php echo e(route('admin.payment-gateways.destroy', $gateway)); ?>" method="POST" onsubmit="return confirm('Supprimer cette passerelle ?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="md:col-span-3 card">
            <div class="card-body text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">Aucune passerelle configurée.</p>
                <a href="<?php echo e(route('admin.payment-gateways.create')); ?>" class="btn btn-primary mt-4">Ajouter la première</a>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/payment-gateways/index.blade.php ENDPATH**/ ?>