<?php $__env->startSection('title', 'Modifier la Catégorie'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('admin.products.categories.index')); ?>" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier : <?php echo e($category->name); ?></h1>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="list-disc list-inside"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.products.categories.update', $category)); ?>" class="card">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="card-body space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nom <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name', $category->name)); ?>" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" value="<?php echo e(old('slug', $category->slug)); ?>" class="form-input">
                </div>
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="2" class="form-input"><?php echo e(old('description', $category->description)); ?></textarea>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Icône</label>
                    <input type="text" name="icon" value="<?php echo e(old('icon', $category->icon)); ?>" class="form-input" placeholder="fas fa-globe">
                </div>
                <div>
                    <label class="form-label">Ordre</label>
                    <input type="number" name="order" value="<?php echo e(old('order', $category->order)); ?>" min="0" class="form-input">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded" <?php if(old('is_active', $category->is_active)): echo 'checked'; endif; ?>>
                <label for="is_active" class="form-label mb-0">Catégorie active</label>
            </div>
        </div>
        <div class="card-footer flex justify-end gap-3">
            <a href="<?php echo e(route('admin.products.categories.index')); ?>" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Sauvegarder</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/products/categories/edit.blade.php ENDPATH**/ ?>