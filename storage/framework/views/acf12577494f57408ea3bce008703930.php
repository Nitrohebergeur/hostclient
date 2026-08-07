<?php $__env->startSection('title', 'Ticket #1234'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="/admin/tickets" class="hover:text-primary-600 dark:hover:text-primary-400">Tickets</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white">#1234 — Problème de connexion FTP</span>
    </nav>

    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Thread -->
        <div class="lg:col-span-2 space-y-4">

            <!-- Messages -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['client', 'Jean Dupont', 'jean.dupont@exemple.com', 'Il y a 2 heures', "Bonjour,\n\nJe n'arrive pas à me connecter en FTP. Erreur : connexion refusée.\n\nMon IP est 82.65.124.200.\n\nMerci", false, []],
                ['staff', 'Alex Martin (Support)', 'support@hostclient.io', 'Il y a 1 heure', "Bonjour Jean,\n\nJ'ai déblocqué votre IP dans notre pare-feu. Pouvez-vous réessayer ?\n\nCordialement,\nAlex", false, []],
                ['staff', 'Note interne', '', 'Il y a 45 min', "Vérifier le pare-feu serveur fr-par-01. IP : 82.65.124.200 ajoutée à la whitelist.", true, []],
                ['client', 'Jean Dupont', 'jean.dupont@exemple.com', 'Il y a 30 min', "Super, ça fonctionne maintenant ! Merci beaucoup !", false, ['capture.png']],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$type, $name, $email, $time, $body, $private, $attachments]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card <?php echo e($private ? 'border-l-4 border-warning-400 bg-warning-50/30 dark:bg-warning-900/10' : ($type === 'staff' ? 'border-l-4 border-primary-500' : '')); ?>">
                <div class="card-body">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($name)); ?>&background=<?php echo e($type === 'staff' ? ($private ? 'f59e0b' : '0ea5e9') : '6366f1'); ?>&color=fff&size=40" class="w-10 h-10 rounded-full flex-shrink-0">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm"><?php echo e($name); ?></p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($type === 'staff' && !$private): ?> <span class="badge badge-primary text-xs">Staff</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($private): ?> <span class="badge badge-warning text-xs">🔒 Note interne</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($email): ?> <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($email); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($time); ?></span>
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                        <?php echo nl2br(e($body)); ?>

                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($attachments)): ?>
                    <div class="mt-3 flex gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="#" class="flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            <?php echo e($f); ?>

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
                    <div x-data="{ mode: 'reply' }" class="flex items-center gap-3">
                        <button @click="mode = 'reply'" :class="mode === 'reply' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">Répondre au client</button>
                        <button @click="mode = 'note'" :class="mode === 'note' ? 'bg-warning-100 dark:bg-warning-900/30 text-warning-700 dark:text-warning-300' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">🔒 Note interne</button>
                    </div>
                </div>
                <div class="card-body">
                    <form class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <!-- Quick Replies -->
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open" class="btn btn-secondary btn-sm text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Réponses rapides
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 mt-1 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                                    'Bonjour, merci pour votre message. Je vous reviens dès que possible.',
                                    'Votre problème a été résolu. N\'hésitez pas à nous contacter si besoin.',
                                    'Pourriez-vous nous fournir plus d\'informations sur le problème ?',
                                    'Votre service a été redémarré. Vérifiez si le problème persiste.',
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quick): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="block w-full text-left px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 leading-relaxed"><?php echo e($quick); ?></button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <textarea name="message" rows="5" class="form-input" placeholder="Rédigez votre réponse…" required></textarea>

                        <!-- Attachments -->
                        <div x-data="{ files: [] }">
                            <div @click="$refs.fi.click()" @dragover.prevent @drop.prevent="files = [...files, ...$event.dataTransfer.files]" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-3 text-center cursor-pointer hover:border-primary-400 dark:hover:border-primary-500 transition-colors">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Glissez des fichiers ou <span class="text-primary-600 dark:text-primary-400">parcourez</span> (max 10 Mo)</p>
                                <input type="file" x-ref="fi" multiple class="hidden" @change="files = [...files, ...$event.target.files]">
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 items-center justify-between">
                            <div class="flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    Envoyer
                                </button>
                                <button type="button" class="btn btn-secondary">Envoyer & Fermer</button>
                            </div>
                            <select class="form-input w-40 text-sm">
                                <option>Laisser ouvert</option>
                                <option>Marquer résolu</option>
                                <option>Fermer le ticket</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="space-y-4">
            <!-- Ticket Info -->
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Informations</h3></div>
                <div class="card-body space-y-4 text-sm">
                    <div>
                        <label class="form-label text-xs">Statut</label>
                        <select class="form-input text-sm">
                            <option>Ouvert</option>
                            <option>En cours</option>
                            <option>En attente</option>
                            <option>Fermé</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-xs">Priorité</label>
                        <select class="form-input text-sm">
                            <option>Basse</option>
                            <option>Normale</option>
                            <option selected>Haute</option>
                            <option>Urgente</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-xs">Assigné à</label>
                        <select class="form-input text-sm">
                            <option>Non assigné</option>
                            <option selected>Alex Martin</option>
                            <option>Sarah Dupuis</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-xs">Catégorie</label>
                        <select class="form-input text-sm">
                            <option selected>Support Technique</option>
                            <option>Facturation</option>
                            <option>Ventes</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-xs">Service lié</label>
                        <select class="form-input text-sm">
                            <option>Aucun</option>
                            <option selected>Hébergement Premium (monsite.com)</option>
                            <option>VPS Cloud Standard</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-full btn-sm">Enregistrer</button>
                </div>
            </div>

            <!-- Client Info -->
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Client</h3></div>
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-3">
                        <img src="https://ui-avatars.com/api/?name=Jean+Dupont&background=6366f1&color=fff&size=40" class="w-10 h-10 rounded-full">
                        <div>
                            <a href="/admin/users/1" class="font-semibold text-primary-600 dark:text-primary-400 text-sm hover:underline">Jean Dupont</a>
                            <p class="text-xs text-gray-500 dark:text-gray-400">jean.dupont@exemple.com</p>
                        </div>
                    </div>
                    <div class="space-y-1.5 text-xs text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between"><span>Services actifs</span><span class="font-semibold text-gray-900 dark:text-white">12</span></div>
                        <div class="flex justify-between"><span>Factures impayées</span><span class="font-semibold text-warning-600">3</span></div>
                        <div class="flex justify-between"><span>Tickets totaux</span><span class="font-semibold text-gray-900 dark:text-white">18</span></div>
                        <div class="flex justify-between"><span>Membre depuis</span><span class="font-semibold text-gray-900 dark:text-white">15 jan. 2024</span></div>
                    </div>
                </div>
            </div>

            <!-- SLA -->
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">SLA</h3></div>
                <div class="card-body space-y-3">
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Première réponse (4h)</span>
                            <span class="text-success-600 dark:text-success-400">✓ 58 min</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full"><div class="h-2 bg-success-500 rounded-full" style="width:25%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Résolution (24h)</span>
                            <span class="text-warning-600 dark:text-warning-400">⏳ 2h restantes</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full"><div class="h-2 bg-warning-500 rounded-full" style="width:92%"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/tickets/show.blade.php ENDPATH**/ ?>