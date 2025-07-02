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
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $commandes = Commande::with(['pharmacie', 'user', 'produits'])->latest()->get();
            $pharmacies = Pharmacie::with('zone')->get();
        } elseif ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');

            $commandes = Commande::with(['pharmacie', 'user', 'produits'])
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'pharmacie_id'   => 'required|exists:pharmacies,id',
            'date_commande'  => 'required|date',
            'statut'         => 'required|in:' . implode(',', array_column(StatutCommande::cases(), 'value')),
            'observations'   => 'nullable|string',
            'produits'       => 'required|array',
            'produits.*.id'  => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        $data['user_id'] = auth()->id();

        $commande = Commande::create([
            'pharmacie_id'  => $data['pharmacie_id'],
            'user_id'       => $data['user_id'],
            'date_commande' => $data['date_commande'],
            'statut'        => $data['statut'],
            'observations'  => $data['observations'] ?? '',
        ]);

        foreach ($data['produits'] as $p) {
            $produit = Produit::findOrFail($p['id']);

            if ($produit->stock < $p['quantite']) {
                return back()->withErrors(['produits' => "Stock insuffisant pour le produit « {$produit->nom} »."]);
            }

            $commande->produits()->attach($produit->id, [
                'quantite' => $p['quantite'],
                'prix_unitaire' => $p['prix_unitaire'],
            ]);

            $produit->decrement('stock', $p['quantite']);
        }

        JournalService::log('create', "Création d'une commande #{$commande->id} pour la pharmacie #{$commande->pharmacie_id}");

        $utilisateurs = User::role(['commercial', 'logistique', 'admin'])->get();

        foreach ($utilisateurs as $utilisateur) {
            NotificationInterne::create([
                'user_id' => $utilisateur->id,
                'titre' => 'Nouvelle commande créée',
                'contenu' => "Commande multiproduits pour la pharmacie « {$commande->pharmacie->nom} », créée par {$commande->user->name}.",
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

        return redirect()->back()->with('success', 'Commande multiproduits créée avec succès.');
    }

    public function update(Request $request, Commande $commande)
    {
        $data = $request->validate([
            'pharmacie_id'   => 'required|exists:pharmacies,id',
            'date_commande'  => 'nullable|date',
            'statut'         => 'required|in:' . implode(',', array_column(StatutCommande::cases(), 'value')),
            'observations'   => 'nullable|string',
            'produits'       => 'required|array',
            'produits.*.id'  => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        if (empty($data['date_commande'])) {
            $data['date_commande'] = $commande->date_commande;
        }

        // Remettre les stocks avant suppression des anciennes lignes pivot
        foreach ($commande->produits as $ancienProduit) {
            $ancienProduit->increment('stock', $ancienProduit->pivot->quantite);
        }

        $commande->produits()->detach();

        foreach ($data['produits'] as $p) {
            $produit = Produit::findOrFail($p['id']);

            if ($produit->stock < $p['quantite']) {
                return back()->withErrors(['produits' => "Stock insuffisant pour le produit « {$produit->nom} »."]);
            }

            $commande->produits()->attach($produit->id, [
                'quantite' => $p['quantite'],
                'prix_unitaire' => $p['prix_unitaire'],
            ]);

            $produit->decrement('stock', $p['quantite']);
        }

        $commande->update([
            'pharmacie_id'  => $data['pharmacie_id'],
            'date_commande' => $data['date_commande'],
            'statut'        => $data['statut'],
            'observations'  => $data['observations'] ?? '',
        ]);

        JournalService::log('update', "Modification d'une commande #{$commande->id} pour la pharmacie #{$commande->pharmacie_id}");

        return redirect()->back()->with('success', 'Commande mise à jour avec succès.');
    }

    public function destroy(Commande $commande)
    {
        $commande->load(['document', 'produits']);

        if ($commande->document) {
            $path = storage_path('app/public/' . $commande->document->chemin);
            if (file_exists($path)) {
                unlink($path);
            }
            $commande->document->delete();
        }

        foreach ($commande->produits as $produit) {
            $produit->increment('stock', $produit->pivot->quantite);
        }

        $commande->produits()->detach();
        $commande->delete();

        JournalService::log('delete', "Suppression d'une commande #{$commande->id}");

        return redirect()->back()->with('success', 'Commande supprimée avec succès.');
    }
}
