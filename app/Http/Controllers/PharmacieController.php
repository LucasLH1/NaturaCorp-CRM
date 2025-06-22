<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\GeocodageService;
use Illuminate\Support\Facades\Storage;

class PharmacieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pharmacies = Pharmacie::with('commercial')->get();
        $commerciaux = User::role('commercial')->get();

        return view('pharmacies.index', [
            'pharmacies' => $pharmacies,
            'commerciaux' => $commerciaux,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pharmacies.create');
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

        return view('pharmacies.show', compact('pharmacy'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pharmacie $pharmacie)
    {
        return view('pharmacies.edit', compact('pharmacie'));
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

        if ($adresseModifiee) {
            app(GeocodageService::class)->geocoder($pharmacie);
            $pharmacie->save();
        }
        return redirect()->back()->with('success', 'Pharmacie mise à jour avec succès.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pharmacie $pharmacy)
    {
        // Supprimer documents joints associés
        foreach ($pharmacy->documents as $doc) {
            Storage::delete('public/' . $doc->chemin);
            $doc->delete();
        }

        // Supprimer les commandes
        $pharmacy->commandes()->delete();

        // Supprimer la pharmacie
        $pharmacy->delete();

        return redirect()->route('pharmacies.index')->with('success', 'Pharmacie supprimée avec succès.');
    }


}
