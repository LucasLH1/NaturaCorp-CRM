<div class="space-y-12">

    <!-- SECTION : STATISTIQUES SYNTHÉTIQUES -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques globales</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <x-dashboard-card label="Pharmacies totales" :value="$stats['pharmacies_total']" color="blue" />
            <x-dashboard-card label="Commandes totales" :value="$stats['commandes_total']" color="green" />
            <x-dashboard-card label="Commandes ce mois" :value="$stats['commandes_mois']" color="indigo" />
            <x-dashboard-card label="Prospects ce mois" :value="$stats['prospects_mois']" color="gray" />
            <x-dashboard-card label="Commande moy. par pharmacie" :value="$stats['commande_moyenne_par_pharmacie']" color="blue" />
            <x-dashboard-card label="Commandes en retard" :value="$stats['commandes_retard']" color="yellow" />
        </div>
    </div>

    <!-- SECTION : RÉPARTITIONS -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition par statut</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pharmacies -->
            <div class="bg-white rounded-xl shadow p-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Pharmacies</h4>
                <div x-data="{
                    chart: null,
                    init() {
                        if (!this.chart) {
                            this.chart = new ApexCharts(this.$refs.chart, {
                                chart: { type: 'donut', height: 300 },
                                series: {{ json_encode(array_values($stats['pharmacies_par_statut']->toArray())) }},
                                labels: {{ json_encode(array_keys($stats['pharmacies_par_statut']->toArray())) }},
                                legend: { position: 'bottom' }
                            });
                            this.chart.render();
                        }
                    }
                }" x-init="init()">
                    <div x-ref="chart" class="w-full"></div>
                </div>
            </div>

            <!-- Commandes -->
            <div class="bg-white rounded-xl shadow p-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Commandes</h4>
                <div x-data="{
                    chart: null,
                    init() {
                        if (!this.chart) {
                            this.chart = new ApexCharts(this.$refs.chart, {
                                chart: { type: 'donut', height: 300 },
                                series: {{ json_encode(array_values($stats['commandes_par_statut']->toArray())) }},
                                labels: {{ json_encode(array_keys($stats['commandes_par_statut']->toArray())) }},
                                legend: { position: 'bottom' }
                            });
                            this.chart.render();
                        }
                    }
                }" x-init="init()">
                    <div x-ref="chart" class="w-full"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION : ÉVOLUTIONS TEMPORELLES -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution mensuelle</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Commandes -->
            <div class="bg-white rounded-xl shadow p-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Commandes</h4>
                <div x-data="{
                    chart: null,
                    init() {
                        if (!this.chart) {
                            this.chart = new ApexCharts(this.$refs.chart, {
                                chart: { type: 'line', height: 300 },
                                series: [{
                                    name: 'Commandes',
                                    data: {{ json_encode(array_values($stats['evolution_commandes']->toArray())) }}
                                }],
                                xaxis: {
                                    categories: {{ json_encode(array_keys($stats['evolution_commandes']->toArray())) }},
                                    labels: { rotate: -45 }
                                }
                            });
                            this.chart.render();
                        }
                    }
                }" x-init="init()">
                    <div x-ref="chart" class="w-full"></div>
                </div>
            </div>

            <!-- Pharmacies -->
            <div class="bg-white rounded-xl shadow p-4">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Pharmacies</h4>
                <div x-data="{
                    chart: null,
                    init() {
                        if (!this.chart) {
                            this.chart = new ApexCharts(this.$refs.chart, {
                                chart: { type: 'line', height: 300 },
                                series: [{
                                    name: 'Pharmacies',
                                    data: {{ json_encode(array_values($stats['evolution_pharmacies']->toArray())) }}
                                }],
                                xaxis: {
                                    categories: {{ json_encode(array_keys($stats['evolution_pharmacies']->toArray())) }},
                                    labels: { rotate: -45 }
                                }
                            });
                            this.chart.render();
                        }
                    }
                }" x-init="init()">
                    <div x-ref="chart" class="w-full"></div>
                </div>
            </div>
        </div>
    </div>

</div>
