<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use App\Models\User;
use Illuminate\Http\Request;

class CarteController extends Controller
{
    public function index()
    {
        $pharmacies = Pharmacie::with('commercial')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $commerciaux = User::role('commercial')->pluck('name');
        $villes = Pharmacie::distinct()->pluck('ville');
        $statuts = ['client_actif', 'client_inactif', 'prospect'];

        return view('carte.index', compact('pharmacies', 'commerciaux', 'villes', 'statuts'));
    }

}
