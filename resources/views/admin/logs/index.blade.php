<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Journal des activités</h2>
    </x-slot>

    <div class="p-6 space-y-6">

        <!-- Filtres -->
        <form method="GET" class="flex flex-wrap gap-4 mb-4">
            <select name="user_id" class="border rounded p-2">
                <option value="">Tous les utilisateurs</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>

            <select name="action" class="border rounded p-2">
                <option value="">Toutes les actions</option>
                @foreach ($actions as $action)
                    <option value="{{ $action }}" @selected(request('action') == $action)>
                        {{ ucfirst($action) }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrer</button>
        </form>

        <!-- Table des logs -->
        <div class="overflow-auto bg-white rounded shadow">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">Date</th>
                    <th class="p-3">Utilisateur</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Description</th>
                    <th class="p-3">IP</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($logs as $log)
                    <tr class="border-t">
                        <td class="p-3">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3">{{ $log->user->name ?? 'N/A' }}</td>
                        <td class="p-3 font-medium text-blue-600">{{ $log->action }}</td>
                        <td class="p-3">{{ $log->description }}</td>
                        <td class="p-3 text-gray-500">{{ $log->ip }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Aucune activité enregistrée.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
