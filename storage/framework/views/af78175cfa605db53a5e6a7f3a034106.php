<?php $__env->startSection('title', 'Ticket #1234'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="/client/tickets" class="hover:text-primary-600 dark:hover:text-primary-400">Support</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white">#1234 — Problème de connexion FTP</span>
    </nav>

    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Ticket Thread -->
        <div class="lg:col-span-2 space-y-4">

            <!-- Messages -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['client', 'Jean Dupont', 'Il y a 2 heures', "Bonjour,\n\nJe n'arrive pas à me connecter en FTP à mon hébergement. J'obtiens l'erreur suivante :\n\n`ERREUR: Impossible de se connecter au serveur`\n\nJ'ai bien vérifié les identifiants qui sont corrects. Pouvez-vous m'aider ?\n\nMerci", []],
                ['staff', 'Support HostClient', 'Il y a 1 heure', "Bonjour Jean,\n\nMerci pour votre message. J'ai vérifié votre compte et je constate que votre adresse IP semble être bloquée par notre pare-feu.\n\nPourriez-vous nous communiquer votre adresse IP publique pour que nous puissions la débloquer ?\n\nCordialement,\nL'équipe Support", []],
                ['client', 'Jean Dupont', 'Il y a 30 minutes', "Bonjour,\n\nMon adresse IP est 82.65.124.200.\n\nMerci beaucoup !", ['capture-ecran.png']],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card <?php echo e($msg[0] === 'staff' ? 'border-l-4 border-primary-500' : ''); ?>">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($msg[1])); ?>&background=<?php echo e($msg[0] === 'staff' ? '0ea5e9' : '6366f1'); ?>&color=fff&size=40" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($msg[1]); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($msg[0] === 'staff'): ?>
                                    <span class="badge badge-primary text-xs">Staff</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($msg[2]); ?></span>
                    </div>
                    <div class="prose prose-sm dark:prose-invert max-w-none">
                        <?php echo nl2br(e($msg[3])); ?>

                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($msg[4])): ?>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $msg[4]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="#" class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <?php echo e($file); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Reply Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Répondre</h3>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div>
                            <textarea
                                name="message"
                                rows="5"
                                class="form-input"
                                placeholder="Écrivez votre réponse ici..."
                                required
                            ></textarea>
                        </div>

                        <!-- Attachments -->
                        <div x-data="{ files: [] }">
                            <label class="form-label">Pièces jointes (optionnel)</label>
                            <div
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:border-primary-400 dark:hover:border-primary-500 transition-colors cursor-pointer"
                                @click="$refs.fileInput.click()"
                                @dragover.prevent
                                @drop.prevent="files = Array.from($event.dataTransfer.files)"
                            >
                                <svg class="w-8 h-8 mx-auto text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Glissez-déposez ou <span class="text-primary-600 dark:text-primary-400">parcourez</span></p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Max 10 Mo par fichier</p>
                                <input type="file" name="attachments[]" multiple x-ref="fileInput" class="hidden" @change="files = Array.from($event.target.files)">
                            </div>
                            <template x-if="files.length">
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <template x-for="(file, i) in files" :key="i">
                                        <div class="flex items-center gap-2 px-3 py-1 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 rounded-lg text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            <span x-text="file.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="btn btn-primary">Envoyer la réponse</button>
                            <button type="button" class="btn btn-secondary">Fermer le ticket</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Sidebar Info -->
        <div class="space-y-4">

            <!-- Ticket Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Informations</h3>
                </div>
                <div class="card-body space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Statut</span>
                        <span class="badge badge-warning">Ouvert</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Priorité</span>
                        <span class="badge badge-danger">Haute</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Catégorie</span>
                        <span class="text-gray-900 dark:text-white font-medium">Support Technique</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Service lié</span>
                        <a href="#" class="text-primary-600 dark:text-primary-400 font-medium hover:underline">Hébergement #1</a>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Ouvert le</span>
                        <span class="text-gray-900 dark:text-white">07/08/2026 14:00</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Dernière réponse</span>
                        <span class="text-gray-900 dark:text-white">Il y a 30 min</span>
                    </div>
                </div>
            </div>

            <!-- SLA Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">SLA</h3>
                </div>
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-success-100 dark:bg-success-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Première réponse</p>
                            <p class="text-xs text-success-600 dark:text-success-400">✓ Dans les délais (58 min / 4h)</p>
                        </div>
                    </div>
                    <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-2 bg-success-500 rounded-full" style="width: 25%"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Résolution : 22h restantes</p>
                </div>
            </div>

            <!-- Quick Replies -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Réponses Rapides</h3>
                </div>
                <div class="card-body space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Merci pour votre réponse rapide !', 'Problème résolu, merci.', 'J\'ai besoin de plus d\'informations.']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quick): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button class="w-full text-left text-sm px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">
                            <?php echo e($quick); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/client/tickets/show.blade.php ENDPATH**/ ?>