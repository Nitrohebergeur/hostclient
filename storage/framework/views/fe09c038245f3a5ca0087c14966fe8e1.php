<?php $__env->startSection('title', 'Utilisateurs'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Utilisateurs</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez les comptes clients et administrateurs</p>
        </div>
        <a href="/admin/users/create" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Ajouter un utilisateur
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            ['Total',     '1 284', 'primary'],
            ['Actifs',    '1 201', 'success'],
            ['Suspendus', '58',    'warning'],
            ['Bannis',    '25',    'danger'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $count, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card">
            <div class="card-body py-4">
                <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($count); ?></p>
                <p class="text-sm text-<?php echo e($color); ?>-600 dark:text-<?php echo e($color); ?>-400 font-medium mt-1"><?php echo e($label); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Rechercher par nom, email, entreprise..." class="form-input pl-9 text-sm">
                </div>
                <select class="form-input w-full sm:w-36 text-sm">
                    <option>Tous statuts</option>
                    <option>Actif</option>
                    <option>Suspendu</option>
                    <option>Banni</option>
                </select>
                <select class="form-input w-full sm:w-36 text-sm">
                    <option>Tous rôles</option>
                    <option>Client</option>
                    <option>Admin</option>
                    <option>Support</option>
                </select>
                <select class="form-input w-full sm:w-36 text-sm">
                    <option>Tous pays</option>
                    <option>France</option>
                    <option>Belgique</option>
                    <option>Suisse</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary-600"></th>
                        <th>Utilisateur</th>
                        <th>Services</th>
                        <th>Dépenses totales</th>
                        <th>Inscription</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                        ['Jean Dupont',      'jean.dupont@exemple.com',    '12', '489,88 €',  '15 jan. 2024', 'active',    'FR'],
                        ['Marie Martin',     'marie.martin@exemple.com',   '5',  '159,95 €',  '20 jan. 2024', 'active',    'BE'],
                        ['Paul Robert',      'paul.robert@exemple.com',    '8',  '319,92 €',  '01 fév. 2024', 'suspended', 'FR'],
                        ['Sophie Laurent',   'sophie.laurent@exemple.com', '3',  '89,97 €',   '10 fév. 2024', 'active',    'CH'],
                        ['Luc Bernard',      'luc.bernard@exemple.com',    '1',  '9,99 €',    '15 fév. 2024', 'banned',    'FR'],
                        ['Emma Petit',       'emma.petit@exemple.com',     '7',  '249,93 €',  '20 fév. 2024', 'active',    'FR'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name, $email, $services, $total, $date, $status, $country]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary-600"></td>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($name)); ?>&background=0ea5e9&color=fff&size=36" class="w-9 h-9 rounded-full flex-shrink-0">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm"><?php echo e($name); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($email); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center font-semibold text-gray-900 dark:text-white"><?php echo e($services); ?></td>
                        <td class="font-semibold text-gray-900 dark:text-white"><?php echo e($total); ?></td>
                        <td class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($date); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($status === 'active'): ?>    <span class="badge badge-success">Actif</span>
                            <?php elseif($status === 'suspended'): ?> <span class="badge badge-warning">Suspendu</span>
                            <?php else: ?>                        <span class="badge badge-danger">Banni</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="/admin/users/1" class="btn btn-ghost btn-sm" title="Voir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="/admin/users/1/edit" class="btn btn-ghost btn-sm" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <div x-data="dropdown" class="relative">
                                    <button @click="toggle" class="btn btn-ghost btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="close" x-transition class="absolute right-0 z-10 mt-1 w-44 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Se connecter en tant que</a>
                                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Envoyer un email</a>
                                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Ajouter un crédit</a>
                                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-warning-600 hover:bg-gray-100 dark:hover:bg-gray-700">Suspendre</a>
                                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-danger-600 hover:bg-gray-100 dark:hover:bg-gray-700">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer flex items-center justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-400">Affichage 1–6 sur 1 284 utilisateurs</p>
            <div class="flex items-center gap-1">
                <button class="btn btn-secondary btn-sm" disabled>←</button>
                <span class="px-3 py-1.5 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded text-sm font-medium">1</span>
                <button class="btn btn-ghost btn-sm text-sm">2</button>
                <button class="btn btn-ghost btn-sm text-sm">3</button>
                <span class="text-gray-400 px-1">…</span>
                <button class="btn btn-ghost btn-sm text-sm">215</button>
                <button class="btn btn-secondary btn-sm">→</button>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/users/index.blade.php ENDPATH**/ ?>