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
        $validated = $request->validate([
            'pharmacie_id' => 'required|exists:pharmacies,id',
            'nom_fichier' => 'required|string',
            'chemin' => 'required|string',
            'type' => 'nullable|string',
        ]);

        DocumentJoint::create($validated);
        return redirect()->route('documents.index');
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
