@extends('layouts.app')

@section('title', 'Panier')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Mon Panier</h1>

    @if(empty($cart))
        <!-- Empty Cart -->
        <div class="text-center py-12">
            <i data-lucide="shopping-cart" class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Votre panier est vide</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Découvrez nos services et ajoutez-les à votre panier.</p>
            <a href="{{ route('store.index') }}" class="btn-primary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Continuer les achats
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Articles ({{ count($cart) }})</h2>
                        
                        <div class="space-y-6">
                            @foreach($cart as $key => $item)
                                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ ucfirst($item['billing_cycle']) }} • Quantité: {{ $item['quantity'] }}
                                        </p>
                                        @if($item['setup_fee'] > 0)
                                            <p class="text-sm text-gray-500 dark:text-gray-500">
                                                Frais d'installation: {{ $item['setup_fee'] }}€
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="text-right">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ number_format($item['price'] * $item['quantity'], 2) }}€
                                        </div>
                                        @if($item['setup_fee'] > 0)
                                            <div class="text-sm text-gray-500 dark:text-gray-500">
                                                +{{ number_format($item['setup_fee'] * $item['quantity'], 2) }}€
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('store.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1"></i>
                                Continuer les achats
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="card">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Résumé de la commande</h2>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Sous-total</span>
                                <span class="text-gray-900 dark:text-white">{{ number_format($subtotal, 2) }}€</span>
                            </div>
                            
                            @if($setupFee > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Frais d'installation</span>
                                    <span class="text-gray-900 dark:text-white">{{ number_format($setupFee, 2) }}€</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">TVA ({{ config('hostclient.tax_rate', 20) }}%)</span>
                                <span class="text-gray-900 dark:text-white">{{ number_format($tax, 2) }}€</span>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-base font-medium text-gray-900 dark:text-white">Total</span>
                                    <span class="text-base font-medium text-gray-900 dark:text-white">{{ number_format($total, 2) }}€</span>
                                </div>
                            </div>
                        </div>

                        @auth
                            <form action="{{ route('store.checkout') }}" method="POST" x-data="checkout()">
                                @csrf

                                <!-- Payment Method -->
                                <div class="mb-6">
                                    <label class="label">Mode de paiement</label>
                                    <div class="space-y-2">
                                        @foreach($gateways as $gateway)
                                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <input type="radio" name="payment_method" value="{{ $gateway->slug }}" class="text-primary-600" required>
                                                <span class="ml-3 text-gray-900 dark:text-white">{{ $gateway->name }}</span>
                                            </label>
                                        @endforeach

                                        @if(auth()->user()->balance >= $total)
                                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <input type="radio" name="payment_method" value="balance" class="text-primary-600">
                                                <div class="ml-3">
                                                    <span class="text-gray-900 dark:text-white">Solde du compte</span>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        Disponible: {{ number_format(auth()->user()->balance, 2) }}€
                                                    </div>
                                                </div>
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <!-- Coupon -->
                                <div class="mb-6">
                                    <label class="label">Code promo (optionnel)</label>
                                    <input type="text" name="coupon_code" placeholder="Entrez votre code" class="input">
                                </div>

                                <button type="submit" class="btn-primary w-full">
                                    <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>
                                    Valider la commande
                                </button>

                                <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-4">
                                    En validant, vous acceptez nos conditions d'utilisation
                                </div>
                            </form>
                        @else
                            <div class="text-center">
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Connectez-vous pour finaliser votre commande</p>
                                <a href="{{ route('login') }}" class="btn-primary w-full mb-2">
                                    <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>
                                    Se connecter
                                </a>
                                <a href="{{ route('register') }}" class="btn-outline w-full">
                                    <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                                    Créer un compte
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Security Badge -->
                <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    <i data-lucide="shield-check" class="w-4 h-4 inline mr-1"></i>
                    Paiement 100% sécurisé
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function checkout() {
    return {
        // Add any checkout-specific functionality here
    }
}
</script>
@endpush
@endsection