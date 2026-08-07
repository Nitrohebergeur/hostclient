<?php $__env->startSection('title', 'Facture #INV-2024-003'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="/client/invoices" class="hover:text-primary-600 dark:hover:text-primary-400">Factures</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white">#INV-2024-003</span>
    </nav>

    <!-- Invoice Document -->
    <div class="card max-w-3xl mx-auto" id="invoice-doc">

        <!-- Invoice Header -->
        <div class="card-body">
            <div class="flex flex-col md:flex-row justify-between gap-6 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">HostClient</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">123 Rue de l'Innovation<br>75001 Paris, France<br>TVA: FR12345678901</p>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">FACTURE</h2>
                    <p class="text-gray-500 dark:text-gray-400">N° <span class="font-bold text-gray-900 dark:text-white">#INV-2024-003</span></p>
                    <p class="text-gray-500 dark:text-gray-400">Date: <span class="font-medium text-gray-900 dark:text-white">01/02/2024</span></p>
                    <p class="text-gray-500 dark:text-gray-400">Échéance: <span class="font-medium text-danger-600 dark:text-danger-400">15/02/2024</span></p>
                    <div class="mt-2">
                        <span class="badge badge-warning">En attente de paiement</span>
                    </div>
                </div>
            </div>

            <!-- Client Info -->
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-8">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Facturé à</p>
                <p class="font-bold text-gray-900 dark:text-white">Jean Dupont</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">jean.dupont@exemple.com</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">456 Rue du Client, 69000 Lyon, France</p>
            </div>

            <!-- Items -->
            <table class="w-full mb-6">
                <thead>
                    <tr class="border-b-2 border-gray-200 dark:border-gray-600">
                        <th class="text-left py-2 text-sm font-semibold text-gray-600 dark:text-gray-400">Description</th>
                        <th class="text-center py-2 text-sm font-semibold text-gray-600 dark:text-gray-400">Qté</th>
                        <th class="text-right py-2 text-sm font-semibold text-gray-600 dark:text-gray-400">P.U.</th>
                        <th class="text-right py-2 text-sm font-semibold text-gray-600 dark:text-gray-400">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-3">
                            <p class="font-medium text-gray-900 dark:text-white">VPS Cloud Standard</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Période: 01/02/2024 → 29/02/2024</p>
                        </td>
                        <td class="text-center py-3 text-gray-900 dark:text-white">1</td>
                        <td class="text-right py-3 text-gray-900 dark:text-white">24,99 €</td>
                        <td class="text-right py-3 font-medium text-gray-900 dark:text-white">24,99 €</td>
                    </tr>
                </tbody>
            </table>

            <!-- Totals -->
            <div class="flex justify-end">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>Sous-total HT</span>
                        <span>24,99 €</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>TVA (20%)</span>
                        <span>5,00 €</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-2 flex justify-between font-bold text-lg text-gray-900 dark:text-white">
                        <span>Total TTC</span>
                        <span>29,99 €</span>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4">Payer cette facture</h3>
                <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <button class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-gray-600 hover:border-primary-500 dark:hover:border-primary-500 rounded-xl transition-colors group">
                        <svg class="w-8 h-8 text-blue-500" viewBox="0 0 24 24" fill="currentColor"><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.563.563 0 0 0-.556.479l-1.187 7.527h-.506l-.24 1.516a.56.56 0 0 0 .554.647h3.882c.46 0 .85-.334.922-.788.06-.26.76-4.852.816-5.09a.932.932 0 0 1 .923-.788h.58c3.76 0 6.705-1.528 7.565-5.946.36-1.847.174-3.388-.777-4.471z"/></svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">PayPal</span>
                    </button>
                    <button class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-gray-600 hover:border-primary-500 dark:hover:border-primary-500 rounded-xl transition-colors">
                        <svg class="w-8 h-8 text-purple-500" viewBox="0 0 24 24" fill="currentColor"><path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.591-7.305z"/></svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Stripe</span>
                    </button>
                    <button class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-gray-600 hover:border-primary-500 dark:hover:border-primary-500 rounded-xl transition-colors">
                        <svg class="w-8 h-8 text-indigo-500" viewBox="0 0 24 24" fill="currentColor"><path d="M8.0001 0L0 8.0001V16.0001L8.0001 24H16.0001L24 16.0001V8.0001L16.0001 0H8.0001zM12 6.5C14.4853 6.5 16.5 8.5147 16.5 11V13C16.5 15.4853 14.4853 17.5 12 17.5C9.5147 17.5 7.5 15.4853 7.5 13V11C7.5 8.5147 9.5147 6.5 12 6.5z"/></svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mollie</span>
                    </button>
                    <button class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-gray-600 hover:border-primary-500 dark:hover:border-primary-500 rounded-xl transition-colors">
                        <svg class="w-8 h-8 text-orange-500" viewBox="0 0 24 24" fill="currentColor"><path d="M23.638 14.904c-1.602 6.425-8.113 10.34-14.542 8.736C2.67 22.05-1.244 15.525.362 9.105 1.962 2.67 8.475-1.243 14.9.358c6.43 1.605 10.342 8.115 8.738 14.548v-.002zm-6.35-4.613c.24-1.59-.974-2.45-2.64-3.03l.54-2.153-1.315-.328-.525 2.107c-.345-.086-.705-.167-1.064-.25l.526-2.127-1.32-.33-.54 2.165c-.285-.067-.565-.132-.84-.2l-1.815-.45-.35 1.407s.974.225.955.236c.535.136.63.486.615.766l-1.477 5.92c-.075.166-.24.406-.614.314.015.02-.96-.24-.96-.24l-.66 1.51 1.71.426.93.242-.54 2.19 1.32.327.54-2.17c.36.1.705.19 1.05.273l-.51 2.154 1.32.33.545-2.19c2.24.427 3.93.257 4.64-1.774.57-1.637-.03-2.58-1.217-3.196.854-.193 1.5-.76 1.68-1.93h.01zm-3.01 4.22c-.404 1.64-3.157.75-4.05.53l.72-2.9c.896.23 3.757.67 3.33 2.37zm.41-4.24c-.37 1.49-2.662.735-3.405.55l.654-2.64c.744.18 3.137.524 2.75 2.084v.006z"/></svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Crypto</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Actions -->
    <div class="max-w-3xl mx-auto flex gap-3">
        <a href="/client/invoices/inv-2024-003/pdf" class="btn btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Télécharger PDF
        </a>
        <a href="/client/invoices" class="btn btn-ghost">← Retour aux factures</a>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/hostclient/resources/views/client/invoices/show.blade.php ENDPATH**/ ?>