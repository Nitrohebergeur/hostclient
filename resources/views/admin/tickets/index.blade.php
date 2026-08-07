@extends('layouts.admin')

@section('title', 'Tickets de support')

@section('content')
    <x-page-header title="Tickets de support" subtitle="Suivi des demandes clients" />

    {{-- Statistiques --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-6);">
        <x-stat label="Total tickets" :value="$stats['total'] ?? 0" icon="message-circle" color="primary" />
        <x-stat label="Ouverts" :value="$stats['open'] ?? 0" icon="inbox" color="warning" />
        <x-stat label="Répondus" :value="$stats['answered'] ?? 0" icon="check-circle" color="success" />
        <x-stat label="Fermés" :value="$stats['closed'] ?? 0" icon="archive" color="info" />
    </div>

    {{-- Filtres --}}
    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" class="hc-filters">
            <div class="hc-filters-field">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Sujet, client..." class="hc-input">
            </div>
            <div class="hc-filters-field-fixed">
                <label class="hc-label">Statut</label>
                <select name="status" class="hc-select">
                    <option value="">Tous</option>
                    <option value="open" @selected(request('status') === 'open')>Ouverts</option>
                    <option value="answered" @selected(request('status') === 'answered')>Répondus</option>
                    <option value="closed" @selected(request('status') === 'closed')>Fermés</option>
                </select>
            </div>
            <div class="hc-filters-field-fixed">
                <label class="hc-label">Priorité</label>
                <select name="priority" class="hc-select">
                    <option value="">Toutes</option>
                    <option value="urgent" @selected(request('priority') === 'urgent')>Urgente</option>
                    <option value="high" @selected(request('priority') === 'high')>Haute</option>
                    <option value="normal" @selected(request('priority') === 'normal')>Normale</option>
                    <option value="low" @selected(request('priority') === 'low')>Basse</option>
                </select>
            </div>
            <div class="hc-filters-actions">
                <x-button type="submit" variant="primary">
                    <i data-lucide="filter" style="width: 14px; height: 14px;"></i>
                    Filtrer
                </x-button>
                @if(request('search') || request('status') || request('priority'))
                    <a href="{{ route('admin.tickets.index') }}" class="hc-btn hc-btn-ghost">Réinitialiser</a>
                @endif
            </div>
        </form>
    </x-card>

    @if($tickets->count() === 0)
        <x-card>
            <x-empty-state title="Aucun ticket" description="Aucun ticket de support trouvé." icon="🎫" />
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Sujet', 'Client', 'Priorité', 'Statut', 'Créé le', '']">
                @foreach($tickets as $ticket)
                    <tr>
                        <td>
                            <a href="{{ route('admin.tickets.show', $ticket) }}" style="display: flex; align-items: center; gap: var(--hc-space-3); text-decoration: none; color: var(--hc-text);">
                                <div class="hc-activity-icon" style="width: 32px; height: 32px; background: {{ $ticket->priority === 'urgent' ? 'var(--hc-danger-50)' : ($ticket->priority === 'high' ? 'var(--hc-warning-50)' : 'var(--hc-primary-50)') }}; color: {{ $ticket->priority === 'urgent' ? 'var(--hc-danger)' : ($ticket->priority === 'high' ? 'var(--hc-warning)' : 'var(--hc-primary)') }};">
                                    <i data-lucide="message-circle"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $ticket->subject }}</div>
                                    @if($ticket->category)
                                        <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $ticket->category->name }}</div>
                                    @endif
                                </div>
                            </a>
                        </td>
                        <td>
                            @if($ticket->user)
                                <div style="font-weight: 500;">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $ticket->user->email }}</div>
                            @else — @endif
                        </td>
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
                        <td>
                            <div style="font-size: var(--hc-text-sm); font-weight: 500;">{{ $ticket->created_at->format('d/m/Y') }}</div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $ticket->created_at->format('H:i') }}</div>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="hc-btn hc-btn-ghost hc-btn-sm" title="Voir le ticket">
                                <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        <div style="margin-top: var(--hc-space-6);">{{ $tickets->links() }}</div>
    @endif
@endsection