@props(['roles', 'zones'])

<div x-show="modalOpen"
     x-cloak
     class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
     style="display: none;">
    <div @click.outside="modalOpen = false"
         class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6"
         x-data="userFormModal()"
         x-effect="if (modalOpen && modalMode === 'edit') initFromEditingUser()"
    >
        <form :action="modalMode === 'create'
                        ? '{{ route('users.store') }}'
                        : `/users/${editingUser.id}`"
              method="POST"
              class="space-y-4">

            @csrf
            <template x-if="modalMode === 'edit'">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <h2 class="text-lg font-semibold" x-text="modalMode === 'create'
                    ? 'Ajouter un utilisateur'
                    : 'Modifier un utilisateur'"></h2>

            <div>
                <label class="block text-sm">Nom</label>
                <input name="name" type="text" required
                       class="w-full border rounded p-2"
                       :value="editingUser?.name ?? ''">
            </div>

            <div>
                <label class="block text-sm">Email</label>
                <input name="email" type="email" required
                       class="w-full border rounded p-2"
                       :value="editingUser?.email ?? ''">
            </div>

            <div>
                <label class="block text-sm">Mot de passe</label>
                <input name="password" type="password" class="w-full border rounded p-2">
                <small x-show="modalMode === 'edit'" class="text-gray-500">Laisser vide pour ne pas modifier</small>
            </div>

            <div>
                <label class="block text-sm">Confirmation</label>
                <input name="password_confirmation" type="password" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-sm">Rôle</label>
                <select name="role" class="w-full border rounded p-2">
                    @foreach($roles as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>

            <div x-show="modalMode === 'edit'" class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" :checked="editingUser?.is_active">
                <span>Compte actif</span>
            </div>

            <!-- Zones -->
            <div>
                <label class="block text-sm mb-1">Zones assignées</label>
                <input type="text" x-model="zoneSearch" placeholder="Filtrer par département..."
                       class="w-full border rounded p-2 mb-2">

                <div class="max-h-40 overflow-y-auto border rounded p-2 space-y-1">
                    <template x-for="zone in filteredZones()" :key="zone.id">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox"
                                   :value="zone.id"
                                   :checked="isZoneSelected(zone.id)"
                                   @change="toggleZone(zone.id)">
                            <span x-text="zone.nom"></span>
                        </label>
                    </template>
                </div>

                <!-- Champs cachés pour POST -->
                <template x-for="id in selectedZones">
                    <input type="hidden" name="zones[]" :value="id">
                </template>
            </div>

            <div class="text-right pt-4">
                <button type="button" @click="modalOpen = false" class="text-sm text-gray-600 hover:underline mr-4">Annuler</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function userFormModal() {
        return {
            zoneSearch: '',
            selectedZones: [],
            allZones: window.zonesFromLaravel,

            filteredZones() {
                return this.allZones.filter(z =>
                    z.nom.toLowerCase().includes(this.zoneSearch.toLowerCase())
                );
            },

            toggleZone(id) {
                if (this.selectedZones.includes(id)) {
                    this.selectedZones = this.selectedZones.filter(z => z !== id);
                } else {
                    this.selectedZones.push(id);
                }
            },

            isZoneSelected(id) {
                return this.selectedZones.includes(id);
            },

            // ⚠️ Cette méthode met à jour les zones quand on édite un utilisateur
            initFromEditingUser() {
                this.selectedZones = this.editingUser?.zones?.map(z => z.id) || [];
            }
        }
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('userFormModal', userFormModal);
    });
</script>

