<?php $__env->startSection('title', 'Support'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Support</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gérez vos demandes de support</p>
        </div>
        <a href="/client/tickets/create" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ouvrir un ticket
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            ['2', 'Ouverts', 'warning'],
            ['1', 'En cours', 'primary'],
            ['0', 'En attente', 'secondary'],
            ['18', 'Fermés', 'success'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card">
            <div class="card-body text-center py-4">
                <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($stat[0]); ?></p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?php echo e($stat[1]); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" placeholder="Rechercher un ticket..." class="form-input flex-1">
                <select class="form-input w-full sm:w-40">
                    <option>Tous statuts</option>
                    <option>Ouvert</option>
                    <option>En cours</option>
                    <option>En attente</option>
                    <option>Fermé</option>
                </select>
                <select class="form-input w-full sm:w-44">
                    <option>Toutes catégories</option>
                    <option>Facturation</option>
                    <option>Support Technique</option>
                    <option>Ventes</option>
                    <option>Abus</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="space-y-3">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            ['1234', 'Problème de connexion FTP', 'Support Technique', 'Ouvert', 'high', 'Il y a 5 min', 'Réponse du support', true],
            ['1233', 'Question sur ma facture INV-2024-003', 'Facturation', 'En cours', 'normal', 'Il y a 2 heures', 'Vous avez répondu', false],
            ['1232', 'Demande de mise à niveau VPS', 'Ventes', 'En attente', 'normal', 'Il y a 1 jour', 'En attente de votre réponse', true],
            ['1230', 'Erreur 500 sur monsite.com', 'Support Technique', 'Fermé', 'low', 'Il y a 3 jours', 'Résolu', false],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="/client/tickets/<?php echo e($ticket[0]); ?>" class="card hover:shadow-md transition-all block group">
            <div class="card-body">
                <div class="flex items-start gap-4">

                    <!-- Priority Indicator -->
                    <div class="flex-shrink-0 mt-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket[4] === 'high'): ?>
                            <span class="w-3 h-3 bg-danger-500 rounded-full block animate-pulse-slow" title="Priorité haute"></span>
                        <?php elseif($ticket[4] === 'normal'): ?>
                            <span class="w-3 h-3 bg-warning-500 rounded-full block" title="Priorité normale"></span>
                        <?php else: ?>
                            <span class="w-3 h-3 bg-gray-400 dark:bg-gray-600 rounded-full block" title="Priorité basse"></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1">
                            <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                #<?php echo e($ticket[0]); ?> — <?php echo e($ticket[1]); ?>

                            </h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket[7]): ?>
                                <span class="badge badge-primary text-xs">Nouvelle réponse</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <?php echo e($ticket[2]); ?>

                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php echo e($ticket[5]); ?>

                            </span>
                            <span><?php echo e($ticket[6]); ?></span>
                        </div>
                    </div>

                    <!-- Status & Arrow -->
                    <div class="flex items-center gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket[3] === 'Ouvert'): ?>
                            <span class="badge badge-warning">Ouvert</span>
                        <?php elseif($ticket[3] === 'En cours'): ?>
                            <span class="badge badge-primary">En cours</span>
                        <?php elseif($ticket[3] === 'En attente'): ?>
                            <span class="badge badge-danger">En attente</span>
                        <?php else: ?>
                            <span class="badge badge-success">Fermé</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>

                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-600 dark:text-gray-400">4 tickets affichés</p>
        <div class="flex items-center gap-2">
            <button class="btn btn-secondary btn-sm" disabled>Précédent</button>
            <button class="btn btn-secondary btn-sm" disabled>Suivant</button>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/client/tickets/index.blade.php ENDPATH**/ ?>