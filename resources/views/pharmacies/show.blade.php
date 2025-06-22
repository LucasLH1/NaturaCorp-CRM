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
    </script>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Total commandes</p>
            <p class="text-2xl font-semibold text-gray-800 mt-1">
                {{ $pharmacy->commandes->count() }}
            </p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Montant cumulé</p>
            <p class="text-2xl font-semibold text-gray-800 mt-1">
                {{
                    number_format(
                        $pharmacy->commandes->sum(fn($cmd) => $cmd->quantite * $cmd->tarif_unitaire),
                        2, ',', ' '
                    )
                }} €
            </p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Dernière commande</p>
            <p class="text-2xl font-semibold text-gray-800 mt-1">
                {{
                    optional($pharmacy->commandes->sortByDesc('date_commande')->first())->date_commande
                        ? optional($pharmacy->commandes->sortByDesc('date_commande')->first())->date_commande->format('d/m/Y')
                        : '—'
                }}
            </p>
        </div>
    </div>

    <div x-data="initCommandeTable(window.commandesFromLaravel)" class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <!-- Bloc Infos -->
        <div class="bg-white p-6 rounded shadow space-y-6">
            <form method="POST" action="{{ route('pharmacies.update', $pharmacy) }}">
                @csrf @method('PUT')

                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informations générales</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mt-4">
                    @foreach ([
                        'nom' => 'Nom',
                        'siret' => 'SIRET',
                        'email' => 'Email',
                        'telephone' => 'Téléphone',
                        'adresse' => 'Adresse',
                        'code_postal' => 'Code postal',
                        'ville' => 'Ville',
                        'derniere_prise_contact' => 'Dernière prise de contact',
                    ] as $field => $label)
                        <div>
                            <label class="block font-medium mb-1">{{ $label }}</label>
                            <input name="{{ $field }}"
                                   type="{{ $field === 'email' ? 'email' : ($field === 'derniere_prise_contact' ? 'date' : 'text') }}"
                                   class="w-full border rounded p-2 bg-white"
                                   value="{{ old($field, $pharmacy->$field) }}">
                        </div>
                    @endforeach

                    <div>
                        <label class="block font-medium mb-1">Statut</label>
                        <select name="statut" class="w-full border rounded p-2 bg-white">
                            <option value="prospect" @selected($pharmacy->statut === 'prospect')>Prospect</option>
                            <option value="client_actif" @selected($pharmacy->statut === 'client_actif')>Client actif</option>
                            <option value="client_inactif" @selected($pharmacy->statut === 'client_inactif')>Client inactif</option>
                        </select>
                    </div>
                </div>



                <div class="text-right pt-4">
                    <form method="POST" action="{{ route('pharmacies.destroy', $pharmacy) }}"
                          onsubmit="return confirm('Supprimer définitivement cette pharmacie ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Supprimer définitivement
                        </button>
                    </form>

                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Carte -->
        @if($pharmacy->latitude && $pharmacy->longitude)
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Localisation</h3>
                <div id="map"
                     class="w-full min-h-[300px] rounded overflow-hidden"
                     style="height: 300px !important;"
                     data-lat="{{ $pharmacy->latitude }}"
                     data-lon="{{ $pharmacy->longitude }}"
                     data-label="{{ $pharmacy->nom }}">
                </div>
            </div>
        @endif


        <!-- Documents -->
        <div class="bg-white p-6 rounded shadow xl:col-span-2 space-y-6">
            <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Documents joints</h3>

            <table class="w-full text-sm border rounded overflow-hidden">
                <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Nom</th>
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2">Ajouté le</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pharmacy->documents as $doc)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $doc->nom_fichier }}</td>
                        <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $doc->type) }}</td>
                        <td class="px-4 py-2">{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ asset('storage/' . $doc->chemin) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                Télécharger
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-2 text-gray-500 text-sm">Aucun document.</td></tr>
                @endforelse
                </tbody>
            </table>

            <form method="POST"
                  action="{{ route('documents.store') }}"
                  enctype="multipart/form-data"
                  class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm items-end border-t pt-4">
                @csrf
                <input type="hidden" name="pharmacie_id" value="{{ $pharmacy->id }}">

                <div>
                    <label class="block text-gray-600 mb-1">Type</label>
                    <select name="type" class="w-full border rounded p-2 bg-white" required>
                        <option value="">— Sélectionner —</option>
                        <option value="contrat">Contrat</option>
                        <option value="devis">Devis</option>
                        <option value="document_reglementaire">Document réglementaire</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-600 mb-1">Fichier</label>
                    <input type="file" name="fichier" class="w-full border rounded p-2 bg-white" required>
                </div>

                <div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>

        <!-- Commandes -->
        <div class="bg-white p-6 rounded shadow xl:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Commandes associées</h3>
                <button @click="modalMode = 'create'; editingCommande = { pharmacie_id: {{ $pharmacy->id }} }; modalOpen = true"
                        class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200">
                    Nouvelle commande
                </button>
            </div>

            <table class="w-full text-sm border rounded overflow-hidden">
                <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Statut</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pharmacy->commandes as $cmd)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $cmd->date_commande }}</td>
                        <td class="px-4 py-2">{{ $cmd->statut->label() }}</td>
                        <td class="px-4 py-2">{{ number_format($cmd->quantite * $cmd->tarif_unitaire, 2, ',', ' ') }} €</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ asset('storage/' . $cmd->document?->chemin) }}"
                               class="text-blue-600 hover:underline text-sm" target="_blank">
                                Voir PDF
                            </a>

                            <form method="POST" action="{{ route('commandes.destroy', $cmd) }}"
                                  onsubmit="return confirm('Supprimer cette commande ?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm ml-4">Supprimer</button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-2 text-gray-500 text-sm">Aucune commande.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal commande -->
        <x-commande-form-modal :pharmacies="[$pharmacy]" :statuts="\App\Enums\StatutCommande::cases()" />

    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            function initCommandeTable(commandes) {
                return {
                    commandes,
                    modalOpen: false,
                    modalMode: 'create',
                    editingCommande: {},
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const mapEl = document.getElementById('map');
                if (mapEl) {
                    const lat = parseFloat(mapEl.dataset.lat);
                    const lon = parseFloat(mapEl.dataset.lon);

                    const map = L.map('map').setView([lat, lon], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    L.marker([lat, lon])
                        .addTo(map)
                        .bindPopup(mapEl.dataset.label || "Pharmacie")
                        .openPopup();
                }
            });
        </script>
    @endpush

</x-app-layout>
