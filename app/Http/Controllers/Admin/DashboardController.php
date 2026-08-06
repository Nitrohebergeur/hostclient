<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => User::role('client')->count(),
            'active_services' => Service::active()->count(),
            'pending_orders' => Order::pending()->count(),
            'open_tickets' => Ticket::open()->count(),
            'unpaid_invoices' => Invoice::unpaid()->count(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'today_signups' => User::whereDate('created_at', today())->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentTickets = Ticket::with(['user', 'category'])
            ->open()
            ->latest()
            ->take(5)
            ->get();

        $revenueChart = $this->getRevenueChartData();
        $servicesChart = $this->getServicesChartData();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'recentTickets',
            'revenueChart',
            'servicesChart'
        ));
    }

    protected function getMonthlyRevenue(): float
    {
        return Transaction::where('type', 'payment')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
    }

    protected function getRevenueChartData(): array
    {
        $data = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('type', 'payment')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(fn($d) => date('M d', strtotime($d))),
            'data' => $data->pluck('total'),
        ];
    }

    protected function getServicesChartData(): array
    {
        $services = Service::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $services->pluck('status'),
            'data' => $services->pluck('count'),
        ];
    }
}
