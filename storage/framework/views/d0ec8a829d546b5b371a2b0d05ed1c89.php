<?php $__env->startSection('title', 'Thèmes'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Thèmes</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Personnalisez l'apparence de votre plateforme</p>
    </div>

    <!-- Installed Themes -->
    <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Thèmes Installés</h3>
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['Default Modern', 'Thème par défaut moderne avec mode clair/sombre', true,  'HostClient', 'v1.0.0'],
                ['Midnight Dark',  'Thème sombre élégant avec accents violets',       false, 'Community',  'v1.2.0'],
                ['Ocean Breeze',   'Thème clair aux couleurs de l\'océan',            false, 'Community',  'v0.9.0'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name, $desc, $active, $author, $version]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card <?php echo e($active ? 'border-2 border-primary-500' : ''); ?> hover:shadow-md transition-shadow">
                <!-- Preview -->
                <div class="h-40 bg-gradient-to-br <?php echo e($active ? 'from-primary-500 to-secondary-600' : 'from-gray-700 to-gray-900'); ?> relative overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-white text-center">
                            <div class="w-10 h-10 bg-white/20 rounded-xl mx-auto mb-2 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            </div>
                            <p class="text-sm font-semibold"><?php echo e($name); ?></p>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($active): ?>
                    <div class="absolute top-2 right-2">
                        <span class="bg-success-500 text-white text-xs font-bold px-2 py-1 rounded-full">Actif</span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="card-body">
                    <h4 class="font-bold text-gray-900 dark:text-white"><?php echo e($name); ?></h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e($desc); ?></p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Par <?php echo e($author); ?> · <?php echo e($version); ?></p>
                    <div class="flex gap-2 mt-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($active): ?>
                            <button class="btn btn-secondary btn-sm flex-1">Personnaliser</button>
                        <?php else: ?>
                            <button class="btn btn-primary btn-sm flex-1">Activer</button>
                            <button class="btn btn-secondary btn-sm">Aperçu</button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Upload card -->
            <div class="card border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-primary-400 dark:hover:border-primary-500 transition-colors cursor-pointer" onclick="document.getElementById('theme-upload').click()">
                <div class="card-body flex flex-col items-center justify-center h-full min-h-48 text-center">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="font-semibold text-gray-700 dark:text-gray-300 text-sm">Installer un thème</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Glissez un fichier .zip ou cliquez pour parcourir</p>
                    <input type="file" id="theme-upload" class="hidden" accept=".zip">
                </div>
            </div>
        </div>
    </div>

    <!-- Customizer -->
    <div class="card">
        <div class="card-header">
            <h3 class="font-bold text-gray-900 dark:text-white">Personnalisation — Default Modern</h3>
        </div>
        <div class="card-body">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Couleur principale</label>
                        <div class="flex items-center gap-3">
                            <input type="color" value="#0ea5e9" class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer">
                            <input type="text" value="#0ea5e9" class="form-input font-mono text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Couleur secondaire</label>
                        <div class="flex items-center gap-3">
                            <input type="color" value="#d946ef" class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer">
                            <input type="text" value="#d946ef" class="form-input font-mono text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Police</label>
                        <select class="form-input">
                            <option selected>Inter</option>
                            <option>Poppins</option>
                            <option>Roboto</option>
                            <option>Nunito</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Logo (URL ou fichier)</label>
                        <input type="text" class="form-input" placeholder="https://exemple.com/logo.png">
                    </div>
                    <div>
                        <label class="form-label">Logo sombre (optionnel)</label>
                        <input type="text" class="form-input" placeholder="https://exemple.com/logo-dark.png">
                    </div>
                </div>
                <div>
                    <label class="form-label">CSS personnalisé</label>
                    <textarea rows="12" class="form-input font-mono text-xs" placeholder="/* Votre CSS personnalisé */&#10;&#10;:root {&#10;  --brand-color: #0ea5e9;&#10;}"></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button class="btn btn-primary">Enregistrer les modifications</button>
                <button class="btn btn-secondary">Aperçu</button>
                <button class="btn btn-ghost text-danger-600">Réinitialiser</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/themes/index.blade.php ENDPATH**/ ?>