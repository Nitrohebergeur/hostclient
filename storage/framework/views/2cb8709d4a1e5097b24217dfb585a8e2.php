<?php $__env->startSection('title', 'Modifier le Serveur'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('admin.servers.index')); ?>" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier : <?php echo e($server->name); ?></h1>
        <span class="badge <?php echo e($server->status === 'online' ? 'badge-success' : 'badge-danger'); ?>">
            <?php echo e($server->getTypeLabel()); ?>

        </span>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="list-disc list-inside"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.servers.update', $server)); ?>" class="space-y-6">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <!-- Connexion -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Connexion</h3></div>
            <div class="card-body space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="<?php echo e(old('name', $server->name)); ?>" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Type</label>
                        <select name="type" class="form-input">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('type', $server->type) === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Hostname / IP <span class="text-red-500">*</span></label>
                        <input type="text" name="hostname" value="<?php echo e(old('hostname', $server->hostname)); ?>" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Port</label>
                        <input type="number" name="port" value="<?php echo e(old('port', $server->port)); ?>" class="form-input" required>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="use_ssl" value="0">
                    <input type="checkbox" name="use_ssl" value="1" id="use_ssl" class="rounded" <?php if(old('use_ssl', $server->use_ssl)): echo 'checked'; endif; ?>>
                    <label for="use_ssl" class="form-label mb-0">SSL/HTTPS</label>
                </div>
            </div>
        </div>

        <!-- Authentification -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Authentification</h3></div>
            <div class="card-body space-y-4">
                <div class="alert alert-warning text-sm">
                    🔒 Laisser vide pour ne pas modifier les credentials existants.
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nouvelle clé API</label>
                        <input type="password" name="api_key" class="form-input font-mono" autocomplete="off" placeholder="Laisser vide pour garder l'actuelle">
                    </div>
                    <div>
                        <label class="form-label">Nouveau secret API</label>
                        <input type="password" name="api_secret" class="form-input font-mono" autocomplete="off" placeholder="Laisser vide pour garder l'actuel">
                    </div>
                    <div>
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="username" value="<?php echo e(old('username', $server->username)); ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-input" autocomplete="off" placeholder="Laisser vide pour garder l'actuel">
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Paramètres</h3></div>
            <div class="card-body space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Comptes max <span class="text-xs text-gray-400">(vide = illimité)</span></label>
                        <input type="number" name="max_accounts" value="<?php echo e(old('max_accounts', $server->max_accounts)); ?>" min="0" class="form-input">
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded" <?php if(old('is_active', $server->is_active)): echo 'checked'; endif; ?>>
                    <label for="is_active" class="form-label mb-0">Serveur actif</label>
                </div>
                <div>
                    <label class="form-label">Notes internes</label>
                    <textarea name="notes" rows="2" class="form-input"><?php echo e(old('notes', $server->notes)); ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?php echo e(route('admin.servers.index')); ?>" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Sauvegarder</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/servers/edit.blade.php ENDPATH**/ ?>