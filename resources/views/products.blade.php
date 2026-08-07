@extends('layouts.app')
@section('title', $category ? $category->name : 'Nos Produits')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">HostClient</span>
                </a>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? '/admin/dashboard' : '/client/dashboard' }}" class="btn btn-secondary">Dashboard</a>
                    @else
                        <a href="/login" class="btn btn-ghost">Connexion</a>
                        <a href="/register" class="btn btn-primary">S'inscrire</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Catégories -->
            <aside class="space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-bold text-gray-900 dark:text-white">Catégories</h3>
                    </div>
                    <div class="card-body p-0">
                        <a href="{{ route('products') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !$category ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Toutes les catégories
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $category && $category->id === $cat->id ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                            @if($cat->icon)
                                <i class="{{ $cat->icon }} w-5 h-5"></i>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            @endif
                            <span class="flex-1">{{ $cat->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <!-- Grid Produits -->
            <div class="lg:col-span-3">

                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $category ? $category->name : 'Tous nos produits' }}
                        </h1>
                        @if($category && $category->description)
                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($products as $product)
                    <div class="card hover:shadow-xl transition-all group">
                        <div class="card-body">
                            <!-- Badge -->
                            <div class="flex items-start justify-between mb-4">
                                <span class="badge badge-secondary text-xs">{{ $product->category->name }}</span>
                                @if($product->is_featured)
                                    <span class="badge badge-warning text-xs">⭐ Populaire</span>
                                @endif
                            </div>

                            <!-- Nom -->
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                {{ $product->name }}
                            </h3>

                            <!-- Description -->
                            @if($product->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $product->description }}</p>
                            @endif

                            <!-- Ressources -->
                            @if($product->resources)
                                <div class="space-y-1 mb-4 text-xs">
                                    @foreach(array_slice($product->resources, 0, 3) as $key => $value)
                                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            <span>{{ ucfirst(str_replace('_', ' ', $key)) }}: <strong>{{ $value }}</strong></span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Prix -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                <div class="flex items-end justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">À partir de</p>
                                        @if($product->allow_hourly_billing && $product->price_hourly > 0)
                                            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                                {{ $currency->format($product->price_hourly) }}<span class="text-sm font-normal text-gray-500">/h</span>
                                            </p>
                                        @elseif($product->price_monthly > 0)
                                            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                                {{ $currency->format($product->price_monthly) }}<span class="text-sm font-normal text-gray-500">/mois</span>
                                            </p>
                                        @else
                                            <p class="text-lg text-gray-500 dark:text-gray-400">Sur devis</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-primary btn-sm">
                                        Commander
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-16">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Aucun produit disponible dans cette catégorie.</p>
                    </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
