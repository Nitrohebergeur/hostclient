@extends('layouts.client')

@section('title', $ticket->subject)
@section('subtitle', 'Ticket ' . $ticket->ticket_number)

@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('client.tickets.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux tickets
        </a>
    </div>

    <x-page-header title="{{ $ticket->subject }}">
        <x-slot:actions>
            <x-badge :variant="match($ticket->status) {
                'open' => 'warning',
                'in_progress' => 'info',
                'waiting_customer' => 'warning',
                'waiting_staff' => 'info',
                'closed' => 'neutral',
                default => 'neutral'
            }">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</x-badge>
            @if(!$ticket->isClosed())
                <form method="POST" action="{{ route('client.tickets.close', $ticket) }}" style="margin: 0;">
                    @csrf
                    <x-button type="submit" variant="secondary" size="sm">
                        <i data-lucide="check" style="width: 14px; height: 14px;"></i>
                        Fermer
                    </x-button>
                </form>
            @endif
        </x-slot:actions>
    </x-page-header>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);" class="hc-detail-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-4);">
            @foreach($ticket->replies as $reply)
                <x-card padding="false">
                    <div style="padding: var(--hc-space-4) var(--hc-space-5); border-bottom: 1px solid var(--hc-border); display: flex; align-items: center; justify-content: space-between; gap: var(--hc-space-3);">
                        <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                            <div style="width: 36px; height: 36px; background: {{ $reply->is_staff ? 'var(--hc-primary)' : 'var(--hc-gray-200)' }}; color: {{ $reply->is_staff ? 'var(--hc-text-inverse)' : 'var(--hc-text)' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                {{ strtoupper(substr($reply->user->first_name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: var(--hc-text-sm);">
                                    {{ $reply->user->first_name ?? '' }} {{ $reply->user->last_name ?? '' }}
                                    @if($reply->is_staff)
                                        <x-badge variant="info" style="margin-left: var(--hc-space-2);">Support</x-badge>
                                    @endif
                                </div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $reply->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: var(--hc-space-4) var(--hc-space-5); white-space: pre-wrap; font-size: var(--hc-text-sm); line-height: 1.6;">{{ $reply->message }}</div>
                </x-card>
            @endforeach

            @if(!$ticket->isClosed())
                <x-card header="Répondre">
                    <form method="POST" action="{{ route('client.tickets.reply', $ticket) }}">
                        @csrf
                        <textarea name="message" class="hc-textarea" rows="6" required minlength="2" placeholder="Votre message..."></textarea>
                        <div style="margin-top: var(--hc-space-3); display: flex; justify-content: flex-end;">
                            <x-button type="submit" variant="primary">
                                <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                                Envoyer
                            </x-button>
                        </div>
                    </form>
                </x-card>
            @else
                <x-card>
                    <div style="text-align: center; padding: var(--hc-space-4); color: var(--hc-text-muted);">
                        <i data-lucide="lock" style="width: 24px; height: 24px; margin-bottom: var(--hc-space-2);"></i>
                        <p style="margin: 0;">Ce ticket est fermé.</p>
                    </div>
                </x-card>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-4);">
            <x-card header="Informations">
                <dl style="margin: 0; font-size: var(--hc-text-sm);">
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">N° de ticket</dt>
                        <dd style="margin: 0; font-family: var(--hc-font-mono); font-weight: 500;">{{ $ticket->ticket_number }}</dd>
                    </div>
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Catégorie</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $ticket->category?->name ?? '—' }}</dd>
                    </div>
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Priorité</dt>
                        <dd style="margin: 0;">
                            <x-badge :variant="match($ticket->priority) {
                                'urgent' => 'danger',
                                'high' => 'warning',
                                'medium' => 'info',
                                'low' => 'neutral',
                                default => 'neutral'
                            }">{{ ucfirst($ticket->priority) }}</x-badge>
                        </dd>
                    </div>
                    @if($ticket->service)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Service concerné</dt>
                            <dd style="margin: 0; font-weight: 500;">
                                <a href="{{ route('client.services.show', $ticket->service) }}" style="color: var(--hc-primary); text-decoration: none;">{{ $ticket->service->name }}</a>
                            </dd>
                        </div>
                    @endif
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Créé le</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($ticket->closed_at)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Fermé le</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ $ticket->closed_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </x-card>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .hc-detail-grid { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection