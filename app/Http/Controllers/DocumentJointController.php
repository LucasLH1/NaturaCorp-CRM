<?php

namespace App\Http\Controllers;

use App\Models\DocumentJoint;
use App\Models\Pharmacie;
use Illuminate\Http\Request;

class DocumentJointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('documents.index', [
            'documents' => DocumentJoint::with('pharmacie')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('documents.create', [
            'pharmacies' => Pharmacie::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pharmacie_id' => 'required|exists:pharmacies,id',
            'type' => 'required|string',
            'fichier' => 'required|file|max:5120', // 5MB
        ]);

        $fichier = $request->file('fichier');
        $nom = 'Doc_' . uniqid() . '.' . $fichier->getClientOriginalExtension();
        $chemin = $fichier->storeAs('documents', $nom, 'public');

        DocumentJoint::create([
            'pharmacie_id' => $request->pharmacie_id,
            'nom_fichier' => $fichier->getClientOriginalName(),
            'chemin' => $chemin,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Document ajouté avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show(DocumentJoint $documentJoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentJoint $documentJoint)
    {
        return view('documents.edit', [
            'document' => $documentJoint,
            'pharmacies' => Pharmacie::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentJoint $documentJoint)
    {
        $validated = $request->validate([
            'pharmacie_id' => 'required|exists:pharmacies,id',
            'nom_fichier' => 'required|string',
            'chemin' => 'required|string',
            'type' => 'nullable|string',
        ]);

        $documentJoint->update($validated);
        return redirect()->route('documents.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentJoint $documentJoint)
    {
        $documentJoint->delete();
        return redirect()->route('documents.index');
    }
}
