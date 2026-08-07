<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — {{ config('hostclient.company_name', 'HostClient') }}</title>
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
            <h2>Pilotez votre infrastructure en toute confiance.</h2>
            <p>Console unique, facturation centralisée, support d'experts.</p>
            <ul class="hc-auth-side-features" style="list-style: none; padding: 0;">
                <li>Console unifiée pour vos services</li>
                <li>Facturation automatique et transparente</li>
                <li>Support technique 24/7 en français</li>
                <li>Sauvegardes et restauration en un clic</li>
            </ul>
        </div>

        <p style="font-size: var(--hc-text-sm); color: rgba(255,255,255,0.6);">
            © {{ date('Y') }} {{ config('hostclient.company_name', 'HostClient') }}
        </p>
    </div>

    <div class="hc-auth-form-side">
        <div class="hc-auth-form">
            <h1>Connexion</h1>
            <p class="hc-auth-subtitle">Accédez à votre espace {{ config('hostclient.company_name', 'HostClient') }}.</p>

            @if($errors->any())
                <x-alert type="danger">
                    {{ $errors->first() }}
                </x-alert>
            @endif

            @if(session('status'))
                <x-alert type="success">
                    {{ session('status') }}
                </x-alert>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

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
                    placeholder="••••••••"
                    autocomplete="current-password"
                />

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--hc-space-6);">
                    <label style="display: inline-flex; align-items: center; gap: var(--hc-space-2); font-size: var(--hc-text-sm); color: var(--hc-text-muted);">
                        <input type="checkbox" name="remember">
                        Se souvenir de moi
                    </label>
                    <a href="#" style="font-size: var(--hc-text-sm); color: var(--hc-primary);">Mot de passe oublié ?</a>
                </div>

                <x-button type="submit" variant="primary" size="lg" style="width: 100%;">
                    Se connecter
                </x-button>
            </form>

            <div class="hc-auth-footer">
                Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>