<?php $__env->startSection('title', 'Ajouter un Serveur'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('admin.servers.index')); ?>" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ajouter un Serveur</h1>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="list-disc list-inside"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.servers.store')); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>

        <!-- Type de serveur -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Type de serveur</h3></div>
            <div class="card-body">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3" id="server-type-selector">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="<?php echo e($value); ?>" class="sr-only peer" <?php if(old('type') === $value): echo 'checked'; endif; ?> required>
                        <div class="border-2 rounded-xl p-3 text-center peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors">
                            <div class="text-2xl mb-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($value):
                                    case ('pterodactyl'): ?> 🦕 <?php break; ?>
                                    <?php case ('cpanel'): ?> 🔵 <?php break; ?>
                                    <?php case ('plesk'): ?> 🟣 <?php break; ?>
                                    <?php case ('proxmox'): ?> 🟠 <?php break; ?>
                                    <?php case ('docker'): ?> 🐳 <?php break; ?>
                                    <?php case ('directadmin'): ?> 🟤 <?php break; ?>
                                    <?php default: ?> 🔧 <?php break; ?>
                                <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <p class="text-xs font-medium text-gray-800 dark:text-gray-200"><?php echo e($label); ?></p>
                        </div>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Connexion -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Connexion</h3></div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Nom du serveur <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-input" required placeholder="Ex: Panel Pterodactyl Principal">
                </div>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Hostname / IP <span class="text-red-500">*</span></label>
                        <input type="text" name="hostname" value="<?php echo e(old('hostname')); ?>" class="form-input" required placeholder="panel.example.com ou 192.168.1.1">
                    </div>
                    <div>
                        <label class="form-label">Port <span class="text-red-500">*</span></label>
                        <input type="number" name="port" value="<?php echo e(old('port', 443)); ?>" class="form-input" required>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="use_ssl" value="0">
                    <input type="checkbox" name="use_ssl" value="1" id="use_ssl" class="rounded" <?php if(old('use_ssl', true)): echo 'checked'; endif; ?>>
                    <label for="use_ssl" class="form-label mb-0">Utiliser SSL/HTTPS</label>
                </div>
            </div>
        </div>

        <!-- Authentification -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Authentification</h3></div>
            <div class="card-body space-y-4">
                <div class="alert alert-warning text-sm">
                    🔒 Les clés API et mots de passe sont chiffrés en base de données.
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Clé API</label>
                        <input type="password" name="api_key" value="<?php echo e(old('api_key')); ?>" class="form-input font-mono" autocomplete="off">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pterodactyl : Client API Key</p>
                    </div>
                    <div>
                        <label class="form-label">Secret API</label>
                        <input type="password" name="api_secret" value="<?php echo e(old('api_secret')); ?>" class="form-input font-mono" autocomplete="off">
                    </div>
                    <div>
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="username" value="<?php echo e(old('username')); ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" value="<?php echo e(old('password')); ?>" class="form-input" autocomplete="off">
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
                        <input type="number" name="max_accounts" value="<?php echo e(old('max_accounts')); ?>" min="0" class="form-input" placeholder="Illimité">
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded" <?php if(old('is_active')): echo 'checked'; endif; ?>>
                    <label for="is_active" class="form-label mb-0">Activer ce serveur</label>
                </div>
                <div>
                    <label class="form-label">Notes internes</label>
                    <textarea name="notes" rows="2" class="form-input" placeholder="Informations internes…"><?php echo e(old('notes')); ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?php echo e(route('admin.servers.index')); ?>" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Ajouter le serveur</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/servers/create.blade.php ENDPATH**/ ?>