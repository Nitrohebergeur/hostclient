<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisation - Page d'Accueil</title>
    <style>
        {!! $css !!}
        
        /* Preview Frame Styles */
        body {
            margin: 0;
            padding: 0;
        }
        
        .preview-banner {
            background: #ffeb3b;
            color: #000;
            text-align: center;
            padding: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="preview-banner">
        👁️ MODE PRÉVISUALISATION - Les données affichées sont des exemples
    </div>
    
    @php
        // Données d'exemple pour la prévisualisation
        $stats = [
            'total_clients' => 127,
            'active_services' => 84,
            'pending_orders' => 12,
            'open_tickets' => 5,
            'unpaid_invoices' => 8,
            'monthly_revenue' => 15420.50,
            'today_signups' => 3,
        ];
    @endphp
    
    {!! $html !!}
</body>
</html>
