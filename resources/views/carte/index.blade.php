<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Carte des pharmacies</h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Filtres -->
        <aside class="col-span-1 bg-white border border-gray-200 rounded-xl shadow-sm p-4 space-y-6"
               x-data="initCarteFiltrage(@js($pharmacies), @js($commerciaux), @js($villes), @js($statuts))"
               x-init="update()">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Filtres</h3>

            <div class="space-y-2">
                <label class="block text-xs text-gray-500 uppercase tracking-wide">Commercial</label>
                <select x-model="filters.commercial" @change="update()" class="w-full border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="">Tous</option>
                    <template x-for="nom in commerciaux" :key="nom">
                        <option :value="nom" x-text="nom"></option>
                    </template>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs text-gray-500 uppercase tracking-wide">Ville</label>
                <select x-model="filters.ville" @change="update()" class="w-full border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="">Toutes</option>
                    <template x-for="ville in villes" :key="ville">
                        <option :value="ville" x-text="ville"></option>
                    </template>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs text-gray-500 uppercase tracking-wide">Statut</label>
                <select x-model="filters.statut" @change="update()" class="w-full border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="">Tous</option>
                    <template x-for="statut in statuts" :key="statut">
                        <option :value="statut" x-text="formatStatut(statut)"></option>
                    </template>
                </select>
            </div>

            <button type="button"
                    @click="filters = { commercial: '', ville: '', statut: '' }; update();"
                    class="w-full mt-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded px-3 py-2">
                Réinitialiser les filtres
            </button>

            <!-- Résumé -->
            <div class="border-t pt-4 text-sm text-gray-700 space-y-1">
                <p>
                    <span class="font-semibold" x-text="filtered.length"></span>
                    pharmacies affichées
                </p>
                <template x-for="s in statuts" :key="s">
                    <p>
                        <span class="inline-block w-2 h-2 rounded-full align-middle mr-1"
                              :class="badgeColor(s)"></span>
                        <span class="capitalize" x-text="formatStatut(s)"></span> :
                        <span x-text="filtered.filter(p => p.statut === s).length"></span>
                    </p>
                </template>
            </div>
        </aside>

        <!-- Carte -->
        <div class="col-span-1 lg:col-span-3" x-data>
            <x-map-pharmacies
                :pharmacies="$pharmacies"
                :commerciaux="$commerciaux"
                :villes="$villes"
                :statuts="$statuts"
                height="aspect-[3/2]"
            />
        </div>
    </div>

    @push('scripts')
        <script>
            function initCarteFiltrage(pharmacies, commerciaux, villes, statuts) {
                return {
                    pharmacies,
                    commerciaux,
                    villes,
                    statuts,
                    filters: { commercial: '', ville: '', statut: '' },
                    filtered: pharmacies,

                    update() {
                        this.filtered = this.pharmacies.filter(p =>
                            (!this.filters.commercial || p.commercial?.name === this.filters.commercial) &&
                            (!this.filters.ville || p.ville === this.filters.ville) &&
                            (!this.filters.statut || p.statut === this.filters.statut)
                        );
                    },

                    formatStatut(value) {
                        return {
                            'client_actif': 'Actif',
                            'client_inactif': 'Inactif',
                            'prospect': 'Prospect'
                        }[value] || value;
                    },

                    badgeColor(statut) {
                        return {
                            'client_actif': 'bg-green-500',
                            'client_inactif': 'bg-gray-400',
                            'prospect': 'bg-yellow-500',
                        }[statut] || 'bg-gray-300';
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
