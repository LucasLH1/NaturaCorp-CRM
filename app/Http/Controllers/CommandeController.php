<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Pharmacie;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('commandes.index', [
            'commandes' => Commande::with('pharmacie')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('commandes.create', [
            'pharmacies' => Pharmacie::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pharmacie_id' => 'required|exists:pharmacies,id',
            'date_commande' => 'required|date',
            'statut' => 'required|in:validée,en_cours,livrée',
            'quantite' => 'required|integer',
            'tarif_unitaire' => 'required|numeric',
            'observations' => 'nullable|string',
        ]);

        Commande::create($validated);
        return redirect()->route('commandes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commande $commande)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commande $commande)
    {
        return view('commandes.edit', [
            'commande' => $commande,
            'pharmacies' => Pharmacie::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commande $commande)
    {
        $validated = $request->validate([
            'pharmacie_id' => 'required|exists:pharmacies,id',
            'date_commande' => 'required|date',
            'statut' => 'required|in:validée,en_cours,livrée',
            'quantite' => 'required|integer',
            'tarif_unitaire' => 'required|numeric',
            'observations' => 'nullable|string',
        ]);

        $commande->update($validated);
        return redirect()->route('commandes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commande $commande)
    {
        $commande->delete();
        return redirect()->route('commandes.index');
    }
}
