@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    @if($customHtml && $customCss)
        {{-- Page d'accueil personnalisée --}}
        <style>{!! $customCss !!}</style>
        <div class="custom-homepage-wrapper">{!! $customHtml !!}</div>
    @else
        {{-- Dashboard par défaut --}}

        {{-- Stats principales --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--hc-space-4); margin-bottom: var(--hc-space-8);">
            <x-stat label="Clients" :value="$stats['total_clients'] ?? 0" />
            <x-stat label="Services actifs" :value="$stats['active_services'] ?? 0" />
            <x-stat label="Revenu ce mois" :value="number_format($stats['monthly_revenue'] ?? 0, 2) . ' €'" />
            <x-stat label="Tickets ouverts" :value="$stats['open_tickets'] ?? 0" />
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6); margin-bottom: var(--hc-space-8);">
            {{-- Chart revenus --}}
            <x-card header="Revenus (30 derniers jours)">
                <div style="height: 280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </x-card>

            {{-- Commandes récentes --}}
            <x-card header="Commandes récentes" :padding="false">
                <div style="padding: var(--hc-space-5);">
                    @forelse($recentOrders ?? [] as $order)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-3) 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--hc-border);' : '' }}">
                            <div>
                                <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ $order->order_number }}</div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $order->user?->first_name ?? '—' }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ number_format($order->total, 2) }} €</div>
                                <x-badge :variant="$order->status === 'completed' ? 'success' : 'warning'">{{ ucfirst($order->status) }}</x-badge>
                            </div>
                        </div>
                    @empty
                        <x-empty-state title="Aucune commande" icon="📭" />
                    @endforelse
                </div>
                <div style="padding: var(--hc-space-3) var(--hc-space-5); border-top: 1px solid var(--hc-border); text-align: center;">
                    <a href="{{ route('admin.orders.index') }}" style="color: var(--hc-primary); font-size: var(--hc-text-sm); font-weight: 500;">
                        Voir toutes les commandes →
                    </a>
                </div>
            </x-card>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-6);">
            {{-- Factures impayées --}}
            <x-card header="Factures impayées" :padding="false">
                <div style="padding: var(--hc-space-5);">
                    @forelse($recent_invoices ?? ($recentInvoices ?? []) as $invoice)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-3) 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--hc-border);' : '' }}">
                            <div>
                                <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ $invoice->invoice_number }}</div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $invoice->user?->first_name ?? '—' }} · échéance {{ $invoice->due_date?->format('d/m/Y') ?? '—' }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 600; font-size: var(--hc-text-sm); color: var(--hc-danger);">{{ number_format($invoice->balance ?? 0, 2) }} €</div>
                            </div>
                        </div>
                    @empty
                        <x-empty-state title="Aucune facture impayée" icon="✅" />
                    @endforelse
                </div>
                <div style="padding: var(--hc-space-3) var(--hc-space-5); border-top: 1px solid var(--hc-border); text-align: center;">
                    <a href="{{ route('admin.invoices.index') }}" style="color: var(--hc-primary); font-size: var(--hc-text-sm); font-weight: 500;">
                        Voir toutes les factures →
                    </a>
                </div>
            </x-card>

            {{-- Tickets récents --}}
            <x-card header="Tickets récents" :padding="false">
                <div style="padding: var(--hc-space-5);">
                    @forelse($recentTickets ?? [] as $ticket)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-3) 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--hc-border);' : '' }}">
                            <div style="flex: 1; min-width: 0; margin-right: var(--hc-space-3);">
                                <div style="font-weight: 600; font-size: var(--hc-text-sm); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ticket->subject }}</div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $ticket->user?->first_name ?? '—' }}</div>
                            </div>
                            <x-badge :variant="$ticket->priority === 'urgent' ? 'danger' : 'info'">
                                {{ ucfirst($ticket->priority ?? 'normal') }}
                            </x-badge>
                        </div>
                    @empty
                        <x-empty-state title="Aucun ticket ouvert" icon="✅" />
                    @endforelse
                </div>
                <div style="padding: var(--hc-space-3) var(--hc-space-5); border-top: 1px solid var(--hc-border); text-align: center;">
                    <a href="{{ route('admin.tickets.index') }}" style="color: var(--hc-primary); font-size: var(--hc-text-sm); font-weight: 500;">
                        Voir tous les tickets →
                    </a>
                </div>
            </x-card>
        </div>

        <style>
        @media (max-width: 1024px) {
            main > div[style*="grid-template-columns: 2fr 1fr"],
            main > div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
        </style>
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