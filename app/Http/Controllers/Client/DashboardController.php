<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Contrôleur du tableau de bord client.
 * Affiche les statistiques et activités récentes du client connecté.
 */
class DashboardController extends Controller
{
    /**
     * Affiche le dashboard du client.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Ces données seront remplacées par de vraies requêtes
        // une fois les modèles créés
        $stats = [
            'active_services'   => 12,   // Service::where('user_id', $user->id)->active()->count()
            'pending_invoices'  => 3,    // Invoice::where('user_id', $user->id)->pending()->count()
            'open_tickets'      => 2,    // Ticket::where('user_id', $user->id)->open()->count()
            'domains'           => 8,    // Domain::where('user_id', $user->id)->count()
        ];

        return view('client.dashboard', compact('stats'));
    }
}
