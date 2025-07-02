<div x-show="modalOpen"
     x-cloak
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40"
     style="display: none;">

    <div class="bg-white p-6 rounded-lg shadow w-full max-w-3xl max-h-[90vh] overflow-y-auto space-y-4">
        <form method="POST"
              :action="modalMode === 'edit'
                        ? '/commandes/' + editingCommande.id
                        : '/commandes'">
            @csrf
            <template x-if="modalMode === 'edit'">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <h2 class="text-lg font-semibold" x-text="modalMode === 'edit' ? 'Modifier la commande' : 'Nouvelle commande'"></h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label>Pharmacie</label>
                    <select name="pharmacie_id" x-model="editingCommande.pharmacie_id" class="w-full border rounded">
                        @foreach($pharmacies as $ph)
                            <option value="{{ $ph->id }}">{{ $ph->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Date commande</label>
                    <input type="date" name="date_commande" x-model="editingCommande.date_commande" class="w-full border rounded" />
                </div>

                <div>
                    <label>Statut</label>
                    <select name="statut" x-model="editingCommande.statut" class="w-full border rounded">
                        @foreach($statuts as $statut)
                            <option value="{{ $statut->value }}">{{ $statut->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label>Observations</label>
                    <textarea name="observations" x-model="editingCommande.observations" class="w-full border rounded" rows="2"></textarea>
                </div>
            </div>

            <div class="border-t pt-4 mt-4">
                <h3 class="font-semibold mb-2">Produits</h3>

                <template x-for="(ligne, index) in editingCommande.produits" :key="index">
                    <div class="grid grid-cols-12 gap-2 p-2 border-b last:border-none">
                        <div class="col-span-4">
                            <select :name="'produits['+index+'][id]'"
                                    x-model.number="ligne.id"
                                    @change="updateTarif(index)"
                                    class="w-full border rounded">
                                <option value="">Sélectionner</option>
                                <template x-for="p in produits">
                                    <option :value="p.id" x-text="p.nom"></option>
                                </template>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <input type="number" :name="'produits['+index+'][quantite]'" x-model="ligne.quantite" min="1" class="w-full border rounded" placeholder="Qté" />
                        </div>

                        <div class="col-span-3">
                            <input type="text" :name="'produits['+index+'][prix_unitaire]'" x-model="ligne.prix_unitaire" class="w-full border rounded bg-gray-100 text-gray-600" readonly />
                        </div>

                        <div class="col-span-2 text-sm text-gray-500">
                            Stock : <span x-text="stockProduit(ligne.id)"></span>
                        </div>

                        <div class="col-span-1">
                            <button type="button" @click="supprimerProduit(index)" class="text-red-600 font-bold">×</button>
                        </div>
                    </div>
                </template>

                <button type="button" @click="ajouterProduit()" class="mt-2 px-3 py-1 border rounded bg-gray-100">+ Ajouter produit</button>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" @click="modalOpen = false" class="border px-4 py-2 rounded">Annuler</button>
                <template x-if="!stockSuffisant()">
                    <p class="text-red-600 text-sm mb-2">La quantité saisie dépasse le stock disponible pour au moins un produit.</p>
                </template>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
                        x-bind:disabled="!stockSuffisant()"
                        x-text="modalMode === 'edit' ? 'Mettre à jour' : 'Créer'">
                </button>
            </div>
        </form>
    </div>
</div>
