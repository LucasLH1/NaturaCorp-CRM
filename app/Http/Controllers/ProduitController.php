<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produits = Produit::orderBy('nom')->get();
        return view('produits.index', compact('produits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'tarif_unitaire' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Produit::create($data);
        return redirect()->route('produits.index')->with('success', 'Produit ajouté.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produit $produit)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'tarif_unitaire' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $produit->update($data);
        return redirect()->route('produits.index')->with('success', 'Produit mis à jour.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit)
    {
        $produit->delete();
        return redirect()->route('produits.index')->with('success', 'Produit supprimé.');
    }
}
