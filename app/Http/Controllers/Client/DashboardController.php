<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'active_services' => $user->services()->active()->count(),
            'pending_orders' => $user->orders()->pending()->count(),
            'unpaid_invoices' => $user->invoices()->unpaid()->count(),
            'open_tickets' => $user->tickets()->open()->count(),
            'balance' => $user->balance,
        ];

        $recentServices = $user->services()
            ->with('product')
            ->latest()
            ->take(5)
            ->get();

        $recentInvoices = $user->invoices()
            ->latest()
            ->take(5)
            ->get();

        $recentTickets = $user->tickets()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        $activities = $this->getRecentActivities();

        return view('client.dashboard', compact(
            'stats',
            'recentServices',
            'recentInvoices',
            'recentTickets',
            'activities'
        ));
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
