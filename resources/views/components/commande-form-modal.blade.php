<div x-show="modalOpen"
     x-cloak
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40"
     style="display: none;"
     x-data="commandeModal()"
     x-init="init()">

    <form method="POST"
          :action="modalMode === 'edit'
                    ? '/commandes/' + editingCommande.id
                    : '/commandes'"
          class="bg-white p-6 rounded-lg shadow w-full max-w-2xl space-y-4">
        @csrf
        <template x-if="modalMode === 'edit'">
            <input type="hidden" name="_method" value="PUT">
        </template>

        <h2 class="text-lg font-semibold" x-text="modalMode === 'edit' ? 'Modifier la commande' : 'Nouvelle commande'"></h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Pharmacie -->
            <div>
                <label>Pharmacie</label>
                <select name="pharmacie_id" x-model="editingCommande.pharmacie_id" class="w-full border rounded">
                    @foreach($pharmacies as $ph)
                        <option value="{{ $ph->id }}">{{ $ph->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Produit -->
            <div>
                <label>Produit</label>
                <select name="produit_id" x-model="editingCommande.produit_id" @change="updateTarifEtStock()" class="w-full border rounded">
                    @foreach($produits as $produit)
                        <option value="{{ $produit->id }}">{{ $produit->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date -->
            <div>
                <label>Date commande</label>
                <input type="date" name="date_commande" x-model="editingCommande.date_commande" class="w-full border rounded" />
            </div>

            <!-- Statut -->
            <div>
                <label>Statut</label>
                <select name="statut" x-model="editingCommande.statut" class="w-full border rounded">
                    @foreach($statuts as $statut)
                        <option value="{{ $statut->value }}">{{ $statut->label() }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Quantité -->
            <div>
                <label>Quantité</label>
                <input
                    type="number"
                    name="quantite"
                    x-model="editingCommande.quantite"
                    :max="quantiteMaxAutorisee()"
                    min="1"
                    class="w-full border rounded"
                />
                <p class="text-sm text-gray-500 mt-1">
                    Stock dispo : <span x-text="stockProduitActuel()"></span>
                </p>
            </div>

            <!-- Tarif -->
            <div>
                <label>Tarif unitaire (€)</label>
                <input type="text"
                       name="tarif_unitaire"
                       x-model="editingCommande.tarif_unitaire"
                       class="w-full border rounded bg-gray-100 text-gray-600"
                       readonly />
            </div>

            <!-- Observations -->
            <div class="md:col-span-2">
                <label>Observations</label>
                <textarea name="observations" x-model="editingCommande.observations" class="w-full border rounded" rows="2"></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button type="button" @click="modalOpen = false" class="border px-4 py-2 rounded">Annuler</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded"
                    x-text="modalMode === 'edit' ? 'Mettre à jour' : 'Créer'">
            </button>
        </div>
    </form>
</div>

<script>
    function commandeModal() {
        return {
            produits: window.produitsFromLaravel,

            init() {
                this.updateTarifEtStock();
            },

            updateTarifEtStock() {
                const produit = this.produits.find(p => p.id == this.editingCommande.produit_id);
                if (!produit) return;

                // Tarif uniquement en création
                if (this.modalMode === 'create') {
                    this.editingCommande.tarif_unitaire = produit.tarif_unitaire;
                }
            },

            stockProduitActuel() {
                const produit = this.produits.find(p => p.id == this.editingCommande.produit_id);
                return produit ? produit.stock : 0;
            },

            quantiteMaxAutorisee() {
                const produit = this.produits.find(p => p.id == this.editingCommande.produit_id);
                if (!produit) return 0;

                const stock = produit.stock;
                const initiale = parseInt(this.editingCommande.quantite_initiale) || 0;

                return this.modalMode === 'edit' ? stock + initiale : stock;
            }
        };
    }
</script>
