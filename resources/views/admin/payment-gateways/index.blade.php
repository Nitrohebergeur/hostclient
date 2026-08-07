@extends('layouts.admin')
@section('title', 'Passerelles de Paiement')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Passerelles de Paiement</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Stripe, PayPal, Mollie, crypto et plus</p>
        </div>
        <a href="{{ route('admin.payment-gateways.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter une Passerelle
        </a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($gateways as $gateway)
        <div class="card {{ $gateway->is_active ? '' : 'opacity-60' }} hover:shadow-md transition-all">
            <div class="card-body">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl
                            {{ $gateway->is_active ? 'bg-primary-100 dark:bg-primary-900/30' : 'bg-gray-100 dark:bg-gray-700' }}">
                            @switch($gateway->slug)
                                @case('stripe') 💳 @break
                                @case('paypal') 🅿 @break
                                @case('mollie') 💶 @break
                                @case('coinbase') ₿ @break
                                @case('razorpay') 🪙 @break
                                @case('bank_transfer') 🏦 @break
                                @case('credit') 👛 @break
                                @default 💰 @break
                            @endswitch
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $gateway->name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $gateway->slug }}</p>
                        </div>
                    </div>

                    <!-- Toggle On/Off -->
                    <form action="{{ route('admin.payment-gateways.toggle', $gateway) }}" method="POST">
                        @csrf
                        <button type="submit" class="relative inline-flex items-center cursor-pointer" title="{{ $gateway->is_active ? 'Désactiver' : 'Activer' }}">
                            <div class="w-11 h-6 rounded-full transition-colors {{ $gateway->is_active ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }} relative">
                                <span class="absolute top-[2px] {{ $gateway->is_active ? 'left-[22px]' : 'left-[2px]' }} w-5 h-5 bg-white rounded-full shadow transition-all"></span>
                            </div>
                        </button>
                    </form>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $gateway->description }}</p>

                <!-- Badges features -->
                <div class="flex flex-wrap gap-1 mb-4">
                    @if($gateway->supports_recurring)
                        <span class="badge badge-success text-xs">🔄 Récurrent</span>
                    @endif
                    @if($gateway->supports_refunds)
                        <span class="badge badge-secondary text-xs">↩ Remboursement</span>
                    @endif
                    @if($gateway->supports_webhooks)
                        <span class="badge badge-secondary text-xs">🔗 Webhooks</span>
                    @endif
                </div>

                <!-- Frais -->
                @if($gateway->fee_fixed > 0 || $gateway->fee_percentage > 0)
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Frais :
                    @if($gateway->fee_fixed > 0) {{ number_format($gateway->fee_fixed, 2) }}€ @endif
                    @if($gateway->fee_fixed > 0 && $gateway->fee_percentage > 0) + @endif
                    @if($gateway->fee_percentage > 0) {{ $gateway->fee_percentage }}% @endif
                </div>
                @endif

                <div class="flex gap-2">
                    <a href="{{ route('admin.payment-gateways.edit', $gateway) }}" class="btn btn-sm btn-secondary flex-1 text-center">
                        Configurer
                    </a>
                    <form action="{{ route('admin.payment-gateways.destroy', $gateway) }}" method="POST" onsubmit="return confirm('Supprimer cette passerelle ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="md:col-span-3 card">
            <div class="card-body text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">Aucune passerelle configurée.</p>
                <a href="{{ route('admin.payment-gateways.create') }}" class="btn btn-primary mt-4">Ajouter la première</a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
