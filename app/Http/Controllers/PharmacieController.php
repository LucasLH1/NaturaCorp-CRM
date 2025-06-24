<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\User;
use App\Services\JournalService;
use Illuminate\Http\Request;
use App\Services\GeocodageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PharmacieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        //on ne récupère que les pharmacies qui ont la même zone assignée au commercial
        if ($user->hasRole('admin')) {
            $pharmacies = Pharmacie::with('zone')->get();
        } elseif ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');
            $pharmacies = Pharmacie::with('zone')
                ->whereIn('zone_id', $zoneIds)
                ->get();
        } else {
            abort(403);
        }

        $commerciaux = User::role('commercial')->get();

        return view('pharmacies.index', [
            'pharmacies' => $pharmacies,
            'commerciaux' => $commerciaux,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'siret' => 'nullable|string|unique:pharmacies,siret',
            'email' => 'nullable|email',
            'telephone' => 'nullable|string',
            'adresse' => 'required|string',
            'code_postal' => 'required|string',
            'ville' => 'required|string',
            'statut' => 'required|in:prospect,client_actif,client_inactif',
            'derniere_prise_contact' => 'nullable|date',
            'commercial_id' => 'nullable|exists:users,id',
        ]);


        $pharmacie = Pharmacie::create($validated);

        app(GeocodageService::class)->geocoder($pharmacie);
        $pharmacie->save();

        JournalService::log('create', "Création d'une pharmacie #{$pharmacie->id}");


        return redirect()->route('pharmacies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pharmacie $pharmacy)
    {
        // On charge les relations nécessaires
        $pharmacy->load([
            'commercial',        // User rattaché en tant que commercial
            'documents',         // Documents joints liés à la pharmacie
            'commandes'          // Commandes liées à cette pharmacie
        ]);

        $produits = Produit::all();

        return view('pharmacies.show', compact('pharmacy','produits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pharmacie = Pharmacie::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string',
            'siret' => 'nullable|string|unique:pharmacies,siret,' . $pharmacie->id,
            'email' => 'nullable|email',
            'telephone' => 'nullable|string',
            'adresse' => 'required|string',
            'code_postal' => 'required|string',
            'ville' => 'required|string',
            'statut' => 'required|in:prospect,client_actif,client_inactif',
            'derniere_prise_contact' => 'nullable|date',
            'commercial_id' => 'nullable|exists:users,id',
        ]);

        $adresseModifiee =
            $pharmacie->adresse !== $validated['adresse'] ||
            $pharmacie->ville !== $validated['ville'] ||
            $pharmacie->code_postal !== $validated['code_postal'];

        $pharmacie->update($validated);
        JournalService::log('update', "Modification d'une pharmacie #{$pharmacie->id}");

        if ($adresseModifiee) {
            app(GeocodageService::class)->geocoder($pharmacie);
            $pharmacie->save();
        }
        return redirect()->back()->with('success', 'Pharmacie mise à jour avec succès.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pharmacie = Pharmacie::findOrFail($id);

        foreach ($pharmacie->documents as $doc) {
            Storage::delete('public/' . $doc->chemin);
            $doc->delete();
        }

        $pharmacie->commandes()->delete();
        $pharmacie->delete();

        JournalService::log('delete', "Suppression d'une pharmacie #{$pharmacie->id}");

        return redirect()->route('pharmacies.index')->with('success', 'Pharmacie supprimée avec succès.');
    }

}
