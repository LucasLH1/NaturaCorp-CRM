<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tableau de bord administratif</h2>
    </x-slot>

    <div class="p-6 space-y-8">

        {{-- PHARMACIES --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Pharmacies</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <x-dashboard-card label="Total" :value="$stats['pharmacieCount']" color="blue" />
                <x-dashboard-card label="Prospects" :value="$stats['prospects']" color="gray" />
                <x-dashboard-card label="Clients actifs" :value="$stats['clientsActifs']" color="green" />
                <x-dashboard-card label="Clients inactifs" :value="$stats['clientsInactifs']" color="red" />
            </div>
        </div>

        {{-- COMMANDES --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Commandes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <x-dashboard-card label="Total" :value="$stats['commandeCount']" color="indigo" />
                <x-dashboard-card label="CA total" :value="number_format($stats['totalCA'], 2, ',', ' ') . ' €'" color="emerald" />
                <x-dashboard-card label="Commande moyenne" :value="number_format($stats['commandeMoyenne'], 2, ',', ' ') . ' €'" color="yellow" />
                <x-dashboard-card label="Validées" :value="$stats['commandesValidees']" color="blue" />
                <x-dashboard-card label="En cours" :value="$stats['commandesEnCours']" color="orange" />
                <x-dashboard-card label="Livrées" :value="$stats['commandesLivrees']" color="green" />
            </div>
        </div>

        {{-- UTILISATEURS & ALERTES --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Utilisateurs & alertes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <x-dashboard-card label="Utilisateurs" :value="$stats['utilisateurs']" color="purple" />
                <x-dashboard-card label="Notifications non lues" :value="$stats['notificationsNonLues']" color="red" />
            </div>
        </div>

        {{-- GRAPH --}}
        <div class="mt-6 bg-white shadow rounded p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Commandes par mois</h3>
            <canvas id="chartCommandes" height="100"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            fetch('{{ route('dashboard.data.commandes') }}')
                .then(res => res.json())
                .then(data => {
                    new Chart(document.getElementById('chartCommandes'), {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Commandes',
                                data: data.data,
                                backgroundColor: 'rgba(59,130,246,0.2)',
                                borderColor: 'rgba(59,130,246,1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: { beginAtZero: true, ticks: { precision: 0 } }
                            }
                        }
                    });
                });
        </script>

    </div>
</x-app-layout>
