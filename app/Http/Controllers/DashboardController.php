<?php

namespace App\Http\Controllers;

use App\Models\{Commande, NotificationInterne, Pharmacie, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pharmacieCount' => Pharmacie::count(),
            'prospects' => Pharmacie::where('statut', 'prospect')->count(),
            'clientsActifs' => Pharmacie::where('statut', 'client_actif')->count(),
            'clientsInactifs' => Pharmacie::where('statut', 'client_inactif')->count(),

            'commandeCount' => Commande::count(),
            'totalCA' => Commande::sum(DB::raw('quantite * tarif_unitaire')),
            'commandeMoyenne' => round(Commande::avg(DB::raw('quantite * tarif_unitaire')), 2),

            'commandesEnCours' => Commande::where('statut', 'en_cours')->count(),
            'commandesLivrees' => Commande::where('statut', 'livree')->count(),
            'commandesValidees' => Commande::where('statut', 'validee')->count(),

            'utilisateurs' => User::count(),
            'notificationsNonLues' => NotificationInterne::where('est_lu', false)->count(),
        ];

        return view('dashboard', compact('stats'));
    }

    public function chartCommandes()
    {
        $data = Commande::selectRaw("DATE_TRUNC('month', date_commande) as mois, COUNT(*) as total")
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        return response()->json([
            'labels' => $data->map(fn($r) => date('M Y', strtotime($r->mois))),
            'data' => $data->pluck('total'),
        ]);
    }
}
