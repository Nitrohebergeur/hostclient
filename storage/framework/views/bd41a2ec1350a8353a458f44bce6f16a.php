<?php $__env->startSection('title', 'Journaux Système'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Journaux Système</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Logs d'activité et d'audit en temps réel</p>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exporter
            </button>
            <button class="btn btn-danger" onclick="confirm('Vider les logs ?')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Vider les logs
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ tab: 'activity' }">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['activity' => 'Activité', 'auth' => 'Authentification', 'api' => 'API', 'system' => 'Système', 'errors' => 'Erreurs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button @click="tab = '<?php echo e($k); ?>'" :class="tab === '<?php echo e($k); ?>' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="px-5 py-3 text-sm font-medium border-b-2 transition-colors"><?php echo e($l); ?></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>
        </div>

        <!-- Activity Log -->
        <div x-show="tab === 'activity'" class="mt-4 space-y-4">
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" placeholder="Rechercher dans les logs…" class="form-input flex-1 text-sm">
                        <select class="form-input w-full sm:w-36 text-sm">
                            <option>Tous types</option>
                            <option>Connexion</option>
                            <option>Paiement</option>
                            <option>Service</option>
                            <option>Admin</option>
                        </select>
                        <input type="date" class="form-input w-full sm:w-40 text-sm">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <!-- Log Terminal -->
                    <div class="bg-gray-950 dark:bg-gray-900 rounded-xl font-mono text-xs overflow-x-auto">
                        <div class="flex items-center gap-2 px-4 py-3 bg-gray-900 dark:bg-gray-800 border-b border-gray-800 rounded-t-xl">
                            <span class="w-3 h-3 bg-danger-500 rounded-full"></span>
                            <span class="w-3 h-3 bg-warning-500 rounded-full"></span>
                            <span class="w-3 h-3 bg-success-500 rounded-full"></span>
                            <span class="ml-2 text-gray-400 text-xs">hostclient-logs — live</span>
                            <span class="ml-auto flex items-center gap-1.5 text-success-400 text-xs">
                                <span class="w-2 h-2 bg-success-500 rounded-full animate-pulse"></span>
                                Live
                            </span>
                        </div>
                        <div class="p-4 space-y-1 max-h-96 overflow-y-auto scrollbar-thin">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                                ['2026-08-07 14:52:01', 'INFO',  'AUTH',    'User #1042 (jean.dupont@exemple.com) logged in from 82.65.100.10'],
                                ['2026-08-07 14:50:33', 'INFO',  'PAYMENT', 'Payment received: 29.99 EUR via Stripe for Invoice #INV-2024-003'],
                                ['2026-08-07 14:49:10', 'INFO',  'SERVICE', 'Service #145 (Hébergement Premium) renewed for User #1042'],
                                ['2026-08-07 14:47:05', 'WARN',  'AUTH',    'Failed login attempt for user@unknown.com from 185.220.101.45'],
                                ['2026-08-07 14:45:22', 'INFO',  'TICKET',  'Ticket #1234 assigned to agent #5 (alex.martin@hostclient.io)'],
                                ['2026-08-07 14:43:15', 'INFO',  'ADMIN',   'Admin #1 modified product #8 (VPS Cloud Pro)'],
                                ['2026-08-07 14:40:08', 'ERROR', 'MODULE',  'Pterodactyl API error: Connection timeout (server: game-fr-01)'],
                                ['2026-08-07 14:38:55', 'INFO',  'AUTH',    'User #1087 (marie.martin@exemple.com) logged in from 90.50.20.5'],
                                ['2026-08-07 14:35:00', 'INFO',  'QUEUE',   'Job ProcessServiceRenewal completed for 12 services'],
                                ['2026-08-07 14:30:01', 'INFO',  'CRON',    'Scheduled task RunInvoiceReminders executed — 8 emails sent'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$time, $level, $cat, $msg]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex gap-3 items-start hover:bg-white/5 px-2 py-1 rounded">
                                <span class="text-gray-500 flex-shrink-0"><?php echo e($time); ?></span>
                                <span class="flex-shrink-0 font-bold w-12
                                    <?php echo e($level === 'ERROR' ? 'text-danger-400' : ($level === 'WARN' ? 'text-warning-400' : 'text-success-400')); ?>">
                                    <?php echo e($level); ?>

                                </span>
                                <span class="flex-shrink-0 text-primary-400 w-16"><?php echo e($cat); ?></span>
                                <span class="text-gray-300 break-all"><?php echo e($msg); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auth Log -->
        <div x-show="tab === 'auth'" class="mt-4">
            <div class="card">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead><tr><th>Date / Heure</th><th>Utilisateur</th><th>Résultat</th><th>IP</th><th>Localisation</th><th>User Agent</th></tr></thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                                ['2026-08-07 14:52:01','jean.dupont@exemple.com','success','82.65.100.10','Paris, FR','Chrome 121 — Windows'],
                                ['2026-08-07 14:47:05','user@unknown.com','failed','185.220.101.45','Tor Exit Node','curl/7.88.1'],
                                ['2026-08-07 14:38:55','marie.martin@exemple.com','success','90.50.20.5','Lyon, FR','Firefox 122 — macOS'],
                                ['2026-08-07 13:10:33','paul.robert@exemple.com','failed','212.58.224.1','London, UK','Safari — iPhone'],
                                ['2026-08-07 11:05:12','admin@hostclient.io','success','10.0.0.1','Local','Chrome 121 — Linux'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$time, $user, $result, $ip, $loc, $ua]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="font-mono text-xs text-gray-600 dark:text-gray-400"><?php echo e($time); ?></td>
                                <td class="text-sm text-gray-900 dark:text-white"><?php echo e($user); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($result === 'success'): ?> <span class="badge badge-success">Succès</span>
                                    <?php else: ?> <span class="badge badge-danger">Échec</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="font-mono text-xs text-gray-600 dark:text-gray-400"><?php echo e($ip); ?></td>
                                <td class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($loc); ?></td>
                                <td class="text-xs text-gray-500 dark:text-gray-500 max-w-32 truncate"><?php echo e($ua); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Errors Tab -->
        <div x-show="tab === 'errors'" class="mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                            ['2026-08-07 14:40:08','Pterodactyl API error: Connection timeout','MODULE','game-fr-01','high'],
                            ['2026-08-07 12:15:44','MySQL query timeout: 30s exceeded','DATABASE','fr-par-01','medium'],
                            ['2026-08-07 09:30:12','Stripe webhook signature mismatch','PAYMENT','—','low'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$time, $msg, $cat, $server, $severity]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex gap-4 p-4 <?php echo e($severity === 'high' ? 'bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800' : ($severity === 'medium' ? 'bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800' : 'bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-700')); ?> rounded-xl">
                            <span class="flex-shrink-0 mt-1 w-2.5 h-2.5 rounded-full <?php echo e($severity === 'high' ? 'bg-danger-500' : ($severity === 'medium' ? 'bg-warning-500' : 'bg-gray-400')); ?>"></span>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($msg); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e($time); ?> · <?php echo e($cat); ?> · <?php echo e($server); ?></p>
                            </div>
                            <button class="btn btn-ghost btn-sm text-xs">Détails</button>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/logs/index.blade.php ENDPATH**/ ?>