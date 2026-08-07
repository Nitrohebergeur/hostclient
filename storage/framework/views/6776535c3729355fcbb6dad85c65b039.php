<?php $__env->startSection('title', 'Mon Profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Mon Profil</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Gérez vos informations personnelles</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Left Column: Avatar + quick info -->
        <div class="space-y-4">
            <div class="card">
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div x-data="{ preview: null }" class="mb-4">
                        <div class="relative inline-block">
                            <img
                                :src="preview || 'https://ui-avatars.com/api/?name=Jean+Dupont&background=0ea5e9&color=fff&size=128'"
                                class="w-28 h-28 rounded-full mx-auto object-cover border-4 border-white dark:border-gray-700 shadow-lg"
                                alt="Avatar"
                            >
                            <label class="absolute bottom-0 right-0 w-8 h-8 bg-primary-600 hover:bg-primary-700 rounded-full flex items-center justify-center cursor-pointer shadow-md transition-colors">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <input type="file" class="hidden" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])">
                            </label>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Jean Dupont</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">jean.dupont@exemple.com</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Membre depuis janvier 2024</p>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Résumé du Compte</h3>
                </div>
                <div class="card-body space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Services actifs</span>
                        <span class="font-semibold text-gray-900 dark:text-white">12</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Factures payées</span>
                        <span class="font-semibold text-gray-900 dark:text-white">21</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Solde de crédit</span>
                        <span class="font-semibold text-success-600 dark:text-success-400">15,00 €</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Statut du compte</span>
                        <span class="badge badge-success">Actif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Forms -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Personal Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Informations Personnelles</h3>
                </div>
                <div class="card-body">
                    <form action="/client/profile" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Prénom</label>
                                <input type="text" name="first_name" value="Jean" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Nom</label>
                                <input type="text" name="last_name" value="Dupont" class="form-input">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Adresse email</label>
                            <input type="email" name="email" value="jean.dupont@exemple.com" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="phone" value="+33 6 12 34 56 78" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Entreprise (optionnel)</label>
                            <input type="text" name="company" value="" class="form-input" placeholder="Votre entreprise">
                        </div>
                        <div>
                            <label class="form-label">Site web (optionnel)</label>
                            <input type="url" name="website" value="" class="form-input" placeholder="https://monsite.com">
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </form>
                </div>
            </div>

            <!-- Billing Address -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Adresse de Facturation</h3>
                </div>
                <div class="card-body">
                    <form action="/client/profile/address" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div>
                            <label class="form-label">Adresse</label>
                            <input type="text" name="address1" value="456 Rue du Client" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Complément d'adresse (optionnel)</label>
                            <input type="text" name="address2" class="form-input" placeholder="Appartement, suite, etc.">
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Code Postal</label>
                                <input type="text" name="postcode" value="69000" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Ville</label>
                                <input type="text" name="city" value="Lyon" class="form-input">
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Région</label>
                                <input type="text" name="state" value="Auvergne-Rhône-Alpes" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Pays</label>
                                <select name="country" class="form-input">
                                    <option value="FR" selected>🇫🇷 France</option>
                                    <option value="BE">🇧🇪 Belgique</option>
                                    <option value="CH">🇨🇭 Suisse</option>
                                    <option value="CA">🇨🇦 Canada</option>
                                    <option value="DE">🇩🇪 Allemagne</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Numéro de TVA (optionnel)</label>
                            <input type="text" name="vat_number" class="form-input" placeholder="FR12345678901">
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour l'adresse</button>
                    </form>
                </div>
            </div>

            <!-- Preferences -->
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Préférences</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Langue</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Langue de l'interface</p>
                        </div>
                        <select class="form-input w-40">
                            <option value="fr" selected>🇫🇷 Français</option>
                            <option value="en">🇬🇧 English</option>
                            <option value="de">🇩🇪 Deutsch</option>
                            <option value="es">🇪🇸 Español</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Devise</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Devise préférée</p>
                        </div>
                        <select class="form-input w-40">
                            <option value="EUR" selected>€ EUR</option>
                            <option value="USD">$ USD</option>
                            <option value="GBP">£ GBP</option>
                            <option value="CHF">CHF</option>
                        </select>
                    </div>

                    <!-- Notification settings -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <p class="font-medium text-gray-900 dark:text-white mb-3">Notifications Email</p>
                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                                ['Nouvelles factures', 'invoice_new', true],
                                ['Rappels de paiement', 'invoice_reminder', true],
                                ['Renouvellements de service', 'service_renewal', true],
                                ['Réponses aux tickets', 'ticket_reply', true],
                                ['Annonces', 'announcements', false],
                                ['Newsletter', 'newsletter', false],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300"><?php echo e($notif[0]); ?></span>
                                <div x-data="{ on: <?php echo e($notif[2] ? 'true' : 'false'); ?> }" @click="on = !on" class="relative cursor-pointer">
                                    <div :class="on ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-11 h-6 rounded-full transition-colors"></div>
                                    <div :class="on ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                                </div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <button class="btn btn-primary">Enregistrer les préférences</button>
                </div>
            </div>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/client/profile/index.blade.php ENDPATH**/ ?>