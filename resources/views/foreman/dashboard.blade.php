@extends('layouts.navbar')

@section('title', 'Foreman Dashboard')

@section('content')
    @php
        // Use prepared data from controller: $nqrStats and $monthlyData
        $nqrStats = $nqrStats ?? ['total' => 0, 'completed' => 0, 'pending' => 0, 'rejected' => 0];
        $labels = $monthlyData['labels'] ?? [];
        $nqrSeries = $monthlyData['nqr'] ?? [];
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 sm:p-6 md:p-8 lg:p-10 xl:p-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div class="lg:col-span-1 flex flex-col gap-4">
                    <div
                        class="w-full bg-white rounded-xl shadow-sm border border-green-300 p-3 sm:p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Total NQR</p>
                                <p class="text-lg sm:text-xl md:text-2xl font-bold text-green-600">
                                    {{ number_format($nqrStats['total'] ?? 0) }}
                                </p>
                                <div class="flex items-center mt-1 text-xs sm:text-sm">
                                    <span class="text-green-600 font-medium">+{{ $nqrStats['completed'] ?? 0 }}</span>
                                    <span class="text-gray-500 ml-1">completed</span>
                                </div>
                            </div>

                            <div
                                class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <img src="{{ asset('image/document.png') }}" alt="Document Icon"
                                    class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 object-contain">
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-green-500 p-3 sm:p-4 md:p-6 md:h-72 lg:h-80 overflow-y-auto">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3">NQR Status Details</h3>
                        <div class="space-y-2 sm:space-y-3 mt-8 sm:mt-10">
                            <div class="flex items-center justify-between p-2 sm:p-3 md:p-4 bg-green-100 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2 sm:mr-3"></div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Completed</span>
                                </div>
                                <span
                                    class="text-xs sm:text-sm font-bold text-green-600">{{ $nqrStats['completed'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2 sm:p-3 md:p-4 bg-yellow-100 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 sm:mr-3"></div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Pending</span>
                                </div>
                                <span
                                    class="text-xs sm:text-sm font-bold text-yellow-600">{{ $nqrStats['pending'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2 sm:p-3 md:p-4 bg-red-100 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2 sm:mr-3"></div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Rejected</span>
                                </div>
                                <span
                                    class="text-xs sm:text-sm font-bold text-red-600">{{ $nqrStats['rejected'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-green-500 p-3 sm:p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Monthly NQR Trend</h3>
                        <div class="flex items-center">
                            <label for="yearFilter" class="text-xs sm:text-sm font-medium text-gray-600 mr-2">Year:</label>
                            <select id="yearFilter" class="px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded-lg">
                                @php
                                    $currentYear = date('Y');
                                    $startYear = 2020;
                                @endphp
                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="relative h-56 sm:h-64 md:h-72 lg:h-80 xl:h-96">
                        <div id="chartLoading"
                            class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-lg"
                            style="display: none;">
                            <div class="flex items-center space-x-2">
                                <div
                                    class="animate-spin rounded-full h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6 border-b-2 border-green-600">
                                </div>
                                <span class="text-xs sm:text-sm text-gray-600">Loading chart...</span>
                            </div>
                        </div>
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        let monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels ?? []) !!},
                datasets: [
                    {
                        label: 'NQR',
                        data: {!! json_encode($nqrSeries ?? []) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.9)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#6B7280' } },
                    y: { beginAtZero: true, ticks: { color: '#6B7280' } }
                }
            }
        });

        const yearFilter = document.getElementById('yearFilter');
        const chartLoading = document.getElementById('chartLoading');

        yearFilter.addEventListener('change', function () {
            const selectedYear = this.value;
            chartLoading.style.display = 'flex';

            fetch(`{{ route('dashboard.monthlyData') }}?year=${selectedYear}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    monthlyChart.data.labels = data.labels;
                    monthlyChart.data.datasets[0].data = data.nqr;
                    monthlyChart.update('active');
                    setTimeout(() => chartLoading.style.display = 'none', 250);
                })
                .catch(err => {
                    console.error('Failed to load monthly NQR data', err);
                    chartLoading.style.display = 'none';
                });
        });
    });
</script>

<style>
    .hover\:shadow-md {
        transition: all 0.3s ease;
    }

    canvas {
        animation: fadeIn 0.45s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0
        }

        to {
            opacity: 1
        }
    }

    @media (max-width: 640px) {
        .p-4 {
            padding: 0.75rem;
        }

        .text-xl {
            font-size: 1.125rem;
        }

        .h-80 {
            height: 16rem;
        }
    }

    @media (min-width: 1536px) {
        .p-12 {
            padding: 3rem;
        }
    }
</style>