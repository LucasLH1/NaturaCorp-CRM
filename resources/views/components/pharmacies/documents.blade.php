<h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Documents joints</h3>

<table class="w-full text-sm border rounded overflow-hidden mb-4">
    <thead class="bg-gray-100 text-left">
    <tr>
        <th class="px-4 py-2">Nom</th>
        <th class="px-4 py-2">Type</th>
        <th class="px-4 py-2">Ajouté le</th>
        <th class="px-4 py-2 text-right">Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($pharmacy->documents as $doc)
        <tr class="border-t hover:bg-gray-50">
            <td class="px-4 py-2">{{ $doc->nom_fichier }}</td>
            <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $doc->type) }}</td>
            <td class="px-4 py-2">{{ $doc->created_at->format('d/m/Y') }}</td>
            <td class="px-4 py-2 text-right">
                <a href="{{ asset('storage/' . $doc->chemin) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                    Télécharger
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="px-4 py-2 text-gray-500 text-sm">Aucun document.</td>
        </tr>
    @endforelse
    </tbody>
</table>

<form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm items-end border-t pt-4">
    @csrf
    <input type="hidden" name="pharmacie_id" value="{{ $pharmacy->id }}">

    <div>
        <label class="block text-gray-600 mb-1">Type</label>
        <select name="type" class="w-full border rounded p-2 bg-white" required>
            <option value="">— Sélectionner —</option>
            <option value="contrat">Contrat</option>
            <option value="devis">Devis</option>
            <option value="document_reglementaire">Document réglementaire</option>
            <option value="autre">Autre</option>
        </select>
    </div>

    <div>
        <label class="block text-gray-600 mb-1">Fichier</label>
        <input type="file" name="fichier" class="w-full border rounded p-2 bg-white" required>
    </div>

    <div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
            Ajouter
        </button>
    </div>
</form>
