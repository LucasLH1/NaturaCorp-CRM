<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Pharmacies</h2>
    </x-slot>

    <script>
        window.pharmaciesFromLaravel = @json($pharmacies);
    </script>

    <div class="p-6 bg-white shadow rounded"
         x-data="initPharmacieTable(window.pharmaciesFromLaravel)">

        <div class="flex justify-between items-center mb-4">
            <input type="text" x-model="search" placeholder="Rechercher..."
                   class="w-full max-w-xs border rounded px-3 py-2">
            <button @click="modalMode = 'create'; editingPharmacie = {}; modalOpen = true"
                    class="ml-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Ajouter
            </button>
        </div>

        <table class="w-full text-sm border rounded">
            <thead class="bg-gray-100 text-left">
            <tr>
                <th class="p-2">Nom</th>
                <th class="p-2">Email</th>
                <th class="p-2">Téléphone</th>
                <th class="p-2">Ville</th>
                <th class="p-2">Statut</th>
                <th class="p-2">Commercial</th>
                <th class="p-2 text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <template x-for="pharmacie in filteredPharmacies()" :key="pharmacie.id">
                <tr class="border-t">
                    <td class="p-2" x-text="pharmacie.nom"></td>
                    <td class="p-2" x-text="pharmacie.email ?? '-'"></td>
                    <td class="p-2" x-text="pharmacie.telephone ?? '-'"></td>
                    <td class="p-2" x-text="pharmacie.ville"></td>
                    <td class="p-2" x-text="pharmacie.statut"></td>
                    <td class="p-2" x-text="pharmacie.commercial?.name ?? '-'"></td>
                    <td class="p-2 text-right">
                        <button @click="modalMode = 'edit'; editingPharmacie = pharmacie; modalOpen = true"
                                class="text-blue-600 hover:underline">
                            Modifier
                        </button>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

        <x-pharmacie-form-modal :commerciaux="$commerciaux" />
    </div>

    <script>
        function initPharmacieTable(pharmacies) {
            return {
                search: '',
                modalOpen: false,
                modalMode: 'create',
                editingPharmacie: {},
                pharmacies: pharmacies,

                filteredPharmacies() {
                    return this.pharmacies.filter(p =>
                        p.nom.toLowerCase().includes(this.search.toLowerCase()) ||
                        (p.ville && p.ville.toLowerCase().includes(this.search.toLowerCase()))
                    );
                }
            }
        }
    </script>
</x-app-layout>
