<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tableau de bord</h2>
    </x-slot>

    <div class="p-6">
        <x-dashboard-global-stats :stats="$stats" />
    </div>
</x-app-layout>
