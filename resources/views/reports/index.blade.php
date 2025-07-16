<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('Overview Reports') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Report Card: Asset Status Summary -->
                        <div class="bg-neutral-50 p-6 rounded-lg shadow">
                            <h4 class="font-semibold text-md text-primary-800 mb-2">{{ __('Asset Status Summary') }}</h4>
                            <p class="text-neutral-700 mb-4">{{ __('Summary of assets by their current status (e.g., operational, under repair, written off).') }}</p>
                            <div class="mt-4">
                                <canvas id="assetStatusChart"></canvas>
                            </div>
                        </div>

                        <!-- Report Card: Asset Age Distribution -->
                        <div class="bg-neutral-50 p-6 rounded-lg shadow">
                            <h4 class="font-semibold text-md text-primary-800 mb-2">{{ __('Asset Age Distribution') }}</h4>
                            <p class="text-neutral-700 mb-4">{{ __('Distribution of assets by their purchase year or age.') }}</p>
                            <div class="mt-4">
                                <canvas id="assetAgeChart"></canvas>
                            </div>
                        </div>

                        <!-- Report Card: Repair Frequency -->
                        <div class="bg-neutral-50 p-6 rounded-lg shadow">
                            <h4 class="font-semibold text-md text-primary-800 mb-2">{{ __('Repair Frequency') }}</h4>
                            <p class="text-neutral-700 mb-4">{{ __('Analysis of how often assets require repair or service.') }}</p>
                            <div class="mt-4">
                                <canvas id="repairFrequencyChart"></canvas>
                            </div>
                        </div>

                        <!-- Report Card: Assets by Location -->
                        <div class="bg-neutral-50 p-6 rounded-lg shadow">
                            <h4 class="font-semibold text-md text-primary-800 mb-2">{{ __('Assets by Location') }}</h4>
                            <p class="text-neutral-700 mb-4">{{ __('Breakdown of assets by building, floor, and room.') }}</p>
                            <div class="mt-4">
                                <canvas id="assetsByLocationChart"></canvas>
                            </div>
                        </div>

                        <!-- Report Card: Assets by Responsible Person -->
                        <div class="bg-neutral-50 p-6 rounded-lg shadow">
                            <h4 class="font-semibold text-md text-primary-800 mb-2">{{ __('Assets by Responsible Person') }}</h4>
                            <p class="text-neutral-700 mb-4">{{ __('List of assets assigned to each responsible person.') }}</p>
                            <div class="mt-4">
                                <canvas id="assetsByResponsiblePersonChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Asset Status Summary Chart
            const assetStatusCtx = document.getElementById('assetStatusChart').getContext('2d');
            const assetStatusData = @json($assetStatusSummary);
            new Chart(assetStatusCtx, {
                type: 'pie',
                data: {
                    labels: assetStatusData.map(row => row.name),
                    datasets: [{
                        label: '{{ __("Number of Assets") }}',
                        data: assetStatusData.map(row => row.count),
                        backgroundColor: [
                            'rgba(99, 102, 241, 0.6)', // primary-500
                            'rgba(34, 197, 94, 0.6)',  // secondary-500
                            'rgba(244, 63, 94, 0.6)',  // rose-500
                            'rgba(251, 191, 36, 0.6)', // amber-500
                            'rgba(148, 163, 184, 0.6)',// neutral-400
                        ],
                        borderColor: [
                            'rgba(99, 102, 241, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(244, 63, 94, 1)',
                            'rgba(251, 191, 36, 1)',
                            'rgba(148, 163, 184, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: '{{ __("Assets by Status") }}'
                        }
                    }
                }
            });

            // Asset Age Distribution Chart
            const assetAgeCtx = document.getElementById('assetAgeChart').getContext('2d');
            const assetAgeData = @json($assetAgeDistribution);
            new Chart(assetAgeCtx, {
                type: 'bar',
                data: {
                    labels: assetAgeData.map(row => row.purchase_year),
                    datasets: [{
                        label: '{{ __("Number of Assets") }}',
                        data: assetAgeData.map(row => row.count),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: '{{ __("Assets by Purchase Year") }}'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Count") }}'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Purchase Year") }}'
                            }
                        }
                    }
                }
            });

            // Repair Frequency Chart
            const repairFrequencyCtx = document.getElementById('repairFrequencyChart').getContext('2d');
            const repairFrequencyData = @json($repairFrequency);
            new Chart(repairFrequencyCtx, {
                type: 'line',
                data: {
                    labels: repairFrequencyData.map(row => row.month),
                    datasets: [{
                        label: '{{ __("Number of Repair Requests") }}',
                        data: repairFrequencyData.map(row => row.count),
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: '{{ __("Repair Requests by Month (Last 12 Months)") }}'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Count") }}'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Month") }}'
                            }
                        }
                    }
                }
            });

            // Assets by Location Chart
            const assetsByLocationCtx = document.getElementById('assetsByLocationChart').getContext('2d');
            const assetsByLocationData = @json($assetsByLocation);
            new Chart(assetsByLocationCtx, {
                type: 'bar',
                data: {
                    labels: assetsByLocationData.map(row => row.name),
                    datasets: [{
                        label: '{{ __("Number of Assets") }}',
                        data: assetsByLocationData.map(row => row.count),
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: '{{ __("Assets by Location") }}'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Count") }}'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Location") }}'
                            }
                        }
                    }
                }
            });

            // Assets by Responsible Person Chart
            const assetsByResponsiblePersonCtx = document.getElementById('assetsByResponsiblePersonChart').getContext('2d');
            const assetsByResponsiblePersonData = @json($assetsByResponsiblePerson);
            new Chart(assetsByResponsiblePersonCtx, {
                type: 'bar',
                data: {
                    labels: assetsByResponsiblePersonData.map(row => row.name),
                    datasets: [{
                        label: '{{ __("Number of Assets") }}',
                        data: assetsByResponsiblePersonData.map(row => row.count),
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Горизонтальные бары
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: '{{ __("Top 10 Responsible Persons by Assets") }}'
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Count") }}'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: '{{ __("Responsible Person") }}'
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
