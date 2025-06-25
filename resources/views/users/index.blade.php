<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Utilisateurs</h2>
    </x-slot>

    <script>
        window.usersFromLaravel = @json($users);
        window.zonesFromLaravel = @json($zones);

    </script>

    <div class="p-6 bg-white shadow rounded"
         x-data="initUserTable(window.usersFromLaravel, window.zonesFromLaravel)"


        <div class="flex justify-between items-center mb-4">
            <input type="text" x-model="search" placeholder="Rechercher..."
                   class="w-full max-w-xs border rounded px-3 py-2">
            <button @click="modalMode = 'create'; editingUser = {}; modalOpen = true"
                    class="ml-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Ajouter
            </button>
        </div>

        <x-table>
            <x-slot name="head">
                <tr>
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Rôle</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </x-slot>

            <x-slot name="body">
                <template x-for="user in filteredUsers()" :key="user.id">
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-4 py-3" x-text="user.name"></td>
                        <td class="px-4 py-3" x-text="user.email"></td>
                        <td class="px-4 py-3" x-text="user.roles[0]?.name || '-'"></td>
                        <td class="px-4 py-3">
                    <span
                        class="inline-block px-2 py-1 text-xs rounded-full font-medium"
                        :class="user.is_active
                            ? 'bg-green-50 text-green-700'
                            : 'bg-red-50 text-red-600'"
                        x-text="user.is_active ? 'Actif' : 'Inactif'"
                    ></span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button @click="
                                            modalMode = 'edit';
                                            editingUser = user;
                                            selectedZones = (user.zones || []).map(z => z.id);
                                            modalOpen = true;
                                            "
                                    class="text-blue-600 hover:underline text-sm font-medium">
                                Modifier
                            </button>
                        </td>
                    </tr>
                </template>
            </x-slot>
        </x-table>


        <x-user-form-modal :roles="$roles" :zones="$zones" />
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
