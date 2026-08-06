<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li>
                <a href="<?php echo e(route('store.index')); ?>" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    Boutique
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 mx-1"></i>
                    <a href="<?php echo e(route('store.category', $category)); ?>" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        <?php echo e($category->name); ?>

                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 mx-1"></i>
                    <span class="text-gray-500 dark:text-gray-400"><?php echo e($product->name); ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Product Details -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?php echo e($product->name); ?></h1>
                            <p class="text-gray-600 dark:text-gray-400"><?php echo e($category->name); ?></p>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$product->isInStock()): ?>
                            <span class="badge badge-danger">En rupture</span>
                        <?php elseif($product->is_featured): ?>
                            <span class="badge badge-success">Populaire</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="prose prose-gray dark:prose-invert max-w-none mb-8">
                        <?php echo nl2br(e($product->description)); ?>

                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->features): ?>
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Fonctionnalités incluses</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $product->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center">
                                        <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                        <span class="text-gray-700 dark:text-gray-300"><?php echo e($feature); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Specifications -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Spécifications</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</dt>
                                <dd class="text-sm text-gray-900 dark:text-white"><?php echo e(ucfirst($product->type)); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cycle de facturation</dt>
                                <dd class="text-sm text-gray-900 dark:text-white"><?php echo e(ucfirst($product->billing_cycle)); ?></dd>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->auto_setup): ?>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Configuration</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">Automatique</dd>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$product->is_unlimited_stock): ?>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock disponible</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white"><?php echo e($product->stock); ?></dd>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Form -->
        <div>
            <div class="card sticky top-8">
                <div class="p-6">
                    <form action="<?php echo e(route('store.cart.add')); ?>" method="POST" x-data="orderForm()">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">

                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold text-gray-900 dark:text-white" x-text="formatPrice(price)"></div>
                            <div class="text-gray-500 dark:text-gray-400" x-text="'par ' + billingCycle"></div>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->setup_fee > 0): ?>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    + <?php echo e($product->setup_fee); ?>€ frais d'installation
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Billing Cycle -->
                        <div class="mb-6">
                            <label class="label">Cycle de facturation</label>
                            <select name="billing_cycle" x-model="billingCycle" @change="updatePrice()" class="input">
                                <option value="monthly">Mensuel</option>
                                <option value="quarterly">Trimestriel (-5%)</option>
                                <option value="semi_annually">Semestriel (-10%)</option>
                                <option value="annually">Annuel (-15%)</option>
                                <option value="biennially">Biennal (-20%)</option>
                                <option value="triennially">Triennal (-25%)</option>
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-6">
                            <label class="label">Quantité</label>
                            <select name="quantity" class="input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= min(10, $product->stock ?: 10); $i++): ?>
                                    <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>

                        <!-- Configuration Options -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->config): ?>
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Configuration</h4>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $product->config; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-4">
                                        <label class="label"><?php echo e($option['label'] ?? ucfirst($key)); ?></label>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($option['type'] === 'select'): ?>
                                            <select name="config[<?php echo e($key); ?>]" class="input">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $option['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        <?php elseif($option['type'] === 'text'): ?>
                                            <input type="text" name="config[<?php echo e($key); ?>]" placeholder="<?php echo e($option['placeholder'] ?? ''); ?>" class="input">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Add to Cart -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->isInStock()): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                <button type="submit" class="btn-primary w-full mb-3">
                                    <i data-lucide="shopping-cart" class="w-4 h-4 mr-2"></i>
                                    Ajouter au panier
                                </button>
                            <?php else: ?>
                                <a href="<?php echo e(route('register')); ?>" class="btn-primary w-full mb-3 text-center block">
                                    <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                                    S'inscrire pour commander
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php else: ?>
                            <button type="button" disabled class="btn bg-gray-300 text-gray-500 cursor-not-allowed w-full mb-3">
                                <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                                Non disponible
                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Support -->
                        <div class="text-center">
                            <a href="<?php echo e(auth() ? route('client.tickets.create') : route('login')); ?>" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                <i data-lucide="help-circle" class="w-4 h-4 inline mr-1"></i>
                                Besoin d'aide ?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function orderForm() {
    return {
        basePrice: <?php echo e($product->price); ?>,
        billingCycle: '<?php echo e($product->billing_cycle); ?>',
        price: <?php echo e($product->price); ?>,
        
        updatePrice() {
            const multipliers = {
                monthly: 1,
                quarterly: 3 * 0.95,
                semi_annually: 6 * 0.9,
                annually: 12 * 0.85,
                biennially: 24 * 0.8,
                triennially: 36 * 0.75
            };
            
            this.price = Math.round(this.basePrice * (multipliers[this.billingCycle] || 1) * 100) / 100;
        },
        
        formatPrice(price) {
            return price.toFixed(2) + '€';
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /root/hostclient/resources/views/store/product.blade.php ENDPATH**/ ?>