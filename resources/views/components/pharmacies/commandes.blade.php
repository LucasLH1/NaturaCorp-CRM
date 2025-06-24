<div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-semibold text-gray-700">Commandes associées</h3>
    <button @click="modalMode = 'create'; editingCommande = { pharmacie_id: {{ $pharmacy->id }} }; modalOpen = true"
            class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200">
        Nouvelle commande
    </button>
</div>

<table class="w-full text-sm border rounded overflow-hidden">
    <thead class="bg-gray-100 text-left">
    <tr>
        <th class="px-4 py-2">Date</th>
        <th class="px-4 py-2">Statut</th>
        <th class="px-4 py-2">Total</th>
        <th class="px-4 py-2 text-right">Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($pharmacy->commandes as $cmd)
        <tr class="border-t hover:bg-gray-50">
            <td class="px-4 py-2">{{ $cmd->date_commande }}</td>
            <td class="px-4 py-2">{{ $cmd->statut->label() }}</td>
            <td class="px-4 py-2">{{ number_format($cmd->quantite * $cmd->tarif_unitaire, 2, ',', ' ') }} €</td>
            <td class="px-4 py-2 text-right">
                <a href="{{ asset('storage/' . $cmd->document?->chemin) }}"
                   class="text-blue-600 hover:underline text-sm" target="_blank">
                    Voir PDF
                </a>

                <form method="POST" action="{{ route('commandes.destroy', $cmd) }}"
                      onsubmit="return confirm('Supprimer cette commande ?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline text-sm ml-4">Supprimer</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="px-4 py-2 text-gray-500 text-sm">Aucune commande.</td>
        </tr>
    @endforelse
    </tbody>
</table>

<x-commande-form-modal
    :pharmacies="[$pharmacy]"
    :statuts="\App\Enums\StatutCommande::cases()"
    :produits="$produits"
/>
