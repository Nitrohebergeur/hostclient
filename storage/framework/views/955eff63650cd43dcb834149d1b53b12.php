<?php $__env->startSection('title', 'Boutique'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Nos Services</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Découvrez notre gamme complète de services d'hébergement conçus pour répondre à tous vos besoins.
        </p>
    </div>

    <!-- Featured Products -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured->count()): ?>
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Produits Populaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="badge badge-success">Populaire</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($product->category->name); ?></span>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2"><?php echo e($product->name); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3"><?php echo e($product->description); ?></p>
                            
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($product->price); ?>€</span>
                                    <span class="text-gray-500 dark:text-gray-400">/<?php echo e($product->billing_cycle); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->setup_fee > 0): ?>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        +<?php echo e($product->setup_fee); ?>€ installation
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->features): ?>
                                <ul class="text-sm text-gray-600 dark:text-gray-400 mb-6 space-y-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_slice($product->features, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="flex items-center">
                                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                                            <?php echo e($feature); ?>

                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </ul>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('store.product', [$product->category, $product])); ?>" class="btn-primary flex-1 text-center">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Categories -->
    <section>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Toutes les Catégories</h2>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->products->count()): ?>
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($category->name); ?></h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->description): ?>
                                <p class="text-gray-600 dark:text-gray-400"><?php echo e($category->description); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('store.category', $category)); ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                            Voir tout →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $category->products->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card hover:shadow-lg transition-shadow duration-200">
                                <div class="p-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$product->isInStock()): ?>
                                        <div class="absolute top-2 right-2">
                                            <span class="badge badge-danger">Rupture</span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2"><?php echo e($product->name); ?></h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2"><?php echo e($product->description); ?></p>
                                    
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <span class="text-lg font-bold text-gray-900 dark:text-white"><?php echo e($product->price); ?>€</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">/<?php echo e($product->billing_cycle); ?></span>
                                        </div>
                                    </div>

                                    <a href="<?php echo e(route('store.product', [$category, $product])); ?>" class="btn-primary w-full text-center text-sm">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    <!-- Empty State -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categories->every(fn($c) => $c->products->count() === 0)): ?>
        <div class="text-center py-12">
            <i data-lucide="package" class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun produit disponible</h3>
            <p class="text-gray-600 dark:text-gray-400">Nous préparons de nouveaux services pour vous. Revenez bientôt !</p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /root/hostclient/resources/views/store/index.blade.php ENDPATH**/ ?>