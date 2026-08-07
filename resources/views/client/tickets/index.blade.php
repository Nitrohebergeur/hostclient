@extends('layouts.client')

@section('title', 'Support')
@section('subtitle', 'Tickets et demandes d\'assistance')

@section('content')
    <x-page-header title="Support">
        <x-slot:actions>
            <x-button :href="route('client.tickets.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouveau ticket
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" style="padding: var(--hc-space-4); display: flex; gap: var(--hc-space-3); flex-wrap: wrap;">
            <div style="min-width: 200px;">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="open" @selected(request('status') === 'open')>Ouvert</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>En cours</option>
                    <option value="waiting_customer" @selected(request('status') === 'waiting_customer')>En attente client</option>
                    <option value="waiting_staff" @selected(request('status') === 'waiting_staff')>En attente staff</option>
                    <option value="closed" @selected(request('status') === 'closed')>Fermé</option>
                </select>
            </div>
        </form>
    </x-card>

    @if($tickets->count())
        <x-card padding="false">
            <table class="hc-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Sujet</th>
                        <th>Catégorie</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Dernière réponse</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td style="font-family: var(--hc-font-mono); font-weight: 500;">{{ $ticket->ticket_number }}</td>
                            <td><strong>{{ $ticket->subject }}</strong></td>
                            <td>{{ $ticket->category?->name ?? '—' }}</td>
                            <td>
                                <x-badge :variant="match($ticket->priority) {
                                    'urgent' => 'danger',
                                    'high' => 'warning',
                                    'medium' => 'info',
                                    'low' => 'neutral',
                                    default => 'neutral'
                                }">{{ ucfirst($ticket->priority) }}</x-badge>
                            </td>
                            <td>
                                <x-badge :variant="match($ticket->status) {
                                    'open' => 'warning',
                                    'in_progress' => 'info',
                                    'waiting_customer' => 'warning',
                                    'waiting_staff' => 'info',
                                    'closed' => 'neutral',
                                    default => 'neutral'
                                }">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</x-badge>
                            </td>
                            <td style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">
                                {{ $ticket->last_reply_at?->diffForHumans() ?? '—' }}
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('client.tickets.show', $ticket) }}" class="hc-btn hc-btn-ghost hc-btn-sm">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>

        <div style="margin-top: var(--hc-space-6);">
            {{ $tickets->links() }}
        </div>
    @else
        <x-card>
            <x-empty-state
                title="Aucun ticket"
                description="Vous n'avez pas encore ouvert de ticket."
                icon="🎫"
            >
                <x-button :href="route('client.tickets.create')" variant="primary">Créer un ticket</x-button>
            </x-empty-state>
        </x-card>
    @endif
@endsection