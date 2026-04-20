<x-app-layout>

    @php
    $catColors = [
        'Immunité'    => ['bg' => 'bg-emerald-100',  'text' => 'text-emerald-700',  'dot' => 'bg-emerald-500',  'icon' => '🛡️'],
        'Articulaire' => ['bg' => 'bg-orange-100',   'text' => 'text-orange-700',   'dot' => 'bg-orange-500',   'icon' => '🦴'],
        'Énergie'     => ['bg' => 'bg-amber-100',    'text' => 'text-amber-700',    'dot' => 'bg-amber-500',    'icon' => '⚡'],
        'Sommeil'     => ['bg' => 'bg-indigo-100',   'text' => 'text-indigo-700',   'dot' => 'bg-indigo-500',   'icon' => '🌙'],
        'Digestion'   => ['bg' => 'bg-teal-100',     'text' => 'text-teal-700',     'dot' => 'bg-teal-500',     'icon' => '🌿'],
        'Beauté'      => ['bg' => 'bg-pink-100',     'text' => 'text-pink-700',     'dot' => 'bg-pink-500',     'icon' => '✨'],
    ];
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Catalogue produits</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $stats['total'] }} produits · {{ $stats['actifs'] }} actifs</p>
            </div>
            <button onclick="document.getElementById('modal-produit').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouveau produit
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
            <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-lg flex-shrink-0">📦</div>
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-400">Total</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-lg flex-shrink-0">✅</div>
            <div>
                <div class="text-xl font-bold text-green-600">{{ $stats['actifs'] }}</div>
                <div class="text-xs text-gray-400">Actifs</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-lg flex-shrink-0">⚠️</div>
            <div>
                <div class="text-xl font-bold text-red-600">{{ $stats['ruptures'] }}</div>
                <div class="text-xs text-gray-400">Ruptures</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-lg flex-shrink-0">📉</div>
            <div>
                <div class="text-xl font-bold text-orange-500">{{ $stats['stock_faible'] }}</div>
                <div class="text-xs text-gray-400">Stock faible</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-lg flex-shrink-0">💰</div>
            <div>
                <div class="text-xl font-bold text-blue-600">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} €</div>
                <div class="text-xs text-gray-400">Valeur stock</div>
            </div>
        </div>
    </div>

    <div x-data="produitPage()">

        {{-- Barre de filtres --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
            <div class="flex flex-wrap gap-3 items-center">

                {{-- Recherche --}}
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search" placeholder="Rechercher..."
                           class="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm w-48 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50">
                </div>

                {{-- Filtre catégorie --}}
                <select x-model="filterCategorie"
                        class="border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                {{-- Pills statut --}}
                <div class="flex gap-2 flex-wrap">
                    <button @click="filterStatut = ''"
                            :class="filterStatut === '' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="text-xs font-semibold px-3 py-1.5 rounded-full transition">Tous</button>
                    <button @click="filterStatut = 'actif'"
                            :class="filterStatut === 'actif' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="text-xs font-semibold px-3 py-1.5 rounded-full transition">Actifs</button>
                    <button @click="filterStatut = 'inactif'"
                            :class="filterStatut === 'inactif' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="text-xs font-semibold px-3 py-1.5 rounded-full transition">Inactifs</button>
                    <button @click="filterStatut = 'rupture'"
                            :class="filterStatut === 'rupture' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="text-xs font-semibold px-3 py-1.5 rounded-full transition">Rupture</button>
                    <button @click="filterStatut = 'faible'"
                            :class="filterStatut === 'faible' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="text-xs font-semibold px-3 py-1.5 rounded-full transition">Stock faible</button>
                </div>

                {{-- Tri --}}
                <div class="ml-auto flex items-center gap-2">
                    <span class="text-xs text-gray-400" x-text="filteredProduits().length + ' résultat(s)'"></span>
                    <select x-model="sortBy"
                            class="border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="nom">A → Z</option>
                        <option value="stock_asc">Stock ↑</option>
                        <option value="stock_desc">Stock ↓</option>
                        <option value="tarif_asc">Prix ↑</option>
                        <option value="tarif_desc">Prix ↓</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Grille produits --}}
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
            <template x-for="p in filteredProduits()" :key="p.id">
                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden">

                    {{-- Bande colorée catégorie --}}
                    <div class="h-1.5 w-full"
                         :class="catBg(p.categorie)"></div>

                    <div class="p-5 flex flex-col flex-1">

                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0 pr-2">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="text-base font-bold text-gray-900 leading-tight" x-text="p.nom"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-400 font-mono" x-text="'REF-' + String(p.id).padStart(3,'0')"></span>
                                    <template x-if="p.categorie">
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                                              :class="catPill(p.categorie)"
                                              x-text="catIcon(p.categorie) + ' ' + p.categorie"></span>
                                    </template>
                                </div>
                            </div>
                            <span class="flex-shrink-0 text-xs font-bold px-2.5 py-1 rounded-full border"
                                  :class="p.is_actif
                                    ? 'bg-green-50 text-green-700 border-green-200'
                                    : 'bg-gray-100 text-gray-400 border-gray-200'"
                                  x-text="p.is_actif ? 'Actif' : 'Inactif'"></span>
                        </div>

                        {{-- Description --}}
                        <p class="text-xs text-gray-500 leading-relaxed mb-4 line-clamp-2 flex-1"
                           x-text="p.description || 'Aucune description disponible.'"></p>

                        {{-- Prix --}}
                        <div class="flex items-baseline gap-1 mb-4">
                            <span class="text-2xl font-extrabold text-gray-900"
                                  x-text="parseFloat(p.tarif_unitaire).toFixed(2)"></span>
                            <span class="text-sm font-semibold text-gray-400">€ / unité</span>
                        </div>

                        {{-- Stock --}}
                        <div class="mb-4 p-3 rounded-xl"
                             :class="{
                                'bg-red-50 border border-red-100':    p.stock === 0,
                                'bg-orange-50 border border-orange-100': p.stock > 0 && p.stock <= p.stock_alerte,
                                'bg-gray-50 border border-gray-100':  p.stock > p.stock_alerte,
                             }">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0"
                                          :class="{
                                            'bg-red-500':    p.stock === 0,
                                            'bg-orange-400': p.stock > 0 && p.stock <= p.stock_alerte,
                                            'bg-green-500':  p.stock > p.stock_alerte,
                                          }"></span>
                                    <span class="text-xs font-semibold"
                                          :class="{
                                            'text-red-700':    p.stock === 0,
                                            'text-orange-700': p.stock > 0 && p.stock <= p.stock_alerte,
                                            'text-gray-700':   p.stock > p.stock_alerte,
                                          }"
                                          x-text="p.stock === 0 ? 'Rupture de stock' : (p.stock > p.stock_alerte ? 'Stock OK' : 'Stock faible')"></span>
                                </div>
                                <span class="text-xs font-bold"
                                      :class="{
                                        'text-red-600':    p.stock === 0,
                                        'text-orange-600': p.stock > 0 && p.stock <= p.stock_alerte,
                                        'text-gray-700':   p.stock > p.stock_alerte,
                                      }"
                                      x-text="p.stock + ' unités'"></span>
                            </div>
                            <div class="w-full bg-white rounded-full h-1.5 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500"
                                     :class="{
                                        'bg-red-400':    p.stock === 0,
                                        'bg-orange-400': p.stock > 0 && p.stock <= p.stock_alerte,
                                        'bg-green-500':  p.stock > p.stock_alerte,
                                     }"
                                     :style="'width:' + Math.min(100, Math.round(p.stock / Math.max(p.stock_alerte * 4, 1) * 100)) + '%'">
                                </div>
                            </div>
                            <div class="text-xs text-gray-400 mt-1.5" x-text="'Seuil alerte : ' + p.stock_alerte + ' unités'"></div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2 mt-auto">
                            <button @click="openEdit(p)"
                                    class="flex-1 flex items-center justify-center gap-1.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl py-2.5 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Modifier
                            </button>
                            <form :action="`/produits/${p.id}`" method="POST"
                                  @submit.prevent="if(confirm('Supprimer «\u00a0' + p.nom + '\u00a0» ?')) $el.submit()">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit"
                                        class="flex items-center justify-center w-10 h-full text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </template>

            {{-- État vide --}}
            <template x-if="filteredProduits().length === 0">
                <div class="col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm text-center py-20 px-6">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">📦</div>
                    <h3 class="font-bold text-gray-700 mb-1">Aucun produit trouvé</h3>
                    <p class="text-sm text-gray-400 mb-4">Modifiez vos filtres ou ajoutez un nouveau produit.</p>
                    <button onclick="document.getElementById('modal-produit').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                        + Nouveau produit
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- Modal --}}
    <div id="modal-produit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
         x-data="produitModal()" x-on:keydown.escape.window="closeModal()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            {{-- Header modal --}}
            <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <div>
                    <h3 class="font-bold text-gray-900" x-text="form.id ? 'Modifier le produit' : 'Nouveau produit'"></h3>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="form.id ? 'REF-' + String(form.id).padStart(3,'0') : 'Sera créé automatiquement'"></p>
                </div>
                <button @click="closeModal()"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition text-xl leading-none">
                    &times;
                </button>
            </div>

            <form @submit.prevent="submitForm()" class="p-6 space-y-5">

                {{-- Nom --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom du produit *</label>
                    <input type="text" x-model="form.nom" required placeholder="Ex : Oméga-3 Premium"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50">
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea x-model="form.description" rows="2" placeholder="Composition, bienfaits, dosage recommandé..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50 resize-none"></textarea>
                </div>

                {{-- Catégorie + Tarif --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catégorie</label>
                        <input type="text" x-model="form.categorie" list="cats-list" placeholder="Ex : Immunité"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50">
                        <datalist id="cats-list">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tarif unitaire (€) *</label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" x-model="form.tarif_unitaire" required placeholder="0.00"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">€</span>
                        </div>
                    </div>
                </div>

                {{-- Stock + Seuil --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stock actuel *</label>
                        <div class="relative">
                            <input type="number" min="0" x-model="form.stock" required placeholder="0"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">unités</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Seuil d'alerte</label>
                        <div class="relative">
                            <input type="number" min="0" x-model="form.stock_alerte" placeholder="20"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">unités</span>
                        </div>
                    </div>
                </div>

                {{-- Statut actif --}}
                <label class="flex items-center gap-3 p-3.5 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-green-50 hover:border-green-200 transition">
                    <div class="relative flex-shrink-0">
                        <input type="checkbox" id="modal_is_actif" x-model="form.is_actif" class="sr-only peer">
                        <div class="w-10 h-6 bg-gray-200 peer-checked:bg-green-500 rounded-full transition"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-4"></div>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-700">Produit actif</div>
                        <div class="text-xs text-gray-400">Disponible à la commande pour les commerciaux</div>
                    </div>
                </label>

                {{-- Boutons --}}
                <div class="flex gap-3 pt-1">
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="form.id ? 'Enregistrer' : 'Créer le produit'"></span>
                    </button>
                    <button type="button" @click="closeModal()"
                            class="px-5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition text-sm font-medium">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const CAT_STYLES = {
            'Immunité':    { bar: 'bg-emerald-500', pill: 'bg-emerald-100 text-emerald-700', icon: '🛡️' },
            'Articulaire': { bar: 'bg-orange-500',  pill: 'bg-orange-100 text-orange-700',   icon: '🦴' },
            'Énergie':     { bar: 'bg-amber-500',   pill: 'bg-amber-100 text-amber-700',     icon: '⚡' },
            'Sommeil':     { bar: 'bg-indigo-500',  pill: 'bg-indigo-100 text-indigo-700',   icon: '🌙' },
            'Digestion':   { bar: 'bg-teal-500',    pill: 'bg-teal-100 text-teal-700',       icon: '🌿' },
            'Beauté':      { bar: 'bg-pink-500',    pill: 'bg-pink-100 text-pink-700',       icon: '✨' },
        };

        function produitPage() {
            return {
                produits: @json($produits),
                search: '',
                filterCategorie: '',
                filterStatut: '',
                sortBy: 'nom',

                catBg(cat) { return CAT_STYLES[cat]?.bar ?? 'bg-gray-300'; },
                catPill(cat) { return CAT_STYLES[cat]?.pill ?? 'bg-gray-100 text-gray-600'; },
                catIcon(cat) { return CAT_STYLES[cat]?.icon ?? '📦'; },

                filteredProduits() {
                    return this.produits
                        .filter(p => {
                            const s = this.search.toLowerCase();
                            const matchSearch = !s || p.nom.toLowerCase().includes(s) || (p.categorie || '').toLowerCase().includes(s);
                            const matchCat    = !this.filterCategorie || p.categorie === this.filterCategorie;
                            const matchStatut = !this.filterStatut
                                || (this.filterStatut === 'actif'   && p.is_actif && p.stock > 0)
                                || (this.filterStatut === 'inactif' && !p.is_actif)
                                || (this.filterStatut === 'rupture' && p.stock === 0)
                                || (this.filterStatut === 'faible'  && p.stock > 0 && p.stock <= p.stock_alerte);
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
                    window.dispatchEvent(new CustomEvent('open-edit', { detail: { ...p } }));
                    document.getElementById('modal-produit').classList.remove('hidden');
                },
            }
        }

        function produitModal() {
            return {
                form: { id: null, nom: '', description: '', categorie: '', tarif_unitaire: '', stock: 0, stock_alerte: 20, is_actif: true },

                init() {
                    window.addEventListener('open-edit', e => { this.form = { ...e.detail }; });
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
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ ...this.form }),
                    }).then(() => window.location.reload());
                },
            }
        }
    </script>

</x-app-layout>
