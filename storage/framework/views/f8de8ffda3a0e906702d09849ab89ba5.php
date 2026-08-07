<?php $__env->startSection('title', 'Paramètres'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Paramètres</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Configurez tous les paramètres de votre plateforme</p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div x-data="{ tab: '<?php echo e(request('tab', 'general')); ?>' }">

        <!-- Tab Nav -->
        <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
            <nav class="flex gap-0 -mb-px min-w-max">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                    ['general',   'Général',     'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['company',   'Entreprise',  'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['billing',   'Facturation', 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    ['email',     'Emails',      'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['security',  'Sécurité',    'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['payment',   'Paiements',   'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $iconPath]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button @click="tab = '<?php echo e($key); ?>'"
                    :class="tab === '<?php echo e($key); ?>'
                        ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                        : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                    class="flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($iconPath); ?>"/></svg>
                    <?php echo e($label); ?>

                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>
        </div>

        
        
        
        <div x-show="tab === 'general'" class="mt-6">
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <input type="hidden" name="group" value="general">

                <div class="card">
                    <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Informations Générales</h3></div>
                    <div class="card-body space-y-5">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Nom du Site</label>
                                <input type="text" name="site_name" value="<?php echo e($settings['site_name'] ?? 'HostClient'); ?>" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">URL du Site</label>
                                <input type="url" name="site_url" value="<?php echo e($settings['site_url'] ?? config('app.url')); ?>" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Langue par défaut</label>
                                <select name="site_locale" class="form-input">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['fr' => '🇫🇷 Français', 'en' => '🇬🇧 English', 'de' => '🇩🇪 Deutsch', 'es' => '🇪🇸 Español']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php echo e(($settings['site_locale'] ?? 'fr') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Devise par défaut</label>
                                <select name="default_currency" class="form-input">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['EUR' => '€ EUR — Euro', 'USD' => '$ USD — Dollar', 'GBP' => '£ GBP — Livre Sterling', 'CAD' => '$ CAD — Dollar Canadien']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php echo e(($settings['default_currency'] ?? 'EUR') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Fuseau Horaire</label>
                                <select name="timezone" class="form-input">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Europe/Paris' => 'Europe/Paris', 'Europe/London' => 'Europe/London', 'Europe/Brussels' => 'Europe/Brussels', 'UTC' => 'UTC', 'America/New_York' => 'America/New_York']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php echo e(($settings['timezone'] ?? 'Europe/Paris') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Format de date</label>
                                <select name="date_format" class="form-input">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['d/m/Y' => 'DD/MM/YYYY', 'm/d/Y' => 'MM/DD/YYYY', 'Y-m-d' => 'YYYY-MM-DD']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php echo e(($settings['date_format'] ?? 'd/m/Y') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Description du Site</label>
                            <textarea name="site_description" rows="3" class="form-input"><?php echo e($settings['site_description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Options</h3></div>
                    <div class="card-body space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                            ['registrations_open',      'Inscriptions ouvertes',          "Permettre aux nouveaux utilisateurs de s'inscrire", true],
                            ['maintenance_mode',         'Mode maintenance',                'Activer le mode maintenance (déconnecte les clients)', false],
                            ['manual_order_validation',  'Validation manuelle commandes',  'Les nouvelles commandes nécessitent une validation admin', false],
                            ['demo_mode',                'Mode démo',                      'Afficher le bandeau démo sur le site', false],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $desc, $default]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $val = isset($settings[$key]) ? (bool)$settings[$key] : $default; ?>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm"><?php echo e($label); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($desc); ?></p>
                            </div>
                            <div x-data="{ on: <?php echo e($val ? 'true' : 'false'); ?> }">
                                <input type="hidden" name="<?php echo e($key); ?>" :value="on ? '1' : '0'">
                                <div @click="on = !on" class="relative cursor-pointer">
                                    <div :class="on ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-11 h-6 rounded-full transition-colors"></div>
                                    <div :class="on ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
        </div>

        
        
        
        <div x-show="tab === 'company'" class="mt-6">
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <input type="hidden" name="group" value="company">

                <div class="card">
                    <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Informations de l'Entreprise</h3></div>
                    <div class="card-body space-y-5">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Nom de l'entreprise</label>
                                <input type="text" name="company_name" value="<?php echo e($settings['company_name'] ?? ''); ?>" class="form-input" placeholder="Ma Société SAS">
                            </div>
                            <div>
                                <label class="form-label">Numéro SIRET / TVA</label>
                                <input type="text" name="company_vat" value="<?php echo e($settings['company_vat'] ?? ''); ?>" class="form-input" placeholder="FR12345678901">
                            </div>
                            <div>
                                <label class="form-label">Email de contact</label>
                                <input type="email" name="company_email" value="<?php echo e($settings['company_email'] ?? ''); ?>" class="form-input" placeholder="contact@masociete.fr">
                            </div>
                            <div>
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="company_phone" value="<?php echo e($settings['company_phone'] ?? ''); ?>" class="form-input" placeholder="+33 1 23 45 67 89">
                            </div>
                            <div>
                                <label class="form-label">Site web</label>
                                <input type="url" name="company_website" value="<?php echo e($settings['company_website'] ?? ''); ?>" class="form-input" placeholder="https://masociete.fr">
                            </div>
                            <div>
                                <label class="form-label">Capital social</label>
                                <input type="text" name="company_capital" value="<?php echo e($settings['company_capital'] ?? ''); ?>" class="form-input" placeholder="10 000 €">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Adresse</label>
                            <input type="text" name="company_address" value="<?php echo e($settings['company_address'] ?? ''); ?>" class="form-input" placeholder="1 rue de la Paix">
                        </div>
                        <div class="grid sm:grid-cols-3 gap-5">
                            <div>
                                <label class="form-label">Code postal</label>
                                <input type="text" name="company_zip" value="<?php echo e($settings['company_zip'] ?? ''); ?>" class="form-input" placeholder="75001">
                            </div>
                            <div>
                                <label class="form-label">Ville</label>
                                <input type="text" name="company_city" value="<?php echo e($settings['company_city'] ?? ''); ?>" class="form-input" placeholder="Paris">
                            </div>
                            <div>
                                <label class="form-label">Pays</label>
                                <select name="company_country" class="form-input">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['FR' => 'France', 'BE' => 'Belgique', 'CH' => 'Suisse', 'CA' => 'Canada', 'GB' => 'Royaume-Uni', 'DE' => 'Allemagne']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php echo e(($settings['company_country'] ?? 'FR') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Mentions légales / Pied de facture</label>
                            <textarea name="company_legal_notice" rows="3" class="form-input" placeholder="Mentions légales à afficher sur les factures..."><?php echo e($settings['company_legal_notice'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        
        
        
        <div x-show="tab === 'billing'" class="mt-6">
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <input type="hidden" name="group" value="billing">

                <div class="card">
                    <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Paramètres de Facturation</h3></div>
                    <div class="card-body space-y-5">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Préfixe Facture</label>
                                <input type="text" name="invoice_prefix" value="<?php echo e($settings['invoice_prefix'] ?? 'INV-'); ?>" class="form-input" placeholder="INV-">
                            </div>
                            <div>
                                <label class="form-label">Prochain numéro de facture</label>
                                <input type="number" name="invoice_next_number" value="<?php echo e($settings['invoice_next_number'] ?? '1001'); ?>" class="form-input" min="1">
                            </div>
                            <div>
                                <label class="form-label">Jours avant premier rappel</label>
                                <input type="number" name="billing_reminder_days" value="<?php echo e($settings['billing_reminder_days'] ?? '7'); ?>" class="form-input" min="1">
                                <p class="text-xs text-gray-500 mt-1">Envoyer un rappel X jours avant l'échéance</p>
                            </div>
                            <div>
                                <label class="form-label">Jours avant suspension (après échéance)</label>
                                <input type="number" name="billing_suspend_days" value="<?php echo e($settings['billing_suspend_days'] ?? '3'); ?>" class="form-input" min="0">
                            </div>
                            <div>
                                <label class="form-label">Jours avant résiliation</label>
                                <input type="number" name="billing_terminate_days" value="<?php echo e($settings['billing_terminate_days'] ?? '14'); ?>" class="form-input" min="1">
                            </div>
                            <div>
                                <label class="form-label">Taux de TVA par défaut (%)</label>
                                <input type="number" name="default_tax_rate" value="<?php echo e($settings['default_tax_rate'] ?? '20'); ?>" class="form-input" step="0.1" min="0" max="100">
                            </div>
                            <div>
                                <label class="form-label">Fermeture auto tickets (jours)</label>
                                <input type="number" name="ticket_auto_close_days" value="<?php echo e($settings['ticket_auto_close_days'] ?? '7'); ?>" class="form-input" min="1">
                            </div>
                            <div>
                                <label class="form-label">Génération factures (jours avant)</label>
                                <input type="number" name="invoice_generate_days_before" value="<?php echo e($settings['invoice_generate_days_before'] ?? '14'); ?>" class="form-input" min="1">
                                <p class="text-xs text-gray-500 mt-1">Générer les factures X jours avant le renouvellement</p>
                            </div>
                        </div>

                        <div class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                                ['billing_tax_enabled',          'Activer la TVA',                    "Appliquer la TVA sur les factures", true],
                                ['billing_auto_invoice',         'Facturation automatique',            "Générer automatiquement les factures de renouvellement", true],
                                ['billing_send_invoice_email',   'Envoyer les factures par email',    "Envoyer automatiquement les factures aux clients", true],
                                ['billing_pro_rata',             'Facturation au prorata',            "Facturer au prorata lors des upgrades de service", false],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $desc, $default]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $val = isset($settings[$key]) ? (bool)$settings[$key] : $default; ?>
                            <div class="flex items-center justify-between py-2">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm"><?php echo e($label); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($desc); ?></p>
                                </div>
                                <div x-data="{ on: <?php echo e($val ? 'true' : 'false'); ?> }">
                                    <input type="hidden" name="<?php echo e($key); ?>" :value="on ? '1' : '0'">
                                    <div @click="on = !on" class="relative cursor-pointer">
                                        <div :class="on ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-11 h-6 rounded-full transition-colors"></div>
                                        <div :class="on ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        
        
        
        <div x-show="tab === 'email'" class="mt-6 space-y-6">
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <input type="hidden" name="group" value="email">

                <div class="card">
                    <div class="card-header">
                        <h3 class="font-bold text-gray-900 dark:text-white">Configuration SMTP</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Paramètres sauvegardés en base de données, appliqués immédiatement sans redémarrage</p>
                    </div>
                    <div class="card-body space-y-5">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Driver Mail</label>
                                <select name="mail_mailer" class="form-input">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['smtp' => 'SMTP', 'mailgun' => 'Mailgun', 'postmark' => 'Postmark', 'ses' => 'Amazon SES', 'sendmail' => 'Sendmail', 'log' => 'Log (debug)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>" <?php echo e(($settings['mail_mailer'] ?? 'smtp') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Hôte SMTP</label>
                                <input type="text" name="mail_host" value="<?php echo e($settings['mail_host'] ?? ''); ?>" class="form-input" placeholder="smtp.example.com">
                            </div>
                            <div>
                                <label class="form-label">Port SMTP</label>
                                <input type="number" name="mail_port" value="<?php echo e($settings['mail_port'] ?? '587'); ?>" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Chiffrement</label>
                                <select name="mail_encryption" class="form-input">
                                    <option value="tls"  <?php echo e(($settings['mail_encryption'] ?? 'tls') === 'tls'  ? 'selected' : ''); ?>>TLS (port 587)</option>
                                    <option value="ssl"  <?php echo e(($settings['mail_encryption'] ?? '') === 'ssl'  ? 'selected' : ''); ?>>SSL (port 465)</option>
                                    <option value=""     <?php echo e(($settings['mail_encryption'] ?? '') === ''     ? 'selected' : ''); ?>>Aucun</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Utilisateur SMTP</label>
                                <input type="text" name="mail_username" value="<?php echo e($settings['mail_username'] ?? ''); ?>" class="form-input" placeholder="user@smtp.example.com">
                            </div>
                            <div>
                                <label class="form-label">Mot de passe SMTP</label>
                                <input type="password" name="mail_password" value="<?php echo e($settings['mail_password'] ?? ''); ?>" class="form-input" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="form-label">Nom expéditeur</label>
                                <input type="text" name="mail_from_name" value="<?php echo e($settings['mail_from_name'] ?? 'HostClient'); ?>" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Email expéditeur</label>
                                <input type="email" name="mail_from_address" value="<?php echo e($settings['mail_from_address'] ?? ''); ?>" class="form-input" placeholder="noreply@votredomaine.com">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer la configuration</button>
                    </div>
                </div>
            </form>

            <!-- Test email -->
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Tester la configuration</h3></div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.settings.test-email')); ?>" class="flex flex-col sm:flex-row gap-3">
                        <?php echo csrf_field(); ?>
                        <input type="email" name="test_email" class="form-input sm:max-w-xs" placeholder="destinataire@example.com" required>
                        <button type="submit" class="btn btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Envoyer un email de test
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Un email de test sera envoyé avec la configuration ci-dessus. Sauvegardez d'abord vos paramètres.</p>
                </div>
            </div>
        </div>

        
        
        
        <div x-show="tab === 'security'" class="mt-6">
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <input type="hidden" name="group" value="security">

                <div class="card">
                    <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Options de Sécurité</h3></div>
                    <div class="card-body space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                            ['security_force_2fa_admin',     'Forcer 2FA pour les admins',       "Tous les administrateurs doivent activer l'authentification à deux facteurs", false],
                            ['security_recaptcha_register',  'reCAPTCHA sur inscription',         "Protéger le formulaire d'inscription avec reCAPTCHA", true],
                            ['security_recaptcha_login',     'reCAPTCHA sur connexion',           "Activer reCAPTCHA après 3 tentatives de connexion échouées", true],
                            ['security_audit_log',           "Journaux d'audit",                  "Enregistrer toutes les actions des administrateurs", true],
                            ['security_rate_limit_api',      'Rate limiting API',                 "Limiter le nombre de requêtes API par IP", true],
                            ['security_ip_whitelist_admin',  'Restreindre accès admin par IP',    "Autoriser uniquement certaines IP pour le panel admin", false],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $desc, $default]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $val = isset($settings[$key]) ? (bool)$settings[$key] : $default; ?>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm"><?php echo e($label); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($desc); ?></p>
                            </div>
                            <div x-data="{ on: <?php echo e($val ? 'true' : 'false'); ?> }">
                                <input type="hidden" name="<?php echo e($key); ?>" :value="on ? '1' : '0'">
                                <div @click="on = !on" class="relative cursor-pointer">
                                    <div :class="on ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-11 h-6 rounded-full transition-colors"></div>
                                    <div :class="on ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">reCAPTCHA (Google)</h3></div>
                    <div class="card-body">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Clé Site (Site Key)</label>
                                <input type="text" name="recaptcha_site_key" value="<?php echo e($settings['recaptcha_site_key'] ?? ''); ?>" class="form-input" placeholder="6Le...">
                            </div>
                            <div>
                                <label class="form-label">Clé Secrète (Secret Key)</label>
                                <input type="password" name="recaptcha_secret_key" value="<?php echo e($settings['recaptcha_secret_key'] ?? ''); ?>" class="form-input" placeholder="6Le...">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Obtenez vos clés sur <a href="https://www.google.com/recaptcha" target="_blank" class="text-primary-600 hover:underline">google.com/recaptcha</a></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Paramètres Session & API</h3></div>
                    <div class="card-body">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Durée de session (minutes)</label>
                                <input type="number" name="session_lifetime" value="<?php echo e($settings['session_lifetime'] ?? '120'); ?>" class="form-input" min="5">
                            </div>
                            <div>
                                <label class="form-label">Rate limit API (requêtes/minute)</label>
                                <input type="number" name="api_rate_limit" value="<?php echo e($settings['api_rate_limit'] ?? '60'); ?>" class="form-input" min="1">
                            </div>
                            <div>
                                <label class="form-label">IP autorisées panel admin</label>
                                <textarea name="admin_ip_whitelist" rows="3" class="form-input font-mono text-sm" placeholder="1.2.3.4&#10;5.6.7.8"><?php echo e($settings['admin_ip_whitelist'] ?? ''); ?></textarea>
                                <p class="text-xs text-gray-500 mt-1">Une IP par ligne. Vide = toutes les IP autorisées.</p>
                            </div>
                            <div>
                                <label class="form-label">Tentatives max avant blocage</label>
                                <input type="number" name="login_max_attempts" value="<?php echo e($settings['login_max_attempts'] ?? '5'); ?>" class="form-input" min="1">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        
        
        
        <div x-show="tab === 'payment'" class="mt-6 space-y-4">
            <?php
            $gateways = [
                ['stripe',        'Stripe',            'Carte bancaire, Apple Pay, Google Pay',    ['stripe_public_key' => 'Clé Publique (pk_live_...)', 'stripe_secret_key' => 'Clé Secrète (sk_live_...)', 'stripe_webhook_secret' => 'Webhook Secret (whsec_...)']],
                ['paypal',        'PayPal',             'Paiement PayPal et carte via PayPal',      ['paypal_client_id' => 'Client ID', 'paypal_client_secret' => 'Client Secret']],
                ['mollie',        'Mollie',             'iDEAL, Bancontact, SEPA et plus',          ['mollie_api_key' => 'Clé API (live_...)']],
                ['coinbase',      'Coinbase Commerce',  'Paiement en cryptomonnaie',                ['coinbase_api_key' => 'Clé API', 'coinbase_webhook_secret' => 'Webhook Secret']],
                ['bank_transfer', 'Virement Bancaire',  'Virement SEPA manuel',                     ['bank_iban' => 'IBAN', 'bank_bic' => 'BIC / SWIFT', 'bank_holder' => 'Titulaire du compte']],
            ];
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $name, $desc, $fields]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $enabled = (bool)($settings[$key . '_enabled'] ?? false); ?>
            <div class="card" x-data="{ open: <?php echo e($enabled ? 'true' : 'false'); ?>, enabled: <?php echo e($enabled ? 'true' : 'false'); ?> }">
                <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="group" value="payment_<?php echo e($key); ?>">
                    <input type="hidden" name="<?php echo e($key); ?>_enabled" :value="enabled ? '1' : '0'">

                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center font-bold text-gray-700 dark:text-gray-300 text-sm">
                                    <?php echo e(strtoupper(substr($key, 0, 2))); ?>

                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white"><?php echo e($name); ?></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($desc); ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span x-show="enabled" class="text-xs text-green-600 dark:text-green-400 font-medium bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded-full">Actif</span>
                                <span x-show="!enabled" class="text-xs text-gray-500 font-medium bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Inactif</span>
                                <div @click="enabled = !enabled; open = enabled" class="relative cursor-pointer">
                                    <div :class="enabled ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-11 h-6 rounded-full transition-colors"></div>
                                    <div :class="enabled ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                                </div>
                                <button type="button" @click="open = !open" class="btn btn-secondary btn-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Configurer
                                </button>
                            </div>
                        </div>

                        <div x-show="open" x-transition class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fieldKey => $fieldLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div <?php if(str_contains($fieldKey, 'webhook') || str_contains($fieldKey, 'iban')): ?> class="sm:col-span-2" <?php endif; ?>>
                                    <label class="form-label text-xs"><?php echo e($fieldLabel); ?></label>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_contains(strtolower($fieldKey), 'secret') || str_contains(strtolower($fieldKey), 'key')): ?>
                                        <input type="password" name="<?php echo e($fieldKey); ?>" value="<?php echo e($settings[$fieldKey] ?? ''); ?>" class="form-input text-sm font-mono">
                                    <?php else: ?>
                                        <input type="text" name="<?php echo e($fieldKey); ?>" value="<?php echo e($settings[$fieldKey] ?? ''); ?>" class="form-input text-sm">
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>