<?php $__env->startSection('title', 'Plugins'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Plugins</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Étendez les fonctionnalités de votre plateforme</p>
        </div>
        <div class="flex gap-2">
            <label class="btn btn-secondary cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Uploader un plugin
                <input type="file" class="hidden" accept=".zip">
            </label>
        </div>
    </div>

    <!-- Installed Plugins -->
    <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Plugins Installés</h3>
        <div class="space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['Stripe Gateway',       'Passerelle de paiement Stripe avec webhooks',          '2.1.0', true,  'payment',  'HostClient Team'],
                ['PayPal Gateway',       'Passerelle de paiement PayPal Standard & Pro',         '1.8.0', true,  'payment',  'HostClient Team'],
                ['Pterodactyl Module',   'Provisionnement automatique serveurs de jeux',         '3.0.1', true,  'module',   'HostClient Team'],
                ['Proxmox Module',       'Gestion VPS via API Proxmox',                         '2.5.0', false, 'module',   'HostClient Team'],
                ['Domain WHMCS Bridge',  'Synchronisation domaines avec WHMCS',                 '1.2.0', false, 'bridge',   'Community'],
                ['Affiliate System',     'Système d\'affiliation avec commissions',              '1.0.3', true,  'marketing','Community'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name, $desc, $version, $enabled, $type, $author]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                                <?php echo e($type === 'payment' ? '💳' : ($type === 'module' ? '🖥️' : ($type === 'marketing' ? '📢' : '🔗'))); ?>

                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-bold text-gray-900 dark:text-white"><?php echo e($name); ?></h4>
                                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded-full font-mono">v<?php echo e($version); ?></span>
                                    <span class="text-xs bg-<?php echo e($type === 'payment' ? 'success' : ($type === 'module' ? 'primary' : ($type === 'marketing' ? 'warning' : 'secondary'))); ?>-100 dark:bg-<?php echo e($type === 'payment' ? 'success' : ($type === 'module' ? 'primary' : ($type === 'marketing' ? 'warning' : 'secondary'))); ?>-900/30 text-<?php echo e($type === 'payment' ? 'success' : ($type === 'module' ? 'primary' : ($type === 'marketing' ? 'warning' : 'secondary'))); ?>-700 dark:text-<?php echo e($type === 'payment' ? 'success' : ($type === 'module' ? 'primary' : ($type === 'marketing' ? 'warning' : 'secondary'))); ?>-300 px-2 py-0.5 rounded-full"><?php echo e(ucfirst($type)); ?></span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($desc); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-0.5">Par <?php echo e($author); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <div x-data="{ on: <?php echo e($enabled ? 'true' : 'false'); ?> }" @click="on = !on" class="relative cursor-pointer">
                                <div :class="on ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-11 h-6 rounded-full transition-colors"></div>
                                <div :class="on ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                            </div>
                            <button class="btn btn-secondary btn-sm">Configurer</button>
                            <button class="btn btn-ghost btn-sm text-danger-600" title="Désinstaller">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Available Plugins -->
    <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Plugins Disponibles</h3>
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['Mollie Gateway',      'Paiements EU via Mollie (iDEAL, Bancontact…)', '💳', 'Gratuit'],
                ['Coinbase Commerce',   'Accepter les cryptomonnaies',                  '₿',  'Gratuit'],
                ['cPanel Module',       'Provisionnement hébergement cPanel',           '🖥️', 'Gratuit'],
                ['DirectAdmin Module',  'Provisionnement hébergement DirectAdmin',      '🖥️', 'Gratuit'],
                ['SolusVM Module',      'Gestion VPS via SolusVM',                     '🖥️', 'Gratuit'],
                ['Email Marketing',     'Intégration Mailchimp / Brevo',               '📧', '9,99 €'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name, $desc, $icon, $price]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card hover:shadow-md transition-shadow">
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-3xl"><?php echo e($icon); ?></span>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm"><?php echo e($name); ?></h4>
                            <span class="text-xs font-semibold <?php echo e($price === 'Gratuit' ? 'text-success-600 dark:text-success-400' : 'text-primary-600 dark:text-primary-400'); ?>"><?php echo e($price); ?></span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-3"><?php echo e($desc); ?></p>
                    <button class="btn btn-primary btn-sm w-full">Installer</button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/plugins/index.blade.php ENDPATH**/ ?>