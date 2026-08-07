<?php $__env->startSection('title', 'Créer un Produit'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('admin.products.index')); ?>" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nouveau Produit</h1>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="list-disc list-inside space-y-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.products.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="grid lg:grid-cols-3 gap-6">

            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Infos générales -->
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Informations générales</h3></div>
                    <div class="card-body space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Nom <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Slug (optionnel)</label>
                                <input type="text" name="slug" value="<?php echo e(old('slug')); ?>" class="form-input" placeholder="Généré automatiquement">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3" class="form-input"><?php echo e(old('description')); ?></textarea>
                        </div>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
                                <select name="category_id" class="form-input" required>
                                    <option value="">Choisir...</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat->id); ?>" <?php if(old('category_id') == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Type <span class="text-red-500">*</span></label>
                                <select name="type" class="form-input" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['hosting' => 'Hébergement', 'vps' => 'VPS', 'dedicated' => 'Dédié', 'game' => 'Jeu', 'domain' => 'Domaine', 'custom' => 'Personnalisé']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php if(old('type') == $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Module</label>
                                <select name="module" class="form-input">
                                    <option value="">Aucun</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['cpanel' => 'cPanel/WHM', 'plesk' => 'Plesk', 'directadmin' => 'DirectAdmin', 'pterodactyl' => 'Pterodactyl', 'proxmox' => 'Proxmox', 'docker' => 'Docker', 'virtualizor' => 'Virtualizor', 'solusvm' => 'SolusVM', 'custom' => 'Custom']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php if(old('module') == $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarification -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Tarification</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Laissez à 0 pour désactiver un cycle</p>
                    </div>
                    <div class="card-body space-y-4">

                        <!-- Facturation horaire -->
                        <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center justify-between mb-3">
                                <label class="font-medium text-purple-800 dark:text-purple-300">⏱ Facturation Horaire</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="allow_hourly_billing" value="0">
                                    <input type="checkbox" name="allow_hourly_billing" value="1" id="allow_hourly" class="sr-only peer" <?php if(old('allow_hourly_billing')): echo 'checked'; endif; ?>>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:bg-purple-600 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div>
                                <label class="form-label">Prix à l'heure (€)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                                    <input type="number" name="price_hourly" value="<?php echo e(old('price_hourly', '0.0000')); ?>" step="0.0001" min="0" class="form-input pl-7">
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ex: 0.0149 = 0.0149 €/h ≈ 10.87 €/mois</p>
                            </div>
                        </div>

                        <!-- Cycles standards -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                                ['price_monthly', 'Mensuel', '/mois'],
                                ['price_quarterly', 'Trimestriel', '/3 mois'],
                                ['price_semiannually', 'Semestriel', '/6 mois'],
                                ['price_annually', 'Annuel', '/an'],
                                ['price_biennially', 'Biennal', '/2 ans'],
                                ['setup_fee', 'Frais de mise en service', 'unique'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$field, $label, $per]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <label class="form-label"><?php echo e($label); ?> <span class="text-xs text-gray-400">(<?php echo e($per); ?>)</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                                    <input type="number" name="<?php echo e($field); ?>" value="<?php echo e(old($field, '0.00')); ?>" step="0.01" min="0" class="form-input pl-7">
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="w-36">
                            <label class="form-label">Devise</label>
                            <select name="currency" class="form-input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['EUR' => '€ EUR', 'USD' => '$ USD', 'GBP' => '£ GBP', 'CAD' => 'C$ CAD']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($code); ?>" <?php if(old('currency', 'EUR') == $code): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Ressources -->
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Ressources incluses</h3></div>
                    <div class="card-body">
                        <div class="grid md:grid-cols-2 gap-4" id="resources-fields">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['disk' => 'Espace disque', 'bandwidth' => 'Bande passante', 'cpu' => 'CPU', 'ram' => 'RAM', 'databases' => 'Bases de données', 'email_accounts' => 'Comptes email', 'domains' => 'Domaines', 'slots' => 'Slots joueurs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <label class="form-label text-xs"><?php echo e($label); ?></label>
                                <input type="text" name="resources[<?php echo e($key); ?>]" value="<?php echo e(old("resources.$key")); ?>" class="form-input" placeholder="Ex: 10 GB ou Illimité">
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">

                <!-- Options -->
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Options</h3></div>
                    <div class="card-body space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="form-label mb-0">Actif</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?php if(old('is_active', true)): echo 'checked'; endif; ?>>
                                <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="form-label mb-0">En vedette</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" class="sr-only peer" <?php if(old('is_featured')): echo 'checked'; endif; ?>>
                                <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-yellow-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="form-label mb-0">Provisionnement auto</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="auto_provision" value="0">
                                <input type="checkbox" name="auto_provision" value="1" class="sr-only peer" <?php if(old('auto_provision')): echo 'checked'; endif; ?>>
                                <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </div>
                        <div>
                            <label class="form-label">Ordre d'affichage</label>
                            <input type="number" name="order" value="<?php echo e(old('order', 0)); ?>" min="0" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Stock <span class="text-xs text-gray-400">(vide = illimité)</span></label>
                            <input type="number" name="stock" value="<?php echo e(old('stock')); ?>" min="0" class="form-input" placeholder="Illimité">
                        </div>
                    </div>
                </div>

                <!-- Serveurs -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($servers->isNotEmpty()): ?>
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Serveurs de provisionnement</h3></div>
                    <div class="card-body space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $servers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $server): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <input type="checkbox" name="servers[]" value="<?php echo e($server->id); ?>" class="rounded" <?php if(in_array($server->id, old('servers', []))): echo 'checked'; endif; ?>>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($server->name); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($server->getTypeLabel()); ?></p>
                            </div>
                            <span class="ml-auto w-2 h-2 rounded-full <?php echo e($server->status === 'online' ? 'bg-green-500' : 'bg-red-400'); ?>"></span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Groupes -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($groups->isNotEmpty()): ?>
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Groupes</h3></div>
                    <div class="card-body space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="groups[]" value="<?php echo e($group->id); ?>" class="rounded" <?php if(in_array($group->id, old('groups', []))): echo 'checked'; endif; ?>>
                            <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e($group->name); ?></span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <button type="submit" class="btn btn-primary w-full">
                    Créer le produit
                </button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/products/create.blade.php ENDPATH**/ ?>