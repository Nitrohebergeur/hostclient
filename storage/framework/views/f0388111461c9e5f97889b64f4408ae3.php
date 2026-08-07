<?php $__env->startSection('title', 'Mes Factures'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Mes Factures</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Consultez et téléchargez vos factures</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card">
            <div class="card-body flex items-center gap-4">
                <div class="w-12 h-12 bg-warning-100 dark:bg-warning-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">En attente</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">89,97 €</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body flex items-center gap-4">
                <div class="w-12 h-12 bg-success-100 dark:bg-success-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Payées (30 jours)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">59,97 €</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total factures</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">24</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" placeholder="Rechercher une facture..." class="form-input flex-1">
                <select class="form-input w-full sm:w-40">
                    <option>Tous statuts</option>
                    <option>En attente</option>
                    <option>Payée</option>
                    <option>Annulée</option>
                    <option>Remboursée</option>
                </select>
                <input type="month" class="form-input w-full sm:w-44">
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                        ['INV-2024-003', '01/02/2024', 'VPS Cloud Standard — Mensuel', '29,99 €', 'pending'],
                        ['INV-2024-002', '15/01/2024', 'Hébergement Premium — Mensuel', '19,99 €', 'paid'],
                        ['INV-2024-001', '01/01/2024', 'VPS Cloud Standard — Mensuel', '29,99 €', 'paid'],
                        ['INV-2023-012', '15/12/2023', 'Hébergement Premium — Mensuel', '19,99 €', 'paid'],
                        ['INV-2023-011', '01/12/2023', 'Domaine monsite.com — Annuel', '12,99 €', 'paid'],
                        ['INV-2023-010', '15/11/2023', 'Hébergement Premium — Mensuel', '19,99 €', 'refunded'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <a href="/client/invoices/<?php echo e(strtolower($inv[0])); ?>" class="font-medium text-primary-600 dark:text-primary-400 hover:underline">
                                #<?php echo e($inv[0]); ?>

                            </a>
                        </td>
                        <td class="text-gray-600 dark:text-gray-400"><?php echo e($inv[1]); ?></td>
                        <td class="text-gray-900 dark:text-white"><?php echo e($inv[2]); ?></td>
                        <td class="font-bold text-gray-900 dark:text-white"><?php echo e($inv[3]); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inv[4] === 'pending'): ?>
                                <span class="badge badge-warning">En attente</span>
                            <?php elseif($inv[4] === 'paid'): ?>
                                <span class="badge badge-success">Payée</span>
                            <?php elseif($inv[4] === 'refunded'): ?>
                                <span class="badge badge-primary">Remboursée</span>
                            <?php else: ?>
                                <span class="badge">Annulée</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inv[4] === 'pending'): ?>
                                    <a href="/client/invoices/<?php echo e(strtolower($inv[0])); ?>/pay" class="btn btn-primary btn-sm">Payer</a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <a href="/client/invoices/<?php echo e(strtolower($inv[0])); ?>/pdf" class="btn btn-secondary btn-sm" title="Télécharger PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="card-footer flex items-center justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-400">Affichage 1–6 sur 24 résultats</p>
            <div class="flex items-center gap-2">
                <button class="btn btn-secondary btn-sm" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span class="px-3 py-1 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded font-medium text-sm">1</span>
                <button class="btn btn-ghost btn-sm text-sm">2</button>
                <button class="btn btn-ghost btn-sm text-sm">3</button>
                <button class="btn btn-secondary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/client/invoices/index.blade.php ENDPATH**/ ?>