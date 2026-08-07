@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
<div class="card" data-aos="fade-up">
    <div class="card-body">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Créer un compte</h1>
            <p class="text-gray-600 dark:text-gray-300">Commencez votre essai gratuit aujourd'hui</p>
        </div>
        
        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            
            <!-- Name -->
            <div>
                <label for="name" class="form-label">Nom complet</label>
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    class="form-input @error('name') border-danger-500 @enderror"
                    placeholder="Jean Dupont"
                >
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="form-label">Adresse email</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    class="form-input @error('email') border-danger-500 @enderror"
                    placeholder="vous@exemple.com"
                >
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Company (Optional) -->
            <div>
                <label for="company" class="form-label">Entreprise (optionnel)</label>
                <input 
                    id="company" 
                    type="text" 
                    name="company" 
                    value="{{ old('company') }}" 
                    class="form-input"
                    placeholder="Mon Entreprise"
                >
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="form-label">Mot de passe</label>
                <div x-data="{ show: false }" class="relative">
                    <input 
                        id="password" 
                        :type="show ? 'text' : 'password'" 
                        name="password" 
                        required 
                        class="form-input pr-10 @error('password') border-danger-500 @enderror"
                        placeholder="••••••••"
                    >
                    <button 
                        type="button" 
                        @click="show = !show" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Minimum 8 caractères avec au moins une majuscule et un chiffre
                </p>
            </div>
            
            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    class="form-input"
                    placeholder="••••••••"
                >
            </div>
            
            <!-- Terms -->
            <div class="flex items-start">
                <input 
                    id="terms" 
                    type="checkbox" 
                    name="terms" 
                    required 
                    class="w-4 h-4 mt-1 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                >
                <label for="terms" class="ml-2 text-sm text-gray-600 dark:text-gray-300">
                    J'accepte les 
                    <a href="/terms" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                        conditions d'utilisation
                    </a> 
                    et la 
                    <a href="/privacy" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                        politique de confidentialité
                    </a>
                </label>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-full">
                Créer mon compte
            </button>
        </form>
        
        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">Ou continuer avec</span>
            </div>
        </div>
        
        <!-- Social Register -->
        <div class="grid grid-cols-2 gap-4">
            <button type="button" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                </svg>
                Google
            </button>
            <button type="button" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                </svg>
                GitHub
            </button>
        </div>
        
        <!-- Login Link -->
        <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-300">
            Vous avez déjà un compte ?
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                Se connecter
            </a>
        </p>
    </div>
</div>
@endsection
