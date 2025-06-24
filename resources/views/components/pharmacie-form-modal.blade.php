@props(['commerciaux'])

<div x-show="modalOpen"
     x-cloak
     class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
     style="display: none;">



        <form :action="modalMode === 'create'
                ? '{{ route('pharmacies.store') }}'
                : `/pharmacies/${editingPharmacie.id}`"
              method="POST"
              class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6">
            @csrf

            <template x-if="modalMode === 'edit'">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <!-- Titre -->
            <div>
                <h2 class="text-xl font-semibold"
                    x-text="modalMode === 'create' ? 'Ajouter une pharmacie' : 'Modifier une pharmacie'">
                </h2>
                <p class="text-xs text-gray-500 mt-1"
                   x-text="`ID: ${editingPharmacie.id}`"
                   x-show="modalMode === 'edit'"></p>
            </div>

            <!-- Grille de champs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm">Nom</label>
                    <input name="nom" required class="w-full border rounded p-2"
                           :value="editingPharmacie?.nom ?? ''">
                </div>
                <div>
                    <label class="block text-sm">SIRET</label>
                    <input name="siret" class="w-full border rounded p-2"
                           :value="editingPharmacie?.siret ?? ''">
                </div>
                <div>
                    <label class="block text-sm">Email</label>
                    <input name="email" type="email" class="w-full border rounded p-2"
                           :value="editingPharmacie?.email ?? ''">
                </div>
                <div>
                    <label class="block text-sm">Téléphone</label>
                    <input name="telephone" class="w-full border rounded p-2"
                           :value="editingPharmacie?.telephone ?? ''">
                </div>
                <div>
                    <label class="block text-sm">Adresse</label>
                    <input name="adresse" required class="w-full border rounded p-2"
                           :value="editingPharmacie?.adresse ?? ''">
                </div>
                <div>
                    <label class="block text-sm">Code postal</label>
                    <input name="code_postal" required class="w-full border rounded p-2"
                           :value="editingPharmacie?.code_postal ?? ''">
                </div>
                <div>
                    <label class="block text-sm">Ville</label>
                    <input name="ville" required class="w-full border rounded p-2"
                           :value="editingPharmacie?.ville ?? ''">
                </div>
                <div>
                    <label class="block text-sm">Dernière prise de contact</label>
                    <input name="derniere_prise_contact" type="date"
                           class="w-full border rounded p-2"
                           :value="editingPharmacie?.derniere_prise_contact ?? ''">
                </div>
            </div>

            <!-- Statut & Commercial -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm">Statut</label>
                    <select name="statut" class="w-full border rounded p-2">
                        <option value="prospect" :selected="editingPharmacie?.statut === 'prospect'">Prospect</option>
                        <option value="client_actif" :selected="editingPharmacie?.statut === 'client_actif'">Client actif</option>
                        <option value="client_inactif" :selected="editingPharmacie?.statut === 'client_inactif'">Client inactif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm">Commercial</label>
                    <select name="commercial_id"
                            class="w-full border rounded p-2"
                            x-model="editingPharmacie.commercial_id">
                        <option value="">—</option>
                        @foreach($commerciaux as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Actions -->
            <div class="text-right pt-4">
                <button type="button"
                        @click="modalOpen = false"
                        class="text-sm text-gray-600 hover:underline mr-4">
                    Annuler
                </button>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
