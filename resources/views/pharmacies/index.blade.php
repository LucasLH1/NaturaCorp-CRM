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

        <x-table>
            <x-slot name="head">
                <tr>
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Téléphone</th>
                    <th class="px-4 py-3 text-left">Ville</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-left">Commercial</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </x-slot>

            <x-slot name="body">
                <template x-for="pharmacie in filteredPharmacies()" :key="pharmacie.id">
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-4 py-3" x-text="pharmacie.nom"></td>
                        <td class="px-4 py-3" x-text="pharmacie.email || '-'"></td>
                        <td class="px-4 py-3" x-text="pharmacie.telephone || '-'"></td>
                        <td class="px-4 py-3" x-text="pharmacie.ville"></td>
                        <td class="px-4 py-3">
                    <span
                        class="inline-block px-2 py-1 text-xs rounded-full font-medium"
                        :class="{
                            'bg-green-50 text-green-700': pharmacie.statut === 'client_actif',
                            'bg-yellow-50 text-yellow-700': pharmacie.statut === 'prospect',
                            'bg-gray-100 text-gray-700': pharmacie.statut === 'client_inactif',
                        }"
                        x-text="statutLabel(pharmacie.statut)"
                    ></span>
                        </td>
                        <td class="px-4 py-3" x-text="pharmacie.commercial?.name || '-'"></td>
                        <td class="px-4 py-3 text-right">
                            <button @click="modalMode = 'edit'; editingPharmacie = pharmacie; modalOpen = true"
                                    class="text-blue-600 hover:underline text-sm font-medium">
                                Modifier
                            </button>
                        </td>
                    </tr>
                </template>
            </x-slot>
        </x-table>


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
                },

                statutLabel(statut) {
                    switch (statut) {
                        case 'client_actif':
                            return 'Actif';
                        case 'client_inactif':
                            return 'Inactif';
                        case 'prospect':
                            return 'Prospect';
                        default:
                            return statut;
                    }
                }

            }
        }
    </script>
</x-app-layout>
