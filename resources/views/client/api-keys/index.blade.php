@extends('layouts.client')

@section('title', 'Clés API')
@section('subtitle', 'Gérez vos clés d\'accès à l\'API')

@section('content')
    <x-page-header title="Clés API">
        <x-slot:actions>
            <x-button :href="route('client.api-keys.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouvelle clé
            </x-button>
        </x-slot:actions>
    </x-page-header>

    @if($apiKeys->count())
        <x-card padding="false">
            <table class="hc-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Préfixe</th>
                        <th>Dernière utilisation</th>
                        <th>Expire le</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($apiKeys as $apiKey)
                        <tr>
                            <td><strong>{{ $apiKey->name }}</strong></td>
                            <td style="font-family: var(--hc-font-mono); font-size: var(--hc-text-xs); color: var(--hc-text-muted);">
                                {{ substr($apiKey->key, 0, 12) }}…
                            </td>
                            <td>{{ $apiKey->last_used_at?->diffForHumans() ?? 'Jamais' }}</td>
                            <td>{{ $apiKey->expires_at?->format('d/m/Y') ?? '—' }}</td>
                            <td>
                                @if($apiKey->isExpired())
                                    <x-badge variant="danger">Expirée</x-badge>
                                @elseif($apiKey->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="neutral">Inactive</x-badge>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <form method="POST" action="{{ route('client.api-keys.destroy', $apiKey) }}" style="display: inline;" onsubmit="return confirm('Supprimer cette clé API ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hc-btn hc-btn-ghost hc-btn-sm" style="color: var(--hc-danger);">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>
    @else
        <x-card>
            <x-empty-state
                title="Aucune clé API"
                description="Vous n'avez pas encore créé de clé API."
                icon="🔑"
            >
                <x-button :href="route('client.api-keys.create')" variant="primary">Créer une clé</x-button>
            </x-empty-state>
        </x-card>
    @endif

    @if(session('new_key'))
        <x-card header="⚠️ Copiez cette clé maintenant" style="margin-top: var(--hc-space-6);">
            <div style="background: var(--hc-warning-bg); border: 1px solid var(--hc-warning); border-radius: var(--hc-radius); padding: var(--hc-space-4); font-family: var(--hc-font-mono); font-size: var(--hc-text-sm); word-break: break-all;">
                {{ session('new_key') }}
            </div>
            <p style="margin-top: var(--hc-space-3); font-size: var(--hc-text-sm); color: var(--hc-text-muted);">
                Pour des raisons de sécurité, cette clé ne sera plus jamais affichée. Stockez-la dans un endroit sûr.
            </p>
        </x-card>
    @endif
@endsection
