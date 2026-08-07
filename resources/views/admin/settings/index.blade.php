@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Paramètres Généraux</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Configurez les paramètres de votre plateforme</p>
    </div>

    <div x-data="{ tab: 'general' }">

        <!-- Tab Nav -->
        <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
            <nav class="flex gap-0 -mb-px min-w-max">
                @foreach([
                    ['general',   'Général'],
                    ['billing',   'Facturation'],
                    ['email',     'Emails'],
                    ['security',  'Sécurité'],
                    ['payment',   'Paiements'],
                    ['company',   'Entreprise'],
                ] as [$key, $label])
                <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}'
                        ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                        : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                    class="px-5 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                    {{ $label }}
                </button>
                @endforeach
            </nav>
        </div>

        <!-- General -->
        <div x-show="tab === 'general'" class="mt-6">
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Informations Générales</h3></div>
                <div class="card-body">
                    <form class="space-y-5">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Nom du Site</label>
                                <input type="text" value="HostClient" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">URL du Site</label>
                                <input type="url" value="https://hostclient.io" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Langue par défaut</label>
                                <select class="form-input">
                                    <option value="fr" selected>🇫🇷 Français</option>
                                    <option value="en">🇬🇧 English</option>
                                    <option value="de">🇩🇪 Deutsch</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Devise par défaut</label>
                                <select class="form-input">
                                    <option value="EUR" selected>€ EUR — Euro</option>
                                    <option value="USD">$ USD — Dollar</option>
                                    <option value="GBP">£ GBP — Livre Sterling</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Fuseau Horaire</label>
                                <select class="form-input">
                                    <option>Europe/Paris</option>
                                    <option>Europe/London</option>
                                    <option>UTC</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Format de date</label>
                                <select class="form-input">
                                    <option>DD/MM/YYYY</option>
                                    <option>MM/DD/YYYY</option>
                                    <option>YYYY-MM-DD</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Description du Site</label>
                            <textarea rows="3" class="form-input">Plateforme de gestion d'hébergement web</textarea>
                        </div>

                        <!-- Toggles -->
                        <div class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-5">
                            @foreach([
                                ['Inscriptions ouvertes',         'Permettre aux nouveaux utilisateurs de s\'inscrire',  true],
                                ['Mode maintenance',               'Activer le mode maintenance (déconnecte les clients)', false],
                                ['Validation manuelle commandes',  'Les nouvelles commandes nécessitent une validation',   false],
                                ['Mode démo',                      'Afficher le bandeau démo sur le site',                false],
                            ] as [$label, $desc, $default])
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $label }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                                </div>
                                <div x-data="{ on: {{ $default ? 'true' : 'false' }} }" @click="on = !on" class="relative cursor-pointer">
                                    <div :class="on ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-11 h-6 rounded-full transition-colors"></div>
                                    <div :class="on ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Billing -->
        <div x-show="tab === 'billing'" class="mt-6">
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Paramètres de Facturation</h3></div>
                <div class="card-body space-y-5">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Préfixe Facture</label>
                            <input type="text" value="INV-" class="form-input" placeholder="INV-">
                        </div>
                        <div>
                            <label class="form-label">Jours avant rappel</label>
                            <input type="number" value="7" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Jours avant suspension</label>
                            <input type="number" value="3" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Jours avant résiliation</label>
                            <input type="number" value="14" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Taux de TVA par défaut (%)</label>
                            <input type="number" value="20" step="0.1" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Fermeture auto tickets (jours)</label>
                            <input type="number" value="7" class="form-input">
                        </div>
                    </div>
                    <button class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>

        <!-- Email -->
        <div x-show="tab === 'email'" class="mt-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm">
                    ✗ {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="font-bold text-gray-900 dark:text-white">Configuration Email (SMTP)</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ces paramètres sont sauvegardés en base de données et appliqués au fichier .env</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="group" value="email">

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Driver</label>
                                <select name="mail_mailer" class="form-input">
                                    @foreach(['smtp' => 'SMTP', 'mailgun' => 'Mailgun', 'postmark' => 'Postmark', 'ses' => 'Amazon SES', 'sendmail' => 'Sendmail'] as $val => $label)
                                        <option value="{{ $val }}" {{ ($settings['mail_mailer'] ?? 'smtp') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Hôte SMTP</label>
                                <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" class="form-input" placeholder="smtp.example.com">
                            </div>
                            <div>
                                <label class="form-label">Port SMTP</label>
                                <input type="number" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Chiffrement</label>
                                <select name="mail_encryption" class="form-input">
                                    @foreach(['tls' => 'TLS (587)', 'ssl' => 'SSL (465)', '' => 'Aucun'] as $val => $label)
                                        <option value="{{ $val }}" {{ ($settings['mail_encryption'] ?? 'tls') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Utilisateur SMTP</label>
                                <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" class="form-input" placeholder="user@smtp.example.com">
                            </div>
                            <div>
                                <label class="form-label">Mot de passe SMTP</label>
                                <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" class="form-input" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="form-label">Nom expéditeur</label>
                                <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? 'HostClient' }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Email expéditeur</label>
                                <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" class="form-input" placeholder="noreply@votredomaine.com">
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>

                    <!-- Test email séparé -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-3">Tester la configuration</h4>
                        <form method="POST" action="{{ route('admin.settings.test-email') }}" class="flex gap-3">
                            @csrf
                            <input type="email" name="test_email" class="form-input max-w-xs" placeholder="destinataire@example.com" required>
                            <button type="submit" class="btn btn-secondary">Envoyer un email de test</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div x-show="tab === 'security'" class="mt-6">
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Paramètres de Sécurité</h3></div>
                <div class="card-body space-y-5">
                    <div class="space-y-4">
                        @foreach([
                            ['Forcer 2FA pour admins',       'Tous les admins doivent activer la 2FA',           false],
                            ['reCAPTCHA sur inscription',    'Protéger le formulaire d\'inscription avec reCAPTCHA', true],
                            ['reCAPTCHA sur connexion',      'Protéger la connexion après 3 tentatives échouées',  true],
                            ['Logs d\'audit activés',        'Enregistrer toutes les actions administrateur',     true],
                            ['Rate limiting API',            'Limiter les requêtes API par IP',                   true],
                        ] as [$label, $desc, $default])
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $label }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                            </div>
                            <div x-data="{ on: {{ $default ? 'true' : 'false' }} }" @click="on = !on" class="relative cursor-pointer">
                                <div :class="on ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-11 h-6 rounded-full transition-colors"></div>
                                <div :class="on ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Clé reCAPTCHA (Site)</label>
                            <input type="text" class="form-input" placeholder="6Le...">
                        </div>
                        <div>
                            <label class="form-label">Clé reCAPTCHA (Secrète)</label>
                            <input type="password" class="form-input" placeholder="6Le...">
                        </div>
                        <div>
                            <label class="form-label">Durée session (minutes)</label>
                            <input type="number" value="120" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Rate limit API (req/min)</label>
                            <input type="number" value="60" class="form-input">
                        </div>
                    </div>
                    <button class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>

        <!-- Payment Gateways -->
        <div x-show="tab === 'payment'" class="mt-6 space-y-4">
            @foreach([
                ['Stripe', 'Carte bancaire, Apple Pay, Google Pay', 'stripe', true],
                ['PayPal', 'Paiement PayPal et carte via PayPal', 'paypal', true],
                ['Mollie', 'iDEAL, Bancontact, SEPA et plus', 'mollie', false],
                ['Coinbase Commerce', 'Paiement en cryptomonnaie', 'coinbase', false],
                ['Virement Bancaire', 'Virement SEPA manuel', 'bank_transfer', true],
            ] as [$name, $desc, $key, $enabled])
            <div class="card">
                <div class="card-body">
                    <div x-data="{ open: false, enabled: {{ $enabled ? 'true' : 'false' }} }">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center font-bold text-gray-700 dark:text-gray-300 text-sm">
                                    {{ strtoupper(substr($key, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div @click="enabled = !enabled" class="relative cursor-pointer">
                                    <div :class="enabled ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-11 h-6 rounded-full transition-colors"></div>
                                    <div :class="enabled ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                                </div>
                                <button @click="open = !open" class="btn btn-secondary btn-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Configurer
                                </button>
                            </div>
                        </div>
                        <div x-show="open" x-collapse class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label text-xs">Clé Publique / Client ID</label>
                                    <input type="text" class="form-input text-sm" placeholder="pk_live_...">
                                </div>
                                <div>
                                    <label class="form-label text-xs">Clé Secrète / Secret</label>
                                    <input type="password" class="form-input text-sm" placeholder="sk_live_...">
                                </div>
                                @if($key === 'stripe')
                                <div class="sm:col-span-2">
                                    <label class="form-label text-xs">Webhook Secret</label>
                                    <input type="password" class="form-input text-sm" placeholder="whsec_...">
                                </div>
                                @endif
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button class="btn btn-primary btn-sm">Enregistrer</button>
                                <button class="btn btn-secondary btn-sm">Tester la connexion</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
