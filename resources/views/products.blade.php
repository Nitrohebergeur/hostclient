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
