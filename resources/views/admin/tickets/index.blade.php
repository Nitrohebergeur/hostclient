@extends('layouts.admin')

@section('title', 'Support')

@section('content')
    <x-page-header title="Tickets de support" />

    @if($tickets->count() === 0)
        <x-card>
            <x-empty-state title="Aucun ticket" description="Aucun ticket de support ouvert." icon="🎫" />
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Sujet', 'Client', 'Priorité', 'Statut', 'Créé le', '']">
                @foreach($tickets as $ticket)
                    <tr>
                        <td><strong>{{ $ticket->subject }}</strong></td>
                        <td>{{ $ticket->user?->first_name ?? '—' }} {{ $ticket->user?->last_name ?? '' }}</td>
                        <td>
                            <x-badge :variant="match($ticket->priority) {
                                'urgent' => 'danger',
                                'high' => 'warning',
                                'normal' => 'info',
                                'low' => 'neutral',
                                default => 'neutral'
                            }">{{ ucfirst($ticket->priority ?? 'normal') }}</x-badge>
                        </td>
                        <td>
                            <x-badge :variant="match($ticket->status) {
                                'open' => 'warning',
                                'answered' => 'info',
                                'closed' => 'neutral',
                                default => 'neutral'
                            }">{{ ucfirst($ticket->status ?? 'open') }}</x-badge>
                        </td>
                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="hc-btn hc-btn-ghost hc-btn-sm">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        <div style="margin-top: var(--hc-space-6);">{{ $tickets->links() }}</div>
    @endif
@endsection