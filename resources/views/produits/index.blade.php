<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Catalogue produits</h2>
            <button onclick="document.getElementById('modal-produit').classList.remove('hidden')"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                + Nouveau produit
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Total produits</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['actifs'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Actifs</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $stats['ruptures'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Ruptures</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <div class="text-2xl font-bold text-orange-500">{{ $stats['stock_faible'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Stock faible</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} €</div>
            <div class="text-xs text-gray-500 mt-1">Valeur stock</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5" x-data="produitPage()">

        {{-- Filtres --}}
        <div class="flex flex-wrap gap-3 items-center mb-5">
            <input type="text" x-model="search" placeholder="Rechercher..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-52 focus:outline-none focus:ring-2 focus:ring-green-500">

            <select x-model="filterCategorie"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Toutes catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            <select x-model="filterStatut"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Tous statuts</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="rupture">Rupture</option>
                <option value="faible">Stock faible</option>
            </select>

            <select x-model="sortBy"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="nom">Trier : Nom</option>
                <option value="stock_asc">Trier : Stock ↑</option>
                <option value="stock_desc">Trier : Stock ↓</option>
                <option value="tarif_asc">Trier : Prix ↑</option>
                <option value="tarif_desc">Trier : Prix ↓</option>
            </select>

            <span class="text-sm text-gray-400 ml-auto" x-text="filteredProduits().length + ' produit(s) affiché(s)'"></span>
        </div>

        {{-- Grille produits --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <template x-for="p in filteredProduits()" :key="p.id">
                <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition bg-white flex flex-col">

                    {{-- En-tête --}}
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1 min-w-0 pr-2">
                            <h3 class="font-semibold text-gray-900 leading-snug" x-text="p.nom"></h3>
                            <span class="text-xs text-gray-400 font-mono" x-text="'REF-' + String(p.id).padStart(3, '0')"></span>
                        </div>
                        <div class="flex flex-col items-end gap-1 flex-shrink-0">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                  :class="p.is_actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400'"
                                  x-text="p.is_actif ? 'Actif' : 'Inactif'"></span>
                            <template x-if="p.categorie">
                                <span class="text-xs px-2 py-0.5 rounded-full bg-purple-100 text-purple-700"
                                      x-text="p.categorie"></span>
                            </template>
                        </div>
                    </div>

                    {{-- Description --}}
                    <p class="text-xs text-gray-500 leading-relaxed mb-3 flex-1"
                       x-text="p.description || 'Aucune description.'"></p>

                    {{-- Prix --}}
                    <div class="text-xl font-bold text-gray-800 mb-3"
                         x-text="parseFloat(p.tarif_unitaire).toFixed(2) + ' €'"></div>

                    {{-- Barre de stock --}}
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-400">Stock</span>
                            <span class="text-xs font-semibold"
                                  :class="{
                                      'text-red-600':    p.stock === 0,
                                      'text-orange-500': p.stock > 0 && p.stock <= p.stock_alerte,
                                      'text-green-600':  p.stock > p.stock_alerte
                                  }">
                                <span x-text="p.stock === 0 ? '⚠ Rupture' : p.stock + ' unités'"></span>
                                <template x-if="p.stock > 0 && p.stock <= p.stock_alerte">
                                    <span> — Stock faible</span>
                                </template>
                            </span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300"
                                 :class="{
                                     'bg-red-400':    p.stock === 0,
                                     'bg-orange-400': p.stock > 0 && p.stock <= p.stock_alerte,
                                     'bg-green-500':  p.stock > p.stock_alerte
                                 }"
                                 :style="'width:' + Math.min(100, (p.stock / Math.max(p.stock_alerte * 4, 1)) * 100) + '%'">
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 mt-1"
                             x-text="'Seuil alerte : ' + p.stock_alerte + ' unités'"></div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 pt-3 border-t border-gray-100 mt-auto">
                        <button @click="openEdit(p)"
                                class="flex-1 text-sm text-blue-600 hover:bg-blue-50 border border-blue-200 rounded-lg py-2 transition font-medium">
                            ✏ Modifier
                        </button>
                        <form :action="`/produits/${p.id}`" method="POST"
                              @submit.prevent="if(confirm('Supprimer «\u00a0' + p.nom + '\u00a0» ?')) $el.submit()">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit"
                                    class="text-sm text-red-500 hover:bg-red-50 border border-red-200 rounded-lg px-3 py-2 transition">
                                🗑
                            </button>
                        </form>
                    </div>
                </div>
            </template>

            <template x-if="filteredProduits().length === 0">
                <div class="col-span-3 text-center py-16 text-gray-400">
                    <div class="text-5xl mb-3">📦</div>
                    <p class="font-semibold text-gray-500">Aucun produit trouvé</p>
                    <p class="text-sm mt-1">Modifiez vos filtres ou créez un nouveau produit.</p>
                </div>
            </template>
        </div>
    </div>

    {{-- Modal création / édition --}}
    <div id="modal-produit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
         x-data="produitModal()" x-on:keydown.escape.window="closeModal()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-lg text-gray-900" x-text="form.id ? 'Modifier le produit' : 'Nouveau produit'"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

            <form @submit.prevent="submitForm()" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input type="text" x-model="form.nom" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea x-model="form.description" rows="2" placeholder="Composition, bienfaits, dosage..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <input type="text" x-model="form.categorie" list="cats"
                               placeholder="Ex: Immunité"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <datalist id="cats">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tarif unitaire (€) *</label>
                        <input type="number" step="0.01" min="0" x-model="form.tarif_unitaire" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock actuel *</label>
                        <input type="number" min="0" x-model="form.stock" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Seuil alerte stock</label>
                        <input type="number" min="0" x-model="form.stock_alerte"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <input type="checkbox" id="modal_is_actif" x-model="form.is_actif"
                           class="w-4 h-4 text-green-600 rounded">
                    <label for="modal_is_actif" class="text-sm text-gray-700 cursor-pointer">
                        Produit actif <span class="text-gray-400">(disponible à la commande)</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="submit"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        <span x-text="form.id ? 'Enregistrer les modifications' : 'Créer le produit'"></span>
                    </button>
                    <button type="button" @click="closeModal()"
                            class="px-5 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function produitPage() {
            return {
                produits: @json($produits),
                search: '',
                filterCategorie: '',
                filterStatut: '',
                sortBy: 'nom',

                filteredProduits() {
                    return this.produits
                        .filter(p => {
                            const s = this.search.toLowerCase();
                            const matchSearch = !s || p.nom.toLowerCase().includes(s) || (p.categorie || '').toLowerCase().includes(s);
                            const matchCat    = !this.filterCategorie || p.categorie === this.filterCategorie;
                            const matchStatut = !this.filterStatut ||
                                (this.filterStatut === 'actif'   && p.is_actif && p.stock > 0) ||
                                (this.filterStatut === 'inactif' && !p.is_actif) ||
                                (this.filterStatut === 'rupture' && p.stock === 0) ||
                                (this.filterStatut === 'faible'  && p.stock > 0 && p.stock <= p.stock_alerte);
                            return matchSearch && matchCat && matchStatut;
                        })
                        .sort((a, b) => {
                            if (this.sortBy === 'stock_asc')  return a.stock - b.stock;
                            if (this.sortBy === 'stock_desc') return b.stock - a.stock;
                            if (this.sortBy === 'tarif_asc')  return a.tarif_unitaire - b.tarif_unitaire;
                            if (this.sortBy === 'tarif_desc') return b.tarif_unitaire - a.tarif_unitaire;
                            return a.nom.localeCompare(b.nom, 'fr');
                        });
                },

                openEdit(p) {
                    window.dispatchEvent(new CustomEvent('open-edit', { detail: p }));
                    document.getElementById('modal-produit').classList.remove('hidden');
                },
            }
        }

        function produitModal() {
            return {
                form: { id: null, nom: '', description: '', categorie: '', tarif_unitaire: '', stock: 0, stock_alerte: 20, is_actif: true },

                init() {
                    window.addEventListener('open-edit', e => {
                        this.form = { ...e.detail };
                    });
                },

                closeModal() {
                    this.form = { id: null, nom: '', description: '', categorie: '', tarif_unitaire: '', stock: 0, stock_alerte: 20, is_actif: true };
                    document.getElementById('modal-produit').classList.add('hidden');
                },

                submitForm() {
                    const method = this.form.id ? 'PUT' : 'POST';
                    const url    = this.form.id ? `/produits/${this.form.id}` : `/produits`;

                    fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ ...this.form }),
                    }).then(() => window.location.reload());
                },
            }
        }
    </script>
</x-app-layout>
