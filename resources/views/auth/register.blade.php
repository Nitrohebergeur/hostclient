<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte — {{ config('hostclient.company_name', 'HostClient') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="hc-auth-page">
    <div class="hc-auth-side">
        <a href="{{ route('home') }}" class="hc-brand hc-brand-light">
            <div class="hc-brand-mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12 L12 3 L21 12 M5 10 V21 H19 V10"/>
                </svg>
            </div>
            <span>{{ config('hostclient.company_name', 'HostClient') }}</span>
        </a>

        <div>
            <h2>Rejoignez {{ config('hostclient.company_name', 'HostClient') }} en quelques secondes.</h2>
            <p>Créez votre compte gratuitement et lancez votre premier service.</p>
            <ul class="hc-auth-side-features" style="list-style: none; padding: 0;">
                <li>Inscription gratuite, sans engagement</li>
                <li>Déploiement en moins de 2 minutes</li>
                <li>Support technique 24/7</li>
                <li>Migration offerte sur demande</li>
            </ul>
        </div>

        <p style="font-size: var(--hc-text-sm); color: rgba(255,255,255,0.6);">
            © {{ date('Y') }} {{ config('hostclient.company_name', 'HostClient') }}
        </p>
    </div>

    <div class="hc-auth-form-side">
        <div class="hc-auth-form">
            <h1>Créer un compte</h1>
            <p class="hc-auth-subtitle">Quelques informations pour démarrer.</p>

            @if($errors->any())
                <x-alert type="danger">
                    {{ $errors->first() }}
                </x-alert>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <x-form-input
                    label="Nom complet"
                    name="name"
                    required
                    placeholder="Jean Dupont"
                    :value="old('name')"
                    autocomplete="name"
                />

                <x-form-input
                    label="Adresse e-mail"
                    name="email"
                    type="email"
                    required
                    placeholder="vous@exemple.com"
                    :value="old('email')"
                    autocomplete="email"
                />

                <x-form-input
                    label="Mot de passe"
                    name="password"
                    type="password"
                    required
                    placeholder="Minimum 8 caractères"
                    autocomplete="new-password"
                />

                <x-form-input
                    label="Confirmer le mot de passe"
                    name="password_confirmation"
                    type="password"
                    required
                    placeholder="••••••••"
                    autocomplete="new-password"
                />

                <label style="display: flex; align-items: flex-start; gap: var(--hc-space-2); margin-bottom: var(--hc-space-6); font-size: var(--hc-text-sm); color: var(--hc-text-muted);">
                    <input type="checkbox" name="terms" required style="margin-top: 3px;">
                    <span>J'accepte les <a href="#" style="color: var(--hc-primary);">CGV</a> et la <a href="#" style="color: var(--hc-primary);">politique de confidentialité</a>.</span>
                </label>

                <x-button type="submit" variant="primary" size="lg" style="width: 100%;">
                    Créer mon compte
                </x-button>
            </form>

            <div class="hc-auth-footer">
                Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>