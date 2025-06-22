<?php

namespace App\Http\Controllers;

use App\Enums\StatutCommande;
use App\Models\Commande;
use App\Models\NotificationInterne;
use App\Models\Pharmacie;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentJoint;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Commande::with(['pharmacie', 'user'])->latest()->get();
        $pharmacies = Pharmacie::all();
        $statuts = StatutCommande::cases();

        return view('commandes.index', compact('commandes', 'pharmacies', 'statuts'));
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
        $data = $request->validate([
            'pharmacie_id' => 'required|exists:pharmacies,id',
            'date_commande' => 'required|date',
            'statut' => 'required|in:' . implode(',', array_column(StatutCommande::cases(), 'value')),
            'quantite' => 'required|integer|min:1',
            'tarif_unitaire' => 'required|numeric|min:0',
            'observations' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

        $commande = Commande::create($data);

        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            NotificationInterne::create([
                'user_id' => $admin->id,
                'titre' => 'Nouvelle commande créée',
                'contenu' => "Commande pour la pharmacie « {$commande->pharmacie->nom} », créée par {$commande->user->name}.",
                'est_lu' => false,
            ]);
        }

        $pdf = Pdf::loadView('pdfs.commande', ['commande' => $commande]);

        $filename = 'commande_' . $commande->id . '_' . now()->format('Ymd_His') . '.pdf';
        $path = 'documents/' . $filename;

        // Stockage
        Storage::disk('public')->put($path, $pdf->output());

        // Enregistrement du document joint
        DocumentJoint::create([
            'pharmacie_id'   => $commande->pharmacie_id,
            'commande_id'    => $commande->id,
            'nom_fichier'    => 'Commande PDF - ' . $commande->id,
            'chemin'         => $path,
            'type'           => 'rapport_commande',
        ]);

        return redirect()->back()->with('success', 'Commande créée avec succès.');
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
//        dd($request->all());
        $data = $request->validate([
            'pharmacie_id' => 'required|exists:pharmacies,id',
            'date_commande' => 'required|date',
            'statut' => 'required|in:' . implode(',', array_column(StatutCommande::cases(), 'value')),
            'quantite' => 'required|integer|min:1',
            'tarif_unitaire' => 'required|numeric|min:0',
            'observations' => 'nullable|string',
        ]);

        $commande->update($data);

        return redirect()->back()->with('success', 'Commande mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commande $commande)
    {
        // Supprimer le document PDF associé s'il existe
        if ($commande->document) {
            $path = storage_path('app/public/' . $commande->document->chemin);
            if (file_exists($path)) {
                unlink($path);
            }

            $commande->document->delete();
        }

        $commande->delete();

        return redirect()->back()->with('success', 'Commande supprimée avec succès.');
    }
}
