<?php $__env->startSection('title', 'Sécurité'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Sécurité du Compte</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Gérez la sécurité et les accès de votre compte</p>
    </div>

    <!-- Security Score -->
    <div class="card bg-gradient-to-r from-primary-600 to-secondary-600 text-white border-0">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-primary-100 text-sm mb-1">Score de sécurité</p>
                    <p class="text-4xl font-bold mb-2">75/100</p>
                    <p class="text-primary-100 text-sm">Activez l'authentification 2FA pour améliorer votre score</p>
                </div>
                <div class="relative w-24 h-24">
                    <svg class="w-24 h-24 -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="white" stroke-width="3" stroke-dasharray="75 25" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">

        <!-- Change Password -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <h3 class="font-bold text-gray-900 dark:text-white">Changer le mot de passe</h3>
                </div>
            </div>
            <div class="card-body">
                <form action="/client/security/password" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div>
                        <label class="form-label">Mot de passe actuel</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="current_password" class="form-input pr-10" placeholder="••••••••" required>
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Nouveau mot de passe</label>
                        <div x-data="{ show: false, strength: 0, value: '' }" class="relative">
                            <input
                                :type="show ? 'text' : 'password'"
                                name="password"
                                class="form-input pr-10"
                                placeholder="••••••••"
                                required
                                x-model="value"
                                @input="
                                    strength = 0;
                                    if (value.length >= 8) strength++;
                                    if (/[A-Z]/.test(value)) strength++;
                                    if (/[0-9]/.test(value)) strength++;
                                    if (/[^A-Za-z0-9]/.test(value)) strength++;
                                "
                            >
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>

                            <!-- Strength bar -->
                            <div class="mt-2 flex gap-1" x-show="value.length > 0">
                                <div :class="strength >= 1 ? 'bg-danger-500' : 'bg-gray-200 dark:bg-gray-600'" class="h-1.5 flex-1 rounded-full transition-colors"></div>
                                <div :class="strength >= 2 ? 'bg-warning-500' : 'bg-gray-200 dark:bg-gray-600'" class="h-1.5 flex-1 rounded-full transition-colors"></div>
                                <div :class="strength >= 3 ? 'bg-primary-500' : 'bg-gray-200 dark:bg-gray-600'" class="h-1.5 flex-1 rounded-full transition-colors"></div>
                                <div :class="strength >= 4 ? 'bg-success-500' : 'bg-gray-200 dark:bg-gray-600'" class="h-1.5 flex-1 rounded-full transition-colors"></div>
                            </div>
                            <p class="text-xs mt-1" x-show="value.length > 0">
                                <span x-text="['', 'Très faible', 'Faible', 'Moyen', 'Fort'][strength]"
                                    :class="{
                                        'text-danger-600': strength === 1,
                                        'text-warning-600': strength === 2,
                                        'text-primary-600': strength === 3,
                                        'text-success-600': strength === 4
                                    }"></span>
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour le mot de passe</button>
                </form>
            </div>
        </div>

        <!-- 2FA -->
        <div class="card" x-data="{ enabled: false }">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <h3 class="font-bold text-gray-900 dark:text-white">Authentification à Deux Facteurs (2FA)</h3>
                </div>
            </div>
            <div class="card-body space-y-4">
                <!-- Status -->
                <div class="flex items-center justify-between p-4 bg-danger-50 dark:bg-danger-900/20 rounded-xl border border-danger-200 dark:border-danger-800">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <p class="font-medium text-danger-800 dark:text-danger-200">2FA Non activé</p>
                            <p class="text-sm text-danger-700 dark:text-danger-300">Votre compte est moins sécurisé</p>
                        </div>
                    </div>
                    <span class="badge badge-danger">Inactif</span>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    L'authentification à deux facteurs ajoute une couche de sécurité supplémentaire à votre compte. En plus de votre mot de passe, vous devrez saisir un code généré par votre application d'authentification.
                </p>

                <!-- Enable 2FA Button -->
                <button @click="enabled = !enabled" class="btn btn-primary w-full" x-show="!enabled">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Activer la 2FA
                </button>

                <!-- 2FA Setup Steps -->
                <div x-show="enabled" x-transition class="space-y-4">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Étapes de configuration :</p>

                    <div class="space-y-3">
                        <div class="flex gap-3 items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Téléchargez une application d'authentification (<strong>Google Authenticator</strong>, <strong>Authy</strong> ou <strong>Bitwarden</strong>)</p>
                        </div>
                        <div class="flex gap-3 items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                <p>Scannez ce QR code avec votre application :</p>
                                <div class="mt-2 p-3 bg-white dark:bg-gray-800 rounded-lg inline-block">
                                    <!-- Placeholder QR Code -->
                                    <div class="w-32 h-32 bg-gray-900 dark:bg-gray-100 rounded flex items-center justify-center">
                                        <p class="text-xs text-center text-gray-400 dark:text-gray-600 p-2">QR Code généré par le serveur</p>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Clé manuelle: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">JBSWY3DPEHPK3PXP</code></p>
                            </div>
                        </div>
                        <div class="flex gap-3 items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Entrez le code de vérification :</p>
                                <div class="flex gap-2">
                                    <input type="text" placeholder="000000" maxlength="6" class="form-input text-center text-2xl tracking-widest font-mono w-36">
                                    <button class="btn btn-primary">Vérifier</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button @click="enabled = false" class="btn btn-ghost w-full text-sm">Annuler</button>
                </div>
            </div>
        </div>

    </div>

    <!-- API Keys -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <h3 class="font-bold text-gray-900 dark:text-white">Clés API</h3>
                </div>
                <button class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Nouvelle clé
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                    ['Clé de Production', 'hc_live_xxxxxxxxxxxxxxxxxxxxxxxxxxx', '2024-01-15', 'Jamais'],
                    ['Clé de Développement', 'hc_test_yyy...', '2024-02-01', '2025-02-01'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-xl">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-medium text-gray-900 dark:text-white"><?php echo e($key[0]); ?></p>
                            <span class="badge <?php echo e($loop->first ? 'badge-success' : 'badge-primary'); ?> text-xs">
                                <?php echo e($loop->first ? 'Live' : 'Test'); ?>

                            </span>
                        </div>
                        <code class="text-sm text-gray-600 dark:text-gray-400 font-mono"><?php echo e($key[1]); ?></code>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            Créée le <?php echo e($key[2]); ?> · Expiration: <?php echo e($key[3]); ?>

                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-secondary btn-sm" onclick="copyToClipboard('<?php echo e($key[1]); ?>')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Copier
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Révoquer
                        </button>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
                    <h3 class="font-bold text-gray-900 dark:text-white">Sessions Actives</h3>
                </div>
                <button class="btn btn-danger btn-sm">Déconnecter toutes les sessions</button>
            </div>
        </div>
        <div class="card-body space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['Chrome 121 — Windows', 'Paris, France', '192.168.1.1', 'Maintenant', true],
                ['Firefox 122 — macOS', 'Lyon, France', '82.65.100.10', 'Il y a 2 heures', false],
                ['Safari — iPhone', 'Marseille, France', '90.50.20.5', 'Il y a 3 jours', false],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-4 p-3 rounded-xl <?php echo e($session[4] ? 'bg-primary-50 dark:bg-primary-900/20' : 'bg-gray-50 dark:bg-gray-700/30'); ?>">
                <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($session[0]); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($session[4]): ?>
                            <span class="badge badge-success text-xs">Cette session</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($session[1]); ?> · <?php echo e($session[2]); ?> · <?php echo e($session[3]); ?></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$session[4]): ?>
                    <button class="btn btn-danger btn-sm">Terminer</button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="card border-2 border-danger-200 dark:border-danger-800">
        <div class="card-header bg-danger-50 dark:bg-danger-900/20">
            <h3 class="font-bold text-danger-700 dark:text-danger-300">Zone Dangereuse</h3>
        </div>
        <div class="card-body">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Supprimer le compte</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Cette action est irréversible. Toutes vos données seront supprimées définitivement.</p>
                </div>
                <button
                    class="btn btn-danger flex-shrink-0"
                    onclick="confirm('Êtes-vous certain de vouloir supprimer votre compte ? Cette action est irréversible.') && document.getElementById('delete-account-form').submit()"
                >
                    Supprimer mon compte
                </button>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/client/security/index.blade.php ENDPATH**/ ?>