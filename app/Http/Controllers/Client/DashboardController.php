<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();

            // Stats avec vérifications
            $stats = [
                'active_services' => $user->services()->where('status', 'active')->count(),
                'pending_orders' => $user->orders()->where('status', 'pending')->count(),
                'unpaid_invoices' => $user->invoices()->whereIn('status', ['unpaid', 'partially_paid'])->count(),
                'open_tickets' => $user->tickets()->whereIn('status', ['open', 'in_progress', 'waiting_customer', 'waiting_staff'])->count(),
                'balance' => $user->balance ?? 0,
            ];

            // Services récents sans relations complexes
            $recentServices = $user->services()
                ->latest()
                ->limit(5)
                ->get();

            // Factures récentes
            $recentInvoices = $user->invoices()
                ->latest()
                ->limit(5)
                ->get();

            // Tickets récents
            $recentTickets = $user->tickets()
                ->latest()
                ->limit(5)
                ->get();

            $activities = [];

            return view('client.dashboard', compact(
                'stats',
                'recentServices',
                'recentInvoices',
                'recentTickets',
                'activities'
            ));
            
        } catch (\Exception $e) {
            // Log l'erreur
            \Log::error('Dashboard Error: ' . $e->getMessage());
            
            // Retour basique sans données
            return view('client.dashboard', [
                'stats' => [
                    'active_services' => 0,
                    'pending_orders' => 0,
                    'unpaid_invoices' => 0,
                    'open_tickets' => 0,
                    'balance' => 0,
                ],
                'recentServices' => collect([]),
                'recentInvoices' => collect([]),
                'recentTickets' => collect([]),
                'activities' => [],
            ]);
        }
    }

    protected function getRecentActivities(): array
    {
        $user = auth()->user();
        $activities = [];

        // Recent services
        $user->services()->latest()->take(3)->each(function ($service) use (&$activities) {
            $activities[] = [
                'type' => 'service',
                'icon' => 'server',
                'title' => 'Service créé',
                'description' => $service->name,
                'date' => $service->created_at,
            ];
        });

        // Recent invoices
        $user->invoices()->latest()->take(3)->each(function ($invoice) use (&$activities) {
            $activities[] = [
                'type' => 'invoice',
                'icon' => 'file-text',
                'title' => 'Facture générée',
                'description' => $invoice->invoice_number,
                'date' => $invoice->created_at,
            ];
        });

        // Recent tickets
        $user->tickets()->latest()->take(3)->each(function ($ticket) use (&$activities) {
            $activities[] = [
                'type' => 'ticket',
                'icon' => 'message-circle',
                'title' => 'Ticket ouvert',
                'description' => $ticket->subject,
                'date' => $ticket->created_at,
            ];
        });

        // Sort by date
        usort($activities, fn($a, $b) => $b['date'] <=> $a['date']);

        return array_slice($activities, 0, 10);
    }
}
