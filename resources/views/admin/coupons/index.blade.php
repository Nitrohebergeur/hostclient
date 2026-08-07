@extends('layouts.admin')

@section('title', 'Coupons')
@section('content')
    <x-page-header title="Coupons">
        <x-slot:actions>
            <x-button :href="route('admin.coupons.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouveau coupon
            </x-button>
        </x-slot:actions>
    </x-page-header>

    @if(($coupons ?? collect())->count() === 0)
        <x-card>
            <x-empty-state title="Aucun coupon" icon="🎟️">
                <x-button :href="route('admin.coupons.create')" variant="primary">Créer un coupon</x-button>
            </x-empty-state>
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Code', 'Type', 'Valeur', 'Utilisations', 'Valide jusqu\'au', 'Statut', '']">
                @foreach($coupons as $coupon)
                    <tr>
                        <td style="font-family: var(--hc-font-mono); font-weight: 600;">{{ $coupon->code }}</td>
                        <td>{{ ucfirst($coupon->type ?? '—') }}</td>
                        <td><strong>{{ $coupon->type === 'percentage' ? $coupon->value . '%' : number_format($coupon->value, 2) . ' €' }}</strong></td>
                        <td>{{ $coupon->usages_count ?? 0 }} / {{ $coupon->max_uses ?? '∞' }}</td>
                        <td>{{ $coupon->expires_at?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            <x-badge :variant="($coupon->is_active ?? true) ? 'success' : 'neutral'">
                                {{ ($coupon->is_active ?? true) ? 'Actif' : 'Inactif' }}
                            </x-badge>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="hc-btn hc-btn-ghost hc-btn-sm">
                                <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" style="display: inline;" onsubmit="return confirm('Supprimer ce coupon ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="hc-btn hc-btn-ghost hc-btn-sm" style="color: var(--hc-danger);">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        <div style="margin-top: var(--hc-space-6);">
            {{ $coupons->links() }}
        </div>
    @endif
@endsection