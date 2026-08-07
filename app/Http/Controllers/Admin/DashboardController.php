<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Ces stats seront remplacées par de vraies requêtes DB
        $stats = [
            'revenue_month'   => 12480,
            'new_clients'     => 48,
            'active_services' => 324,
            'open_tickets'    => 7,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
