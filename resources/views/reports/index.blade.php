<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Report Card: Asset Status Summary -->
        <div class="bg-surface p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-lg text-text-primary mb-1">{{ __('Asset Status Summary') }}</h3>
            <p class="text-sm text-text-secondary mb-4">{{ __('Summary of assets by their current status.') }}</p>
            <div class="h-64">
                <canvas id="assetStatusChart"></canvas>
            </div>
        </div>

        <!-- Report Card: Asset Age Distribution -->
        <div class="bg-surface p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-lg text-text-primary mb-1">{{ __('Asset Age Distribution') }}</h3>
            <p class="text-sm text-text-secondary mb-4">{{ __('Distribution of assets by their purchase year.') }}</p>
            <div class="h-64">
                <canvas id="assetAgeChart"></canvas>
            </div>
        </div>

        <!-- Report Card: Assets by Location -->
        <div class="bg-surface p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-lg text-text-primary mb-1">{{ __('Assets by Location') }}</h3>
            <p class="text-sm text-text-secondary mb-4">{{ __('Breakdown of assets by building, floor, and room.') }}</p>
             <div class="h-64">
                <canvas id="assetsByLocationChart"></canvas>
            </div>
        </div>

        <!-- Report Card: Assets by Responsible Person -->
        <div class="bg-surface p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-lg text-text-primary mb-1">{{ __('Assets by Responsible Person') }}</h3>
            <p class="text-sm text-text-secondary mb-4">{{ __('List of assets assigned to each responsible person.') }}</p>
             <div class="h-64">
                <canvas id="assetsByResponsiblePersonChart"></canvas>
            </div>
        </div>
        
        <!-- Report Card: Repair Frequency -->
        <div class="lg:col-span-2 bg-surface p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-lg text-text-primary mb-1">{{ __('Repair Frequency') }}</h3>
            <p class="text-sm text-text-secondary mb-4">{{ __('Analysis of how often assets require repair or service.') }}</p>
            <div class="h-80">
                <canvas id="repairFrequencyChart"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Цветовая палитра в стиле нашего дизайна
            const chartColors = {
                primary: 'rgba(193, 39, 45, 0.7)',
                primaryBorder: 'rgba(193, 39, 45, 1)',
                accent: 'rgba(255, 107, 107, 0.7)',
                accentBorder: 'rgba(255, 107, 107, 1)',
                blue: 'rgba(59, 130, 246, 0.7)',
                blueBorder: 'rgba(59, 130, 246, 1)',
                green: 'rgba(34, 197, 94, 0.7)',
                greenBorder: 'rgba(34, 197, 94, 1)',
                yellow: 'rgba(251, 191, 36, 0.7)',
                yellowBorder: 'rgba(251, 191, 36, 1)',
                gray: 'rgba(107, 114, 128, 0.7)',
                grayBorder: 'rgba(107, 114, 128, 1)',
            };

            const chartFont = 'Montserrat';

            // Общие настройки для всех графиков
            Chart.defaults.font.family = chartFont;
            Chart.defaults.plugins.legend.position = 'bottom';

            // 1. Asset Status Summary Chart
            const assetStatusCtx = document.getElementById('assetStatusChart')?.getContext('2d');
            if (assetStatusCtx) {
                const assetStatusData = @json($assetStatusSummary);
                new Chart(assetStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: assetStatusData.map(row => row.name),
                        datasets: [{
                            data: assetStatusData.map(row => row.count),
                            backgroundColor: [chartColors.primary, chartColors.green, chartColors.yellow, chartColors.accent, chartColors.gray],
                            borderWidth: 0,
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            // 2. Asset Age Distribution Chart
            const assetAgeCtx = document.getElementById('assetAgeChart')?.getContext('2d');
            if(assetAgeCtx) {
                const assetAgeData = @json($assetAgeDistribution);
                new Chart(assetAgeCtx, {
                    type: 'bar',
                    data: {
                        labels: assetAgeData.map(row => row.purchase_year),
                        datasets: [{
                            label: '{{ __("Number of Assets") }}',
                            data: assetAgeData.map(row => row.count),
                            backgroundColor: chartColors.primary,
                            borderColor: chartColors.primaryBorder,
                            borderWidth: 1,
                            borderRadius: 4,
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });
            }

            // 3. Repair Frequency Chart
            const repairFrequencyCtx = document.getElementById('repairFrequencyChart')?.getContext('2d');
            if(repairFrequencyCtx) {
                const repairFrequencyData = @json($repairFrequency);
                new Chart(repairFrequencyCtx, {
                    type: 'line',
                    data: {
                        labels: repairFrequencyData.map(row => row.month),
                        datasets: [{
                            label: '{{ __("Number of Repair Requests") }}',
                            data: repairFrequencyData.map(row => row.count),
                            backgroundColor: chartColors.accent,
                            borderColor: chartColors.accentBorder,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });
            }

            // 4. Assets by Location Chart
            const assetsByLocationCtx = document.getElementById('assetsByLocationChart')?.getContext('2d');
            if(assetsByLocationCtx) {
                const assetsByLocationData = @json($assetsByLocation);
                new Chart(assetsByLocationCtx, {
                    type: 'bar',
                    data: {
                        labels: assetsByLocationData.map(row => row.name),
                        datasets: [{
                            label: '{{ __("Number of Assets") }}',
                            data: assetsByLocationData.map(row => row.count),
                            backgroundColor: chartColors.blue,
                            borderColor: chartColors.blueBorder,
                            borderWidth: 1,
                            borderRadius: 4,
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });
            }

            // 5. Assets by Responsible Person Chart
            const assetsByResponsiblePersonCtx = document.getElementById('assetsByResponsiblePersonChart')?.getContext('2d');
            if(assetsByResponsiblePersonCtx) {
                const assetsByResponsiblePersonData = @json($assetsByResponsiblePerson);
                new Chart(assetsByResponsiblePersonCtx, {
                    type: 'bar',
                    data: {
                        labels: assetsByResponsiblePersonData.map(row => row.name),
                        datasets: [{
                            label: '{{ __("Number of Assets") }}',
                            data: assetsByResponsiblePersonData.map(row => row.count),
                            backgroundColor: chartColors.green,
                            borderColor: chartColors.greenBorder,
                            borderWidth: 1,
                            borderRadius: 4,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
