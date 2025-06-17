@props(['roles'])

<div x-show="modalOpen"
     x-cloak
     class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
     style="display: none;">
    <div @click.outside="modalOpen = false"
         class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6">
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

            <div class="text-right pt-4">
                <button type="button" @click="modalOpen = false" class="text-sm text-gray-600 hover:underline mr-4">Annuler</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
