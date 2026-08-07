<?php $__env->startSection('title', 'Ouvrir un Ticket'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="/client/tickets" class="hover:text-primary-600 dark:hover:text-primary-400">Support</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white">Nouveau ticket</span>
    </nav>

    <!-- Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Ouvrir un ticket de support</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Notre équipe vous répondra dans les plus brefs délais</p>
        </div>
        <div class="card-body">
            <form action="/client/tickets" method="POST" enctype="multipart/form-data" class="space-y-5">
                <?php echo csrf_field(); ?>

                <!-- Subject -->
                <div>
                    <label class="form-label">Sujet <span class="text-danger-500">*</span></label>
                    <input type="text" name="subject" class="form-input" placeholder="Décrivez brièvement votre problème" required>
                </div>

                <!-- Category -->
                <div>
                    <label class="form-label">Catégorie <span class="text-danger-500">*</span></label>
                    <select name="category" class="form-input" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <option value="billing">💳 Facturation</option>
                        <option value="technical">🔧 Support Technique</option>
                        <option value="sales">💼 Ventes</option>
                        <option value="abuse">🚨 Signalement d'abus</option>
                        <option value="other">❓ Autre</option>
                    </select>
                </div>

                <!-- Related Service -->
                <div>
                    <label class="form-label">Service concerné (optionnel)</label>
                    <select name="service_id" class="form-input">
                        <option value="">Aucun service spécifique</option>
                        <option value="1">Hébergement Premium — monsite.com</option>
                        <option value="2">VPS Cloud Standard — 192.168.1.100</option>
                        <option value="3">Domaine monsite.com</option>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label class="form-label">Priorité <span class="text-danger-500">*</span></label>
                    <div class="grid grid-cols-3 gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                            ['low', '🟢 Basse', 'Problème non urgent'],
                            ['normal', '🟡 Normale', 'Impact modéré'],
                            ['high', '🔴 Haute', 'Service inaccessible'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="priority" value="<?php echo e($prio[0]); ?>" class="peer sr-only" <?php echo e($prio[0] === 'normal' ? 'checked' : ''); ?>>
                            <div class="border-2 border-gray-200 dark:border-gray-600 peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 rounded-xl p-3 text-center transition-all">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($prio[1]); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e($prio[2]); ?></p>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Message -->
                <div>
                    <label class="form-label">Message <span class="text-danger-500">*</span></label>
                    <textarea
                        name="message"
                        rows="6"
                        class="form-input"
                        placeholder="Décrivez votre problème en détail. Plus vous donnez d'informations, plus vite nous pourrons vous aider."
                        required
                    ></textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 30 caractères</p>
                </div>

                <!-- Attachments -->
                <div x-data="{ files: [] }">
                    <label class="form-label">Pièces jointes (optionnel)</label>
                    <div
                        class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-primary-400 dark:hover:border-primary-500 transition-colors cursor-pointer"
                        @click="$refs.fileInput.click()"
                        @dragover.prevent
                        @drop.prevent="files = Array.from($event.dataTransfer.files)"
                    >
                        <svg class="w-10 h-10 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Glissez-déposez vos fichiers ici ou <span class="text-primary-600 dark:text-primary-400 font-medium">parcourez</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">PNG, JPG, PDF, TXT — Max 10 Mo chacun</p>
                        <input type="file" name="attachments[]" multiple x-ref="fileInput" class="hidden" accept=".png,.jpg,.jpeg,.pdf,.txt,.log" @change="files = Array.from($event.target.files)">
                    </div>
                    <template x-if="files.length">
                        <div class="mt-3 space-y-2">
                            <template x-for="(file, i) in files" :key="i">
                                <div class="flex items-center justify-between px-3 py-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        <span x-text="file.name"></span>
                                    </div>
                                    <button type="button" @click="files.splice(i, 1)" class="text-gray-400 hover:text-danger-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Submit -->
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Envoyer le ticket
                    </button>
                    <a href="/client/tickets" class="btn btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/client/tickets/create.blade.php ENDPATH**/ ?>