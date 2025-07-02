<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Commandes</h2>
    </x-slot>

    <script>
        window.commandesFromLaravel = @json($commandes);
        window.pharmaciesFromLaravel = @json($pharmacies);
        window.statutsFromLaravel = @json($statuts);
        window.produitsFromLaravel = @json($produits);
    </script>

    <div class="p-6 bg-white shadow rounded"
         x-data="initCommandeTable(window.commandesFromLaravel, window.produitsFromLaravel)">

        <!-- Barre de recherche + bouton ajouter -->
        <div class="flex justify-between items-center mb-4">
            <input type="text" x-model="search" placeholder="Rechercher..."
                   class="w-full max-w-xs border rounded px-3 py-2">
            <button @click="
                    modalMode = 'create';
                    editingCommande = {
                        pharmacie_id: '',
                        date_commande: new Date().toISOString().substring(0,10),
                        statut: window.statutsFromLaravel[0]?.value ?? '',
                        observations: '',
                        produits: []
                    };
                    ajouterProduit();
                    modalOpen = true;
                "
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
                                    'bg-yellow-50 text-yellow-700': commande.statut === 'en_cours',
                                    'bg-blue-50 text-blue-700': commande.statut === 'validee',
                                }"
                                x-text="commande.statut_label"
                            ></span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button
                                @click="
        modalMode = 'edit';
        editingCommande = {
            ...commande,
            date_commande: new Date(commande.date_commande).toISOString().substring(0, 10),
            produits: commande.produits.map(p => ({
                id: null, // valeur temporaire
                quantite: p.pivot.quantite,
                prix_unitaire: p.pivot.prix_unitaire
            }))
        };
        modalOpen = true;

        $nextTick(() => {
            editingCommande.produits.forEach((prod, idx) => {
                prod.id = Number(commande.produits[idx].id);
            });
        });
    "
                                class="text-blue-600 hover:underline text-sm font-medium">
                                Modifier
                            </button>
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

        <!-- Modal -->
        <x-commande-form-modal :pharmacies="$pharmacies" :statuts="$statuts" :produits="$produits" />
    </div>

    <script>
        function initCommandeTable(commandes, produits) {
            return {
                search: '',
                modalOpen: false,
                modalMode: 'create',
                editingCommande: {
                    pharmacie_id: '',
                    date_commande: '',
                    statut: '',
                    observations: '',
                    produits: [],
                },
                commandes: commandes,
                produits: produits,

                filteredCommandes() {
                    return this.commandes
                        .filter(c =>
                            c.pharmacie.nom.toLowerCase().includes(this.search.toLowerCase())
                        )
                        .sort((a, b) => new Date(b.date_commande) - new Date(a.date_commande));
                },

                stockProduit(id) {
                    if (!id) return 0;
                    const produit = this.produits.find(p => p.id == id);
                    return produit ? produit.stock : 0;
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const d = new Date(dateString);
                    return d.toLocaleDateString('fr-FR');
                },

                ajouterProduit() {
                    if (!this.editingCommande.produits) {
                        this.editingCommande.produits = [];
                    }
                    this.editingCommande.produits.push({ id: '', quantite: 1, prix_unitaire: 0 });
                },

                supprimerProduit(index) {
                    this.editingCommande.produits.splice(index, 1);
                },

                updateTarif(index) {
                    const produit = this.produits.find(p => p.id == this.editingCommande.produits[index].id);
                    if (produit) {
                        this.editingCommande.produits[index].prix_unitaire = produit.tarif_unitaire;
                    } else {
                        this.editingCommande.produits[index].prix_unitaire = 0;
                    }
                },

                stockVirtuel(index) {
                    const ligne = this.editingCommande.produits[index];
                    const produit = this.produits.find(p => p.id == ligne.id);
                    if (!produit) return 0;

                    if (this.modalMode === 'edit') {
                        const ancienneQuantite = this.commandes
                            .find(c => c.id === this.editingCommande.id)
                            ?.produits.find(p => p.id == ligne.id)
                            ?.pivot?.quantite || 0;

                        return produit.stock + ancienneQuantite;
                    }

                    return produit.stock;
                },

                stockSuffisant() {
                    return this.editingCommande.produits.every((ligne, index) => {
                        const stockDispo = this.stockVirtuel(index);
                        return ligne.quantite <= stockDispo;
                    });
                }
            };
        }
    </script>
</x-app-layout>
