<form method="POST" action="{{ route('pharmacies.update', $pharmacy) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informations générales</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
        @foreach ([
            'nom' => 'Nom',
            'siret' => 'SIRET',
            'email' => 'Email',
            'telephone' => 'Téléphone',
            'adresse' => 'Adresse',
            'code_postal' => 'Code postal',
            'ville' => 'Ville',
            'derniere_prise_contact' => 'Dernière prise de contact',
        ] as $field => $label)
            <div>
                <label class="block font-medium mb-1">{{ $label }}</label>
                <input
                    name="{{ $field }}"
                    type="{{ $field === 'email' ? 'email' : ($field === 'derniere_prise_contact' ? 'date' : 'text') }}"
                    class="w-full border rounded p-2 bg-white"
                    value="{{ old($field, $pharmacy->$field) }}"
                >
            </div>
        @endforeach

        <div>
            <label class="block font-medium mb-1">Statut</label>
            <select name="statut" class="w-full border rounded p-2 bg-white">
                <option value="prospect" @selected($pharmacy->statut === 'prospect')>Prospect</option>
                <option value="client_actif" @selected($pharmacy->statut === 'client_actif')>Client actif</option>
                <option value="client_inactif" @selected($pharmacy->statut === 'client_inactif')>Client inactif</option>
            </select>
        </div>
    </div>

    <div class="flex justify-between items-center pt-4 border-t">
        <form method="POST" action="{{ route('pharmacies.destroy', $pharmacy) }}" onsubmit="return confirm('Supprimer définitivement cette pharmacie ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Supprimer définitivement
            </button>
        </form>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Enregistrer
        </button>
    </div>
</form>
