<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center py-12">
    <div class="w-full max-w-md p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Créer votre compte</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label" for="first_name">Prénom</label>
                    <input id="first_name" type="text" name="first_name"
                        value="{{ old('first_name') }}" class="input" required>
                </div>
                <div>
                    <label class="label" for="last_name">Nom</label>
                    <input id="last_name" type="text" name="last_name"
                        value="{{ old('last_name') }}" class="input" required>
                </div>
            </div>

            <div>
                <label class="label" for="email">Adresse email</label>
                <input id="email" type="email" name="email"
                    value="{{ old('email') }}" class="input" required>
            </div>

            <div>
                <label class="label" for="password">Mot de passe</label>
                <input id="password" type="password" name="password" class="input" required>
            </div>

            <div>
                <label class="label" for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password"
                    name="password_confirmation" class="input" required>
            </div>

            <button type="submit" class="w-full btn-primary justify-center py-3">
                Créer mon compte
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
            Déjà un compte ?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
        </p>
    </div>
</body>
</html>
