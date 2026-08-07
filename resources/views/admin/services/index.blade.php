@extends('layouts.admin')

@section('title', 'Services')

@section('content')
    <x-page-header title="Services actifs" />

    @if($services->count() === 0)
        <x-card>
            <x-empty-state title="Aucun service" description="Les services activés apparaîtront ici." icon="🖥️" />
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Service', 'Client', 'Produit', 'Cycle', 'Statut', 'Activé le', '']">
                @foreach($services as $service)
                    <tr>
                        <td><strong>{{ $service->name }}</strong></td>
                        <td>{{ $service->user?->first_name ?? '—' }} {{ $service->user?->last_name ?? '' }}</td>
                        <td>{{ $service->product?->name ?? '—' }}</td>
                        <td>{{ ucfirst($service->billing_cycle ?? '—') }}</td>
                        <td>
                            <x-badge :variant="match($service->status) {
                                'active' => 'success',
                                'pending' => 'warning',
                                'suspended' => 'danger',
                                'terminated' => 'neutral',
                                default => 'neutral'
                            }">
                                {{ ucfirst($service->status ?? 'unknown') }}
                            </x-badge>
                        </td>
                        <td>{{ $service->activated_at?->format('d/m/Y') ?? '—' }}</td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.services.show', $service) }}" class="hc-btn hc-btn-ghost hc-btn-sm">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        <div style="margin-top: var(--hc-space-6);">{{ $services->links() }}</div>
    @endif
@endsection