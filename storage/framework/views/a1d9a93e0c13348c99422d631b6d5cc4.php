<?php $__env->startSection('title', 'VDD - Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 sm:p-6 md:p-8 lg:p-10 xl:p-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div class="lg:col-span-1 flex flex-col gap-4">
                    <div
                        class="w-full bg-white rounded-xl shadow-sm border border-blue-300 p-3 sm:p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Total NQR</p>
                                <p class="text-lg sm:text-xl md:text-2xl font-bold text-blue-600">
                                    <?php echo e(number_format($nqrStats['total'] ?? 0)); ?>

                                </p>
                                <div class="flex items-center mt-1 text-xs sm:text-sm">
                                    <span class="text-green-600 font-medium"><?php echo e($nqrStats['completed'] ?? 0); ?></span>
                                    <span class="text-gray-500 ml-1">completed</span>
                                </div>
                            </div>
                            <div
                                class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <img src="<?php echo e(asset('image/document.png')); ?>" alt="Document Icon"
                                    class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 object-contain">
                            </div>
                        </div>
                    </div>

                    <div
                        class="w-full bg-white rounded-xl shadow-sm border border-green-300 p-3 sm:p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Total CMR</p>
                                <p class="text-lg sm:text-xl md:text-2xl font-bold text-green-600">
                                    <?php echo e(number_format($cmrStats['total'] ?? 0)); ?>

                                </p>
                                <div class="flex items-center mt-1 text-xs sm:text-sm">
                                    <span class="text-green-600 font-medium"><?php echo e($cmrStats['completed'] ?? 0); ?></span>
                                    <span class="text-gray-500 ml-1">completed</span>
                                </div>
                            </div>
                            <div
                                class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <img src="<?php echo e(asset('image/document.png')); ?>" alt="Document Icon"
                                    class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 object-contain">
                            </div>
                        </div>
                    </div>

                    <div
                        class="w-full bg-white rounded-xl shadow-sm border border-green-100 p-3 sm:p-4 md:p-4 hover:shadow-md transition-shadow duration-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">CMR Status Details</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="p-2 rounded-lg bg-green-50 text-center">
                                <div class="text-xs text-gray-600">Completed</div>
                                <div class="text-sm font-bold text-green-600"><?php echo e($cmrStats['completed'] ?? 0); ?></div>
                            </div>
                            <div class="p-2 rounded-lg bg-yellow-50 text-center">
                                <div class="text-xs text-gray-600">Pending</div>
                                <div class="text-sm font-bold text-yellow-600"><?php echo e($cmrStats['pending'] ?? 0); ?></div>
                            </div>
                            <div class="p-2 rounded-lg bg-red-50 text-center">
                                <div class="text-xs text-gray-600">Rejected</div>
                                <div class="text-sm font-bold text-red-600"><?php echo e($cmrStats['rejected'] ?? 0); ?></div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 md:p-6 md:h-auto lg:h-auto overflow-y-auto hover:shadow-md transition-shadow duration-200">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3">NQR Status Details</h3>
                        <div class="grid grid-cols-3 gap-2 mt-2 sm:mt-4">
                            <div class="p-3 rounded-lg bg-green-50 text-center">
                                <div class="text-xs text-gray-600">Completed</div>
                                <div class="text-sm font-bold text-green-600"><?php echo e($nqrStats['completed'] ?? 0); ?></div>
                            </div>
                            <div class="p-3 rounded-lg bg-yellow-50 text-center">
                                <div class="text-xs text-gray-600">Pending</div>
                                <div class="text-sm font-bold text-yellow-600"><?php echo e($nqrStats['pending'] ?? 0); ?></div>
                            </div>
                            <div class="p-3 rounded-lg bg-red-50 text-center">
                                <div class="text-xs text-gray-600">Rejected</div>
                                <div class="text-sm font-bold text-red-600"><?php echo e($nqrStats['rejected'] ?? 0); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-blue-500 p-3 sm:p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Monthly NQR Trend</h3>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <label for="yearFilter"
                                    class="text-xs sm:text-sm font-medium text-gray-600 mr-2">Year:</label>
                                <select id="yearFilter"
                                    class="px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded-lg">
                                    <?php
                                        $currentYear = date('Y');
                                        $startYear = 2020;
                                    ?>
                                    <?php for($year = $currentYear; $year >= $startYear; $year--): ?>
                                        <option value="<?php echo e($year); ?>" <?php echo e($year == $currentYear ? 'selected' : ''); ?>><?php echo e($year); ?>

                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="flex items-center">
                                <label for="monthFilter"
                                    class="text-xs sm:text-sm font-medium text-gray-600 mr-2">Month:</label>
                                <select id="monthFilter"
                                    class="px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded-lg">
                                    <option value="">All</option>
                                    <?php
                                        $months = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
                                    ?>
                                    <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mKey => $mLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($mKey); ?>"><?php echo e($mLabel); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="relative h-56 sm:h-64 md:h-72 lg:h-80 xl:h-96">
                        <div id="chartLoading"
                            class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-lg"
                            style="display: none;">
                            <div class="flex items-center space-x-2">
                                <div
                                    class="animate-spin rounded-full h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6 border-b-2 border-blue-600">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            // Prepare initial trend data arrays
            const nqrTrendData = <?php echo json_encode($monthlyData['nqr'] ?? []); ?>.slice();
            const cmrTrendData = <?php echo json_encode($monthlyData['cmr'] ?? []); ?>.slice();

            let monthlyChart = new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($monthlyData['labels'] ?? []); ?>,
                    datasets: [
                        {
                            label: 'NQR',
                            data: <?php echo json_encode($monthlyData['nqr'] ?? []); ?>,
                            backgroundColor: 'rgba(147, 51, 234, 0.8)',
                            borderColor: 'rgb(147, 51, 234)',
                            borderWidth: 1,
                            borderRadius: 6,
                            order: 4,
                        }
                        ,
                        {
                            label: 'CMR',
                            data: <?php echo json_encode($monthlyData['cmr'] ?? []); ?>,
                            backgroundColor: 'rgba(249, 115, 22, 0.8)',
                            borderColor: 'rgb(249, 115, 22)',
                            borderWidth: 1,
                            borderRadius: 6,
                            order: 4,
                        }
                        ,
                        {
                            label: 'NQR Trend',
                            data: nqrTrendData,
                            type: 'line',
                            showLine: false,
                            borderColor: 'rgba(147, 51, 234, 1)',
                            backgroundColor: 'transparent',
                            borderWidth: 0,
                            borderDash: [6, 4],
                            pointBackgroundColor: 'rgba(147, 51, 234, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 0,
                            pointRadius: 0,
                            pointHoverRadius: 0,
                            tension: 0,
                            fill: false,
                            order: 1
                        }
                        ,
                        {
                            label: 'CMR Trend',
                            data: cmrTrendData,
                            type: 'line',
                            showLine: false,
                            borderColor: 'rgba(249, 115, 22, 1)',
                            backgroundColor: 'transparent',
                            borderWidth: 0,
                            borderDash: [6, 4],
                            pointBackgroundColor: 'rgba(249, 115, 22, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 0,
                            pointRadius: 0,
                            pointHoverRadius: 0,
                            tension: 0,
                            fill: false,
                            order: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: { enabled: true }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#6B7280' } },
                        y: { beginAtZero: true, ticks: { color: '#6B7280' } }
                    }
                }
            });

            // Data label plugin (disabled for now to match QC behavior)
            const dataLabelPlugin = {
                id: 'dataLabelPlugin',
                afterDatasetsDraw(chart, args, options) {
                    return; // intentionally disabled
                }
            };
            Chart.register(dataLabelPlugin);

            // Add plugin to draw dashed trend lines and nodes matched to bar top coordinates
            const drawTrendLinesPlugin = {
                id: 'drawTrendLinesPlugin',
                afterDatasetsDraw(chart, args, options) {
                    if (!chart || !chart.canvas || chart.canvas.id !== 'monthlyChart') return;
                    const ctx = chart.ctx;
                    ctx.save();

                    chart.data.datasets.forEach((dataset) => {
                        if (!dataset.label || !dataset.label.includes('Trend')) return;
                        const baseLabel = dataset.label.replace(/\s*Trend\s*$/i, '');
                        const barIndex = chart.data.datasets.findIndex(d => d.label === baseLabel && (!d.type || d.type === 'bar'));
                        if (barIndex === -1) return;
                        const barMeta = chart.getDatasetMeta(barIndex);
                        if (!barMeta || !barMeta.data) return;

                        const nodeOffset = 6; // px above bar top
                        const points = [];
                        for (let i = 0; i < barMeta.data.length; i++) {
                            const barEl = barMeta.data[i];
                            if (!barEl || typeof barEl.x === 'undefined' || typeof barEl.y === 'undefined') continue;
                            const base = (typeof barEl.base !== 'undefined') ? barEl.base : null;
                            const height = base !== null ? Math.abs(base - barEl.y) : (barEl.height || 0);
                            const value = dataset.data[i];
                            if (!value || height <= 0) continue;
                            points.push({ x: barEl.x, y: barEl.y - nodeOffset, value: value });
                        }

                        if (points.length > 0) {
                            ctx.beginPath();
                            ctx.setLineDash(dataset.borderDash || [6, 4]);
                            ctx.strokeStyle = dataset.borderColor || 'rgba(0,0,0,1)';
                            ctx.lineWidth = dataset.borderWidth || 2;
                            ctx.moveTo(points[0].x, points[0].y);
                            for (let i = 1; i < points.length; i++) ctx.lineTo(points[i].x, points[i].y);
                            ctx.stroke();
                        }

                        const isHoverActive = chart && chart.tooltip && Array.isArray(chart.tooltip._active) && chart.tooltip._active.length > 0;
                        if (!isHoverActive) {
                            points.forEach(p => {
                                ctx.beginPath(); ctx.fillStyle = '#ffffff'; ctx.arc(p.x, p.y, 6, 0, Math.PI * 2); ctx.fill();
                                ctx.beginPath(); ctx.fillStyle = dataset.borderColor || 'rgba(0,0,0,1)'; ctx.arc(p.x, p.y, 4, 0, Math.PI * 2); ctx.fill();
                            });
                        }
                    });

                    ctx.setLineDash([]);
                    ctx.restore();
                }
            };
            Chart.register(drawTrendLinesPlugin);

            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const chartLoading = document.getElementById('chartLoading');

            function fetchAndUpdateChart(year, month = null) {
                chartLoading.style.display = 'flex';

                const params = new URLSearchParams();
                if (year) params.append('year', year);
                if (month) params.append('month', month);

                fetch(`<?php echo e(route('dashboard.monthlyData')); ?>?` + params.toString(), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        let labels = data.labels || [];

                        // If month is provided, convert labels to day numbers 1..N
                        if (month) {
                            const lastDay = new Date(parseInt(year, 10), parseInt(month, 10), 0).getDate();
                            labels = [];
                            for (let d = 1; d <= lastDay; d++) labels.push(String(d));
                        }

                        function normalizeSeries(series, expectedLength) {
                            if (!series) return new Array(expectedLength).fill(0);
                            if (Array.isArray(series)) {
                                const arr = series.slice(0, expectedLength);
                                while (arr.length < expectedLength) arr.push(0);
                                return arr;
                            }
                            if (typeof series === 'object') {
                                const out = [];
                                for (let i = 1; i <= expectedLength; i++) {
                                    out.push(series[i] || 0);
                                }
                                return out;
                            }
                            return new Array(expectedLength).fill(0);
                        }

                        const expectedLen = labels.length || (data.nqr && data.nqr.length) || 0;

                        const nqrSeries = normalizeSeries(data.nqr, expectedLen);
                        const cmrSeries = normalizeSeries(data.cmr, expectedLen);

                        monthlyChart.data.labels = labels;
                        if (monthlyChart.data.datasets && monthlyChart.data.datasets.length > 0) {
                            monthlyChart.data.datasets[0].data = nqrSeries;
                            if (monthlyChart.data.datasets.length > 1) {
                                monthlyChart.data.datasets[1].data = cmrSeries;
                            }
                            // Update trend datasets if present so trend lines redraw for filtered data
                            const nqrTrendIdx = monthlyChart.data.datasets.findIndex(d => d.label === 'NQR Trend');
                            const cmrTrendIdx = monthlyChart.data.datasets.findIndex(d => d.label === 'CMR Trend');
                            if (nqrTrendIdx !== -1) monthlyChart.data.datasets[nqrTrendIdx].data = nqrSeries.slice();
                            if (cmrTrendIdx !== -1) monthlyChart.data.datasets[cmrTrendIdx].data = cmrSeries.slice();
                        }
                        monthlyChart.update('active');
                        setTimeout(() => chartLoading.style.display = 'none', 150);
                    })
                    .catch(err => {
                        console.error('Failed to load monthly NQR/CMR data', err);
                        chartLoading.style.display = 'none';
                    });
            }

            // wire up both filters
            yearFilter.addEventListener('change', function () {
                const selectedYear = this.value;
                const selectedMonth = monthFilter.value || null;
                fetchAndUpdateChart(selectedYear, selectedMonth);
            });

            monthFilter.addEventListener('change', function () {
                const selectedMonth = this.value || null;
                const selectedYear = yearFilter.value;
                fetchAndUpdateChart(selectedYear, selectedMonth);
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/procurement/dashboard.blade.php ENDPATH**/ ?>