<?php

namespace App\Http\Controllers;

use App\Enums\StatutCommande;
use App\Models\Commande;
use App\Models\NotificationInterne;
use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\User;
use App\Services\JournalService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentJoint;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $commandes = Commande::with(['pharmacie', 'user'])->latest()->get();
            $pharmacies = Pharmacie::with('zone')->get();
        } elseif ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');

            // On ne récupère que les commandes dont la pharmacie appartient à une zone du commercial
            $commandes = Commande::with(['pharmacie', 'user'])
                ->whereHas('pharmacie', fn ($query) => $query->whereIn('zone_id', $zoneIds))
                ->latest()
                ->get();

            $pharmacies = Pharmacie::with('zone')
                ->whereIn('zone_id', $zoneIds)
                ->get();
        } else {
            abort(403);
        }

        $statuts = StatutCommande::cases();
        $produits = Produit::all();

        return view('commandes.index', compact('commandes', 'pharmacies', 'statuts', 'produits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'pharmacie_id'     => 'required|exists:pharmacies,id',
            'produit_id'       => 'required|exists:produits,id',
            'date_commande'    => 'required|date',
            'statut'           => 'required|in:' . implode(',', array_column(StatutCommande::cases(), 'value')),
            'quantite'         => 'required|integer|min:1',
            'tarif_unitaire'   => 'required|numeric|min:0',
            'observations'     => 'nullable|string',
        ]);

        $produit = Produit::findOrFail($data['produit_id']);

        if ($produit->stock < $data['quantite']) {
            return back()->withErrors(['quantite' => 'Stock insuffisant pour ce produit.']);
        }

        $data['user_id'] = auth()->id();

        $commande = Commande::create($data);

        // Décrémenter le stock
        $produit->decrement('stock', $data['quantite']);

        JournalService::log('create', "Création d'une commande #{$commande->id} pour la pharmacie #{$commande->pharmacie_id}");

        $utilisateurs = User::role(['commercial', 'logistique', 'admin'])->get();

        foreach ($utilisateurs as $utilisateur) {
            NotificationInterne::create([
                'user_id' => $utilisateur->id,
                'titre' => 'Nouvelle commande créée',
                'contenu' => "Commande pour la pharmacie « {$commande->pharmacie->nom} », créée par {$commande->user->name}.",
                'est_lu' => false,
            ]);
        }

        $pdf = Pdf::loadView('pdfs.commande', ['commande' => $commande]);
        $filename = 'commande_' . $commande->id . '_' . now()->format('Ymd_His') . '.pdf';
        $path = 'documents/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        DocumentJoint::create([
            'pharmacie_id' => $commande->pharmacie_id,
            'commande_id'  => $commande->id,
            'nom_fichier'  => 'Commande PDF - ' . $commande->id,
            'chemin'       => $path,
            'type'         => 'rapport_commande',
        ]);

        return redirect()->back()->with('success', 'Commande créée avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commande $commande)
    {
        $data = $request->validate([
            'pharmacie_id'     => 'required|exists:pharmacies,id',
            'produit_id'       => 'required|exists:produits,id',
            'date_commande'    => 'nullable|date',
            'statut'           => 'required|in:' . implode(',', array_column(StatutCommande::cases(), 'value')),
            'quantite'         => 'required|integer|min:1',
            'tarif_unitaire'   => 'required|numeric|min:0',
            'observations'     => 'nullable|string',
        ]);

        if (empty($data['date_commande'])) {
            $data['date_commande'] = $commande->date_commande;
        }

        $produit = Produit::findOrFail($data['produit_id']);

        $quantiteDisponible = $produit->stock + $commande->quantite;

        if ($data['quantite'] > $quantiteDisponible) {
            return back()->withErrors(['quantite' => 'Stock insuffisant pour cette quantité.']);
        }

        // Mise à jour du stock si la quantité change
        if ($commande->quantite != $data['quantite']) {
            $difference = $data['quantite'] - $commande->quantite;
            $produit->stock -= $difference;
            $produit->save();
        }

        $commande->update($data);

        JournalService::log('update', "Modification d'une commande #{$commande->id} pour la pharmacie #{$commande->pharmacie_id}");

        return redirect()->back()->with('success', 'Commande mise à jour avec succès.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commande $commande)
    {
        // Charger les relations nécessaires
        $commande->load(['document', 'produit']);

        // Supprimer le fichier PDF associé, s'il existe
        if ($commande->document) {
            $path = storage_path('app/public/' . $commande->document->chemin);

            if (file_exists($path)) {
                unlink($path);
            }

            $commande->document->delete();
        }

        // Remettre en stock la quantité commandée
        if ($commande->produit) {
            $commande->produit->increment('stock', $commande->quantite);
        }

        // Supprimer la commande
        $commande->delete();

        JournalService::log('delete', "Suppression d'une commande #{$commande->id}");

        return redirect()->back()->with('success', 'Commande supprimée avec succès.');
    }

}
