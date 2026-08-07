@extends('layouts.admin')

@section('title', 'Passerelles de paiement')
@section('content')
    <x-page-header title="Passerelles de paiement">
        <x-slot:actions>
            <x-button :href="route('admin.payment-gateways.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouvelle passerelle
            </x-button>
        </x-slot:actions>
    </x-page-header>

    @if(($gateways ?? collect())->count() === 0)
        <x-card>
            <x-empty-state title="Aucune passerelle" description="Configurez vos moyens de paiement." icon="💳">
                <x-button :href="route('admin.payment-gateways.create')" variant="primary">Ajouter</x-button>
            </x-empty-state>
        </x-card>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--hc-space-4);">
            @foreach($gateways as $gateway)
                <x-card>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--hc-space-3);">
                        <h3 style="font-size: var(--hc-text-lg); font-weight: 600; margin: 0;">{{ $gateway->name }}</h3>
                        <x-badge :variant="($gateway->is_active ?? true) ? 'success' : 'neutral'">
                            {{ ($gateway->is_active ?? true) ? 'Actif' : 'Inactif' }}
                        </x-badge>
                    </div>
                    <p style="color: var(--hc-text-muted); font-size: var(--hc-text-sm); margin-bottom: var(--hc-space-4);">
                        Provider : <strong>{{ ucfirst($gateway->provider) }}</strong>
                    </p>
                    <div style="display: flex; gap: var(--hc-space-2);">
                        <x-button :href="route('admin.payment-gateways.edit', $gateway)" variant="secondary" size="sm" style="flex: 1;">
                            <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                            Configurer
                        </x-button>
                        <form method="POST" action="{{ route('admin.payment-gateways.destroy', $gateway) }}" onsubmit="return confirm('Supprimer ?')">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="ghost" size="sm" style="color: var(--hc-danger);">
                                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                            </x-button>
                        </form>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
@endsection