<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Utilisateurs</h2>
    </x-slot>

    <script>
        window.usersFromLaravel = @json($users);
    </script>

    <div class="p-6 bg-white shadow rounded"
         x-data="initUserTable(window.usersFromLaravel)">

        <!-- Barre de recherche + bouton ajouter -->
        <div class="flex justify-between items-center mb-4">
            <input type="text" x-model="search" placeholder="Rechercher..."
                   class="w-full max-w-xs border rounded px-3 py-2">
            <button @click="modalMode = 'create'; editingUser = {}; modalOpen = true"
                    class="ml-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Ajouter
            </button>
        </div>

        <!-- Tableau -->
        <table class="w-full text-sm border rounded">
            <thead class="bg-gray-100 text-left">
            <tr>
                <th class="p-2">Nom</th>
                <th class="p-2">Email</th>
                <th class="p-2">Rôle</th>
                <th class="p-2">Statut</th>
                <th class="p-2 text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <template x-for="user in filteredUsers()" :key="user.id">
                <tr class="border-t">
                    <td class="p-2" x-text="user.name"></td>
                    <td class="p-2" x-text="user.email"></td>
                    <td class="p-2" x-text="user.roles[0]?.name ?? '-'"></td>
                    <td class="p-2">
                            <span :class="user.is_active ? 'text-green-600' : 'text-red-600'"
                                  x-text="user.is_active ? 'Actif' : 'Inactif'"></span>
                    </td>
                    <td class="p-2 text-right">
                        <button @click="modalMode = 'edit'; editingUser = user; modalOpen = true"
                                class="text-blue-600 hover:underline">
                            Modifier
                        </button>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

        <x-user-form-modal :roles="$roles" />
    </div>

    <script>
        function initUserTable(users) {
            return {
                search: '',
                modalOpen: false,
                modalMode: 'create',
                editingUser: {},
                users: users,

                filteredUsers() {
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        u.email.toLowerCase().includes(this.search.toLowerCase())
                    );
                }
            }
        }
    </script>
</x-app-layout>
