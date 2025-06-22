<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Commandes</h2>
    </x-slot>

    <script>
        window.commandesFromLaravel = @json($commandes);
        window.pharmaciesFromLaravel = @json($pharmacies);
        window.statutsFromLaravel = @json($statuts);
    </script>

    <div class="p-6 bg-white shadow rounded"
         x-data="initCommandeTable(window.commandesFromLaravel)">

        <!-- Barre de recherche + bouton ajouter -->
        <div class="flex justify-between items-center mb-4">
            <input type="text" x-model="search" placeholder="Rechercher..."
                   class="w-full max-w-xs border rounded px-3 py-2">
            <button @click="modalMode = 'create'; editingCommande = {}; modalOpen = true"
                    class="ml-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Ajouter
            </button>
        </div>

        <!-- Tableau -->
        <x-table>
            <x-slot name="head">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Pharmacie</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-right">Qté</th>
                    <th class="px-4 py-3 text-right">Tarif</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </x-slot>

            <x-slot name="body">
                <template x-for="(commande, index) in filteredCommandes()" :key="commande.id">
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-4 py-3 font-mono text-gray-400" x-text="index + 1"></td>
                        <td class="px-4 py-3" x-text="commande.pharmacie.nom"></td>
                        <td class="px-4 py-3" x-text="formatDate(commande.date_commande)"></td>
                        <td class="px-4 py-3">
                    <span
                        class="inline-block px-2 py-1 text-xs rounded-full font-medium"
                        :class="{
                            'bg-green-50 text-green-700': commande.statut === 'livree',
                            'bg-yellow-50 text-yellow-700': commande.statut === 'en_attente',
                            'bg-blue-50 text-blue-700': commande.statut === 'validee',
                        }"
                        x-text="commande.statut_label"
                    ></span>
                        </td>
                        <td class="px-4 py-3 text-right font-mono" x-text="commande.quantite"></td>
                        <td class="px-4 py-3 text-right font-mono" x-text="parseFloat(commande.tarif_unitaire).toFixed(2) + ' €'"></td>
                        <td class="px-4 py-3 text-right">
                            <button @click="modalMode = 'edit'; editingCommande = commande; modalOpen = true"
                                    class="text-blue-600 hover:underline text-sm font-medium">
                                Modifier
                            </button>
                            <!-- Ajout dans la colonne Actions -->
                            <form method="POST" :action="`/commandes/${commande.id}`" @submit.prevent="if(confirm('Confirmer la suppression ?')) $el.submit()">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600 hover:underline text-sm">Supprimer</button>
                            </form>

                        </td>
                    </tr>
                </template>
            </x-slot>
        </x-table>


        <x-commande-form-modal :pharmacies="$pharmacies" :statuts="$statuts" />
    </div>

    <script>
        function initCommandeTable(commandes) {
            return {
                search: '',
                modalOpen: false,
                modalMode: 'create',
                editingCommande: {},
                commandes: commandes,

                filteredCommandes() {
                    return this.commandes.filter(c =>
                        c.pharmacie.nom.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const d = new Date(dateString);
                    return d.toLocaleDateString('fr-FR'); // Format : jj/mm/aaaa
                }
            };
        }
    </script>

</x-app-layout>
