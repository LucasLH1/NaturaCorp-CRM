<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Produits</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow" x-data="produitPage()">
        <!-- Formulaire de création -->
        <form @submit.prevent="submitForm()" class="mb-6 space-y-4">
            <div class="grid md:grid-cols-3 gap-4">
                <input type="text" x-model="form.nom" placeholder="Nom du produit" required class="border rounded px-3 py-2 w-full">
                <input type="number" step="0.01" x-model="form.tarif_unitaire" placeholder="Tarif (€)" required class="border rounded px-3 py-2 w-full">
                <input type="number" x-model="form.stock" placeholder="Stock" required class="border rounded px-3 py-2 w-full">
            </div>
            <div class="flex justify-end gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <span x-text="form.id ? 'Mettre à jour' : 'Créer'"></span>
                </button>
                <button type="button" @click="resetForm()" x-show="form.id" class="border px-4 py-2 rounded">
                    Annuler
                </button>
            </div>
        </form>

        <!-- Barre de recherche -->
        <div class="mb-4">
            <input type="text" x-model="search" placeholder="Rechercher un produit..." class="w-full border rounded px-3 py-2">
        </div>

        <!-- Liste des cartes -->
        <div class="grid md:grid-cols-3 gap-4">
            <template x-for="produit in filteredProduits()" :key="produit.id">
                <div class="border rounded-lg p-4 shadow-sm bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800" x-text="produit.nom"></h3>
                    <p class="text-sm text-gray-600">Tarif : <span x-text="produit.tarif_unitaire.toFixed(2) + ' €'"></span></p>
                    <p class="text-sm text-gray-600">Stock : <span x-text="produit.stock"></span></p>

                    <div class="mt-3 flex justify-end gap-2">
                        <button @click="editProduit(produit)" class="text-blue-600 text-sm hover:underline">Modifier</button>
                        <form :action="`/produits/${produit.id}`" method="POST" @submit.prevent="confirm('Supprimer ?') && $el.submit()">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 text-sm hover:underline">Supprimer</button>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function produitPage() {
            return {
                produits: @json($produits),
                search: '',
                form: {
                    id: null,
                    nom: '',
                    tarif_unitaire: '',
                    stock: '',
                },

                filteredProduits() {
                    return this.produits.filter(p =>
                        p.nom.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                editProduit(produit) {
                    this.form = { ...produit };
                },

                resetForm() {
                    this.form = { id: null, nom: '', tarif_unitaire: '', stock: '' };
                },

                submitForm() {
                    const method = this.form.id ? 'PUT' : 'POST';
                    const url = this.form.id ? `/produits/${this.form.id}` : `/produits`;
                    const data = { ...this.form, _token: '{{ csrf_token() }}' };

                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data),
                    }).then(() => window.location.reload());
                }
            }
        }
    </script>
</x-app-layout>
