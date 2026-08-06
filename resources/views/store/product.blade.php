@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li>
                <a href="{{ route('store.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    Boutique
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 mx-1"></i>
                    <a href="{{ route('store.category', $category) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        {{ $category->name }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 mx-1"></i>
                    <span class="text-gray-500 dark:text-gray-400">{{ $product->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Product Details -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">{{ $category->name }}</p>
                        </div>
                        
                        @if(!$product->isInStock())
                            <span class="badge badge-danger">En rupture</span>
                        @elseif($product->is_featured)
                            <span class="badge badge-success">Populaire</span>
                        @endif
                    </div>

                    <div class="prose prose-gray dark:prose-invert max-w-none mb-8">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    @if($product->features)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Fonctionnalités incluses</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($product->features as $feature)
                                    <div class="flex items-center">
                                        <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Specifications -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Spécifications</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ ucfirst($product->type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cycle de facturation</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ ucfirst($product->billing_cycle) }}</dd>
                            </div>
                            @if($product->auto_setup)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Configuration</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">Automatique</dd>
                                </div>
                            @endif
                            @if(!$product->is_unlimited_stock)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock disponible</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $product->stock }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Form -->
        <div>
            <div class="card sticky top-8">
                <div class="p-6">
                    <form action="{{ route('store.cart.add') }}" method="POST" x-data="orderForm()">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold text-gray-900 dark:text-white" x-text="formatPrice(price)"></div>
                            <div class="text-gray-500 dark:text-gray-400" x-text="'par ' + billingCycle"></div>
                            
                            @if($product->setup_fee > 0)
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    + {{ $product->setup_fee }}€ frais d'installation
                                </div>
                            @endif
                        </div>

                        <!-- Billing Cycle -->
                        <div class="mb-6">
                            <label class="label">Cycle de facturation</label>
                            <select name="billing_cycle" x-model="billingCycle" @change="updatePrice()" class="input">
                                <option value="monthly">Mensuel</option>
                                <option value="quarterly">Trimestriel (-5%)</option>
                                <option value="semi_annually">Semestriel (-10%)</option>
                                <option value="annually">Annuel (-15%)</option>
                                <option value="biennially">Biennal (-20%)</option>
                                <option value="triennially">Triennal (-25%)</option>
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-6">
                            <label class="label">Quantité</label>
                            <select name="quantity" class="input">
                                @for($i = 1; $i <= min(10, $product->stock ?: 10); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Configuration Options -->
                        @if($product->config)
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Configuration</h4>
                                @foreach($product->config as $key => $option)
                                    <div class="mb-4">
                                        <label class="label">{{ $option['label'] ?? ucfirst($key) }}</label>
                                        @if($option['type'] === 'select')
                                            <select name="config[{{ $key }}]" class="input">
                                                @foreach($option['options'] as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($option['type'] === 'text')
                                            <input type="text" name="config[{{ $key }}]" placeholder="{{ $option['placeholder'] ?? '' }}" class="input">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Add to Cart -->
                        @if($product->isInStock())
                            @auth
                                <button type="submit" class="btn-primary w-full mb-3">
                                    <i data-lucide="shopping-cart" class="w-4 h-4 mr-2"></i>
                                    Ajouter au panier
                                </button>
                            @else
                                <a href="{{ route('register') }}" class="btn-primary w-full mb-3 text-center block">
                                    <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                                    S'inscrire pour commander
                                </a>
                            @endauth
                        @else
                            <button type="button" disabled class="btn bg-gray-300 text-gray-500 cursor-not-allowed w-full mb-3">
                                <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                                Non disponible
                            </button>
                        @endif

                        <!-- Support -->
                        <div class="text-center">
                            <a href="{{ auth() ? route('client.tickets.create') : route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                <i data-lucide="help-circle" class="w-4 h-4 inline mr-1"></i>
                                Besoin d'aide ?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function orderForm() {
    return {
        basePrice: {{ $product->price }},
        billingCycle: '{{ $product->billing_cycle }}',
        price: {{ $product->price }},
        
        updatePrice() {
            const multipliers = {
                monthly: 1,
                quarterly: 3 * 0.95,
                semi_annually: 6 * 0.9,
                annually: 12 * 0.85,
                biennially: 24 * 0.8,
                triennially: 36 * 0.75
            };
            
            this.price = Math.round(this.basePrice * (multipliers[this.billingCycle] || 1) * 100) / 100;
        },
        
        formatPrice(price) {
            return price.toFixed(2) + '€';
        }
    }
}
</script>
@endpush
@endsection