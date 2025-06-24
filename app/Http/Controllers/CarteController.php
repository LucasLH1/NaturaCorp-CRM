<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CarteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Base de la requête
        $query = Pharmacie::with('commercial')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        // Si commercial → filtrer par zones
        if ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');
            $query->whereIn('zone_id', $zoneIds);
        }

        // Si autre rôle sans droit, refuser l'accès
        elseif (!$user->hasRole('admin')) {
            abort(403);
        }

        $pharmacies = $query->get();

        $commerciaux = User::role('commercial')->pluck('name');
        $villes = Pharmacie::distinct()->pluck('ville');
        $statuts = ['client_actif', 'client_inactif', 'prospect'];

        return view('carte.index', compact('pharmacies', 'commerciaux', 'villes', 'statuts'));
    }
}
