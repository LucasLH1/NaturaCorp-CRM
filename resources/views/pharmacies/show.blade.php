<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Fiche pharmacie</h2>
    </x-slot>

    <div class="bg-white rounded-xl shadow p-6 space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">{{ $pharmacie->nom }}</h3>
                <p class="text-sm text-gray-600">{{ $pharmacie->adresse }}, {{ $pharmacie->code_postal }} {{ $pharmacie->ville }}</p>
                <p class="text-sm text-gray-600">Statut : {{ ucfirst(str_replace('_', ' ', $pharmacie->statut)) }}</p>
                <p class="text-sm text-gray-600">Commercial : {{ $pharmacie->commercial?->name ?? '-' }}</p>
            </div>
            <a href="{{ route('pharmacies.edit', ['pharmacy' => $pharmacie->id]) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                Modifier
            </a>
        </div>

        <div>
            <h4 class="text-md font-semibold text-gray-700 mb-2">Documents joints</h4>
            <p class="text-sm text-gray-500">Aucun document pour l’instant.</p>
        </div>

        <div>
            <h4 class="text-md font-semibold text-gray-700 mb-2">Actions</h4>
            <form action="#" method="POST">
                <button type="submit" class="text-sm bg-gray-100 border px-3 py-2 rounded hover:bg-gray-200">
                    Générer les commandes PDF
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
