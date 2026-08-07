@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    @if($customHtml && $customCss)
        {{-- Page d'accueil personnalisée --}}
        <style>{!! $customCss !!}</style>
        <div class="custom-homepage-wrapper">{!! $customHtml !!}</div>
    @else
        {{-- Dashboard par défaut --}}

        {{-- Stats principales (ClientXMS-style avec icônes) --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-8);">
            <x-stat label="Clients" :value="$stats['total_clients'] ?? 0" icon="users" color="primary"
                :delta="($stats['clients_growth'] ?? null)" :deltaType="($stats['clients_growth'] ?? 0) >= 0 ? 'positive' : 'negative'" />
            <x-stat label="Services actifs" :value="$stats['active_services'] ?? 0" icon="server" color="success" />
            <x-stat label="Revenu ce mois" :value="number_format($stats['monthly_revenue'] ?? 0, 2) . ' €'" icon="trending-up" color="info" />
            <x-stat label="Tickets ouverts" :value="$stats['open_tickets'] ?? 0" icon="message-circle" color="warning" />
        </div>

        <div class="hc-info-grid" style="margin-bottom: var(--hc-space-8);">
            {{-- Chart revenus --}}
            <x-card>
                <x-slot:header>Revenus (30 derniers jours)</x-slot:header>
                <div style="height: 280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </x-card>

            {{-- Commandes récentes --}}
            <x-card header="Commandes récentes" :padding="false">
                @forelse($recentOrders ?? [] as $order)
                    <div class="hc-activity-item">
                        <div class="hc-activity-icon" style="background: var(--hc-info-50); color: var(--hc-info);">
                            <i data-lucide="shopping-bag"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: var(--hc-space-2);">
                                <a href="{{ route('admin.orders.show', $order) }}" style="font-weight: 600; font-size: var(--hc-text-sm); color: var(--hc-text); text-decoration: none;">
                                    {{ $order->order_number }}
                                </a>
                                <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ number_format($order->total, 2) }} €</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2px;">
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $order->user?->first_name ?? '—' }} {{ $order->user?->last_name ?? '' }}</div>
                                <x-badge :variant="match($order->status) {
                                    'completed' => 'success',
                                    'pending' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'neutral'
                                }">{{ ucfirst($order->status ?? 'pending') }}</x-badge>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="hc-empty-state">
                        <div class="hc-empty-icon">📭</div>
                        <h3>Aucune commande</h3>
                        <p>Les nouvelles commandes apparaîtront ici.</p>
                    </div>
                @endforelse
                <div style="padding: var(--hc-space-3) var(--hc-space-5); border-top: 1px solid var(--hc-border); text-align: center;">
                    <a href="{{ route('admin.orders.index') }}" style="color: var(--hc-primary); font-size: var(--hc-text-sm); font-weight: 500; text-decoration: none;">
                        Voir toutes les commandes →
                    </a>
                </div>
            </x-card>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-6);">
            {{-- Factures impayées --}}
            <x-card header="Factures impayées" :padding="false">
                @forelse($recent_invoices ?? ($recentInvoices ?? []) as $invoice)
                    <div class="hc-activity-item">
                        <div class="hc-activity-icon" style="background: var(--hc-danger-50); color: var(--hc-danger);">
                            <i data-lucide="file-warning"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: var(--hc-space-2);">
                                <a href="{{ route('admin.invoices.show', $invoice) }}" style="font-family: var(--hc-font-mono); font-weight: 600; font-size: var(--hc-text-sm); color: var(--hc-text); text-decoration: none;">
                                    {{ $invoice->invoice_number }}
                                </a>
                                <div style="font-weight: 700; font-size: var(--hc-text-sm); color: var(--hc-danger);">{{ number_format($invoice->balance ?? 0, 2) }} €</div>
                            </div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: 2px;">
                                {{ $invoice->user?->first_name ?? '—' }} · échéance {{ $invoice->due_date?->format('d/m/Y') ?? '—' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="hc-empty-state">
                        <div class="hc-empty-icon">✅</div>
                        <h3>Aucune facture impayée</h3>
                        <p>Toutes les factures sont à jour.</p>
                    </div>
                @endforelse
                <div style="padding: var(--hc-space-3) var(--hc-space-5); border-top: 1px solid var(--hc-border); text-align: center;">
                    <a href="{{ route('admin.invoices.index') }}" style="color: var(--hc-primary); font-size: var(--hc-text-sm); font-weight: 500; text-decoration: none;">
                        Voir toutes les factures →
                    </a>
                </div>
            </x-card>

            {{-- Tickets récents --}}
            <x-card header="Tickets récents" :padding="false">
                @forelse($recentTickets ?? [] as $ticket)
                    <div class="hc-activity-item">
                        <div class="hc-activity-icon" style="background: {{ $ticket->priority === 'urgent' ? 'var(--hc-danger-50)' : 'var(--hc-warning-50)' }}; color: {{ $ticket->priority === 'urgent' ? 'var(--hc-danger)' : 'var(--hc-warning)' }};">
                            <i data-lucide="message-circle"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: var(--hc-space-2);">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" style="font-weight: 600; font-size: var(--hc-text-sm); color: var(--hc-text); text-decoration: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                    {{ $ticket->subject }}
                                </a>
                                <x-badge :variant="match($ticket->priority) {
                                    'urgent' => 'danger',
                                    'high' => 'warning',
                                    'low' => 'neutral',
                                    default => 'info'
                                }">{{ ucfirst($ticket->priority ?? 'normal') }}</x-badge>
                            </div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: 2px;">
                                {{ $ticket->user?->first_name ?? '—' }} {{ $ticket->user?->last_name ?? '' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="hc-empty-state">
                        <div class="hc-empty-icon">✅</div>
                        <h3>Aucun ticket ouvert</h3>
                        <p>La file d'assistance est vide.</p>
                    </div>
                @endforelse
                <div style="padding: var(--hc-space-3) var(--hc-space-5); border-top: 1px solid var(--hc-border); text-align: center;">
                    <a href="{{ route('admin.tickets.index') }}" style="color: var(--hc-primary); font-size: var(--hc-text-sm); font-weight: 500; text-decoration: none;">
                        Voir tous les tickets →
                    </a>
                </div>
            </x-card>
        </div>
    @endif
@endsection

@stack('scripts')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($revenueChart['labels'] ?? []),
            datasets: [{
                label: 'Revenus',
                data: @json($revenueChart['data'] ?? []),
                borderColor: '#0066ff',
                backgroundColor: 'rgba(0, 102, 255, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2,
                pointRadius: 3,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: (v) => v + ' €' },
                    grid: { color: '#e2e8f0' }
                },
                x: { grid: { display: false } }
            }
        }
    });
})();
</script>
@endpush