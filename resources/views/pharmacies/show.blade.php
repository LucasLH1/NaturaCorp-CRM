<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Fiche pharmacie</h2>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <script>
        window.commandesFromLaravel = @json($pharmacy->commandes);
        window.statutsCommande = @json(\App\Enums\StatutCommande::cases());
        window.produitsFromLaravel = @json($produits);
    </script>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-4 rounded shadow flex items-center space-x-4">
            <div class="p-2 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total commandes</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $pharmacy->commandes->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded shadow flex items-center space-x-4">
            <div class="p-2 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.105 0-2 .672-2 1.5S10.895 11 12 11s2-.672 2-1.5S13.105 8 12 8z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Montant cumulé</p>
                <p class="text-2xl font-semibold text-gray-800">
                    {{
                        number_format(
                            $pharmacy->commandes->sum(fn($cmd) => $cmd->quantite * $cmd->tarif_unitaire),
                            2, ',', ' '
                        )
                    }} €
                </p>
            </div>
        </div>

        <div class="bg-white p-4 rounded shadow flex items-center space-x-4">
            <div class="p-2 bg-gray-100 rounded-full">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a8 8 0 100 16 8 8 0 000-16z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Dernière commande</p>
                <p class="text-2xl font-semibold text-gray-800">
                    {{
                        optional($pharmacy->commandes->sortByDesc('date_commande')->first())->date_commande
                            ? optional($pharmacy->commandes->sortByDesc('date_commande')->first())->date_commande->format('d/m/Y')
                            : '—'
                    }}
                </p>
            </div>
        </div>
    </div>

    <!-- Section principale -->
    <div x-data="initCommandeTable(window.commandesFromLaravel, window.produitsFromLaravel)" class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        <!-- Infos pharmacie -->
        <div class="bg-white p-6 rounded shadow xl:col-span-5">
            <x-pharmacies.formulaire :pharmacy="$pharmacy" />
        </div>

        <!-- Documents -->
        <div class="bg-white p-6 rounded shadow xl:col-span-7">
            <x-pharmacies.documents :pharmacy="$pharmacy" />
        </div>

        <!-- Commandes -->
        <div class="bg-white p-6 rounded shadow xl:col-span-12">
            <x-pharmacies.commandes :pharmacy="$pharmacy" :produits="$produits" />
        </div>
    </div>

    @push('scripts')
        <script>
            function initCommandeTable(commandes, produits) {
                return {
                    commandes,
                    produits,
                    modalOpen: false,
                    modalMode: 'create',
                    editingCommande: {},
                }
            }
        </script>
    @endpush
</x-app-layout>
