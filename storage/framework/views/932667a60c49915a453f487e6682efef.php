<?php $__env->startSection('title', 'Annonces'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Annonces</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Communiquez avec vos clients</p>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('create-modal').classList.remove('hidden')">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouvelle annonce
        </button>
    </div>

    <!-- Announcements List -->
    <div class="space-y-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            ['Maintenance programmée',    'Une maintenance est prévue le 15 août de 02h00 à 04h00. Les services seront indisponibles.',          'warning', 'Tous les clients', '07/08/2026', true,  145],
            ['Nouvelle fonctionnalité',   'Nous avons lancé le système de 2FA pour renforcer la sécurité de vos comptes.',                       'success', 'Tous les clients', '01/08/2026', true,  289],
            ['Offre promotionnelle',      'Profitez de -30% sur tous les VPS ce mois-ci avec le code PROMO30.',                                   'primary', 'Tous les clients', '25/07/2026', false, 0],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$title, $body, $type, $audience, $date, $published, $views]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card hover:shadow-md transition-shadow">
            <div class="card-body">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4 justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-<?php echo e($type); ?>-100 dark:bg-<?php echo e($type); ?>-900/30 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($type === 'warning'): ?>
                                <svg class="w-5 h-5 text-<?php echo e($type); ?>-600 dark:text-<?php echo e($type); ?>-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <?php elseif($type === 'success'): ?>
                                <svg class="w-5 h-5 text-<?php echo e($type); ?>-600 dark:text-<?php echo e($type); ?>-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-<?php echo e($type); ?>-600 dark:text-<?php echo e($type); ?>-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <h4 class="font-bold text-gray-900 dark:text-white"><?php echo e($title); ?></h4>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($published): ?> <span class="badge badge-success text-xs">Publié</span>
                                <?php else: ?> <span class="badge badge-warning text-xs">Brouillon</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed"><?php echo e($body); ?></p>
                            <div class="flex flex-wrap gap-4 mt-2 text-xs text-gray-500 dark:text-gray-500">
                                <span>Audience: <?php echo e($audience); ?></span>
                                <span>Date: <?php echo e($date); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($published): ?> <span><?php echo e($views); ?> vues</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button class="btn btn-secondary btn-sm">Modifier</button>
                        <button class="btn btn-ghost btn-sm text-danger-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Create Modal -->
    <div id="create-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-2xl shadow-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Nouvelle Annonce</h3>
                <button onclick="document.getElementById('create-modal').classList.add('hidden')" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form class="p-6 space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="form-label">Titre <span class="text-danger-500">*</span></label>
                    <input type="text" name="title" class="form-input" placeholder="Titre de l'annonce" required>
                </div>
                <div>
                    <label class="form-label">Contenu <span class="text-danger-500">*</span></label>
                    <textarea name="body" rows="4" class="form-input" placeholder="Rédigez votre annonce…" required></textarea>
                </div>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Type</label>
                        <select name="type" class="form-input">
                            <option value="info">ℹ️ Information</option>
                            <option value="warning">⚠️ Maintenance</option>
                            <option value="success">✅ Nouveau</option>
                            <option value="primary">📢 Promotion</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Audience</label>
                        <select name="audience" class="form-input">
                            <option>Tous les clients</option>
                            <option>Clients VPS uniquement</option>
                            <option>Clients hébergement</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Date de publication</label>
                        <input type="datetime-local" name="published_at" class="form-input">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">Publier</button>
                    <button type="submit" name="draft" class="btn btn-secondary">Sauvegarder brouillon</button>
                    <button type="button" onclick="document.getElementById('create-modal').classList.add('hidden')" class="btn btn-ghost">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/announcements/index.blade.php ENDPATH**/ ?>