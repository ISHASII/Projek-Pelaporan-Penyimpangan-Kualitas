<?php $__env->startSection('title', 'Dept Head Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6 lg:p-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <div
                    class="bg-white rounded-xl shadow-sm border border-blue-500 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total LPK</p>
                            <p class="text-2xl md:text-3xl font-bold text-blue-600"><?php echo e(number_format($lpkStats['total'])); ?>

                            </p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="text-green-600 font-medium">+<?php echo e($lpkStats['approved']); ?></span>
                                <span class="text-gray-500 ml-1">approved</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <img src="<?php echo e(asset('image/document.png')); ?>" alt="Document Icon"
                                class="w-5 h-5 md:w-6 md:h-6 object-contain">
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-purple-500 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total NQR</p>
                            <p class="text-2xl md:text-3xl font-bold text-purple-600">
                                <?php echo e(number_format($nqrStats['total'])); ?>

                            </p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="text-green-600 font-medium">+<?php echo e($nqrStats['completed']); ?></span>
                                <span class="text-gray-500 ml-1">completed</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <img src="<?php echo e(asset('image/document.png')); ?>" alt="Document Icon"
                                class="w-5 h-5 md:w-6 md:h-6 object-contain">
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-orange-300 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total CMR</p>
                            <p class="text-2xl md:text-3xl font-bold text-orange-600">
                                <?php echo e(number_format($cmrStats['total'])); ?>

                            </p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="text-green-600 font-medium">+<?php echo e($cmrStats['completed']); ?></span>
                                <span class="text-gray-500 ml-1">completed</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <img src="<?php echo e(asset('image/document.png')); ?>" alt="Document Icon"
                                class="w-5 h-5 md:w-6 md:h-6 object-contain">
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-red-500 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Documents</p>
                            <p class="text-2xl md:text-3xl font-bold text-gray-900">
                                <?php echo e(number_format($lpkStats['total'] + $nqrStats['total'] + $cmrStats['total'])); ?>

                            </p>
                            <div class="flex items-center mt-2 text-sm">
                                <span
                                    class="text-yellow-600 font-medium"><?php echo e($lpkStats['pending'] + $nqrStats['pending'] + $cmrStats['pending']); ?></span>
                                <span class="text-gray-500 ml-1">pending</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <img src="<?php echo e(asset('image/chart.png')); ?>" alt="Document Icon"
                                class="w-5 h-5 md:w-6 md:h-6 object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-4 md:mb-6">
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-green-500 p-4 md:p-6">
                    <div
                        class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4 md:mb-6 space-y-4 md:space-y-0">
                        <div
                            class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                            <h3 class="text-base md:text-lg font-semibold text-gray-900">Monthly Document Trends</h3>
                            <div class="flex items-center space-x-2">
                                <label for="yearFilter" class="text-sm font-medium text-gray-600">Year:</label>
                                <select id="yearFilter"
                                    class="px-2 py-1 md:px-3 md:py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <?php
                                        $currentYear = date('Y');
                                        $startYear = 2020; // Adjust as needed
                                    ?>
                                    <?php for($year = $currentYear; $year >= $startYear; $year--): ?>
                                        <option value="<?php echo e($year); ?>" <?php echo e($year == $currentYear ? 'selected' : ''); ?>><?php echo e($year); ?>

                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="flex items-center space-x-2">
                                <label for="monthFilter" class="text-sm font-medium text-gray-600">Month:</label>
                                <select id="monthFilter"
                                    class="px-2 py-1 md:px-3 md:py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                        <div
                            class="flex flex-wrap items-center space-x-2 md:space-x-4 text-xs md:text-sm w-full md:w-auto justify-start md:justify-end">
                            <div class="flex items-center mb-2 md:mb-0">
                                <div class="w-2 h-2 md:w-3 md:h-3 bg-blue-500 rounded-full mr-1 md:mr-2"></div>
                                <span class="text-gray-600">LPK</span>
                            </div>
                            <div class="flex items-center mb-2 md:mb-0">
                                <div class="w-2 h-2 md:w-3 md:h-3 bg-purple-500 rounded-full mr-1 md:mr-2"></div>
                                <span class="text-gray-600">NQR</span>
                            </div>
                            <div class="flex items-center mb-2 md:mb-0">
                                <div class="w-2 h-2 md:w-3 md:h-3 bg-orange-500 rounded-full mr-1 md:mr-2"></div>
                                <span class="text-gray-600">CMR</span>
                            </div>
                        </div>
                    </div>

                    <!-- Legend for Trend Lines -->
                    <div class="flex flex-wrap items-center gap-3 md:gap-4 text-xs md:text-sm mb-4">
                        <span class="text-gray-500 font-medium">Trend:</span>
                        <div class="flex items-center">
                            <div class="w-6 h-0.5 mr-1.5" style="border-top: 2px dashed #3b82f6;"></div>
                            <span class="text-gray-600">LPK</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-0.5 mr-1.5" style="border-top: 2px dashed #9333ea;"></div>
                            <span class="text-gray-600">NQR</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-0.5 mr-1.5" style="border-top: 2px dashed #f97316;"></div>
                            <span class="text-gray-600">CMR</span>
                        </div>
                    </div>

                    <div class="relative h-64 sm:h-72 md:h-80">
                        <div id="chartLoading"
                            class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-lg"
                            style="display: none;">
                            <div class="flex items-center space-x-2">
                                <div class="animate-spin rounded-full h-5 w-5 md:h-6 md:w-6 border-b-2 border-blue-600">
                                </div>
                                <span class="text-xs md:text-sm text-gray-600">Loading chart...</span>
                            </div>
                        </div>
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-green-500 p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900">Status Distribution</h3>
                    </div>
                    <div class="relative h-64 sm:h-72 md:h-80 flex items-center justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-4 space-y-2 text-xs md:text-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-2 h-2 md:w-3 md:h-3 bg-green-500 rounded-full mr-1 md:mr-2"></div>
                                <span class="text-gray-600">Approved/Completed</span>
                            </div>
                            <span class="font-semibold text-gray-900"><?php echo e($statusDistribution['approved']); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-2 h-2 md:w-3 md:h-3 bg-yellow-500 rounded-full mr-1 md:mr-2"></div>
                                <span class="text-gray-600">Pending</span>
                            </div>
                            <span class="font-semibold text-gray-900"><?php echo e($statusDistribution['pending']); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-2 h-2 md:w-3 md:h-3 bg-red-500 rounded-full mr-1 md:mr-2"></div>
                                <span class="text-gray-600">Rejected</span>
                            </div>
                            <span class="font-semibold text-gray-900"><?php echo e($statusDistribution['rejected']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-blue-500 p-4 md:p-6">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">LPK Status Details</h3>
                    <div class="space-y-2 md:space-y-3">
                        <div class="flex items-center justify-between p-2 md:p-3 bg-green-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 md:mr-3"></div>
                                <span class="text-xs md:text-sm font-medium text-gray-700">Approved</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-green-600"><?php echo e($lpkStats['approved']); ?></span>
                        </div>
                        <div class="flex items-center justify-between p-2 md:p-3 bg-yellow-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 md:mr-3"></div>
                                <span class="text-xs md:text-sm font-medium text-gray-700">Pending</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-yellow-600"><?php echo e($lpkStats['pending']); ?></span>
                        </div>
                        <div class="flex items-center justify-between p-2 md:p-3 bg-red-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2 md:mr-3"></div>
                                <span class="text-xs md:text-sm font-medium text-gray-700">Rejected</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-red-600"><?php echo e($lpkStats['rejected']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-purple-500 p-4 md:p-6">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">NQR Status Details</h3>
                    <div class="space-y-2 md:space-y-3">
                        <div class="flex items-center justify-between p-2 md:p-3 bg-green-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 md:mr-3"></div>
                                <span class="text-xs md:text-sm font-medium text-gray-700">Completed</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-green-600"><?php echo e($nqrStats['completed']); ?></span>
                        </div>
                        <div class="flex items-center justify-between p-2 md:p-3 bg-yellow-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 md:mr-3"></div>
                                <span class="text-xs md:text-sm font-medium text-gray-700">Pending</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-yellow-600"><?php echo e($nqrStats['pending']); ?></span>
                        </div>
                        <div class="flex items-center justify-between p-2 md:p-3 bg-red-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2 md:mr-3"></div>
                                <span class="text-xs md:text-sm font-medium text-gray-700">Rejected</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-red-600"><?php echo e($nqrStats['rejected']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-orange-500 p-4 md:p-6">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">CMR Status Details</h3>
                    <div class="space-y-2 md:space-y-3">
                        <div class="flex items-center justify-between p-2 md:p-3 bg-green-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 md:mr-3"></div>
                                <span class="text-xs md:text-sm font-medium text-gray-700">Completed</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-green-600"><?php echo e($cmrStats['completed']); ?></span>
                        </div>
                        <div class="flex items-center justify-between p-2 md:p-3 bg-yellow-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 md:mr-3"></div>
                                <span class="text-xs md:text-sm font-medium text-gray-700">Pending</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-yellow-600"><?php echo e($cmrStats['pending']); ?></span>
                        </div>
                        <div class="flex items-center justify-between p-2 md:p-3 bg-red-100 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2 md:mr-3"></div>
                                <span class="text-xs md:text-sm font-medium text-gray-700">Rejected</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-red-600"><?php echo e($cmrStats['rejected']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Trend lines will use the raw monthly values so the line goes up/down
            // exactly as the bar chart values do.

            const lpkData = <?php echo json_encode($monthlyData['lpk']); ?>;
            const nqrData = <?php echo json_encode($monthlyData['nqr']); ?>;
            const cmrData = <?php echo json_encode($monthlyData['cmr']); ?>;

            // Use raw monthly data for trend lines so the line follows monthly ups/downs
            const lpkTrendData = lpkData.slice();
            const nqrTrendData = nqrData.slice();
            const cmrTrendData = cmrData.slice();

            // Simple passthrough for AJAX updates to keep API consistent
            function calculateTrendLine(data) {
                return Array.isArray(data) ? data.slice() : [];
            }

            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            let monthlyChart = new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($monthlyData['labels']); ?>,
                    datasets: [
                        {
                            label: 'LPK',
                            data: lpkData,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1,
                            borderRadius: 4,
                            order: 4
                        },
                        {
                            label: 'NQR',
                            data: nqrData,
                            backgroundColor: 'rgba(147, 51, 234, 0.8)',
                            borderColor: 'rgb(147, 51, 234)',
                            borderWidth: 1,
                            borderRadius: 4,
                            order: 4
                        },
                        {
                            label: 'CMR',
                            data: cmrData,
                            backgroundColor: 'rgba(249, 115, 22, 0.8)',
                            borderColor: 'rgb(249, 115, 22)',
                            borderWidth: 1,
                            borderRadius: 4,
                            order: 4
                        },
                        {
                            label: 'LPK Trend',
                            data: lpkTrendData,
                            type: 'line',
                            // hide built-in Chart.js rendering; we'll draw the line manually
                            showLine: false,
                            borderColor: 'rgba(59, 130, 246, 1)',
                            backgroundColor: 'transparent',
                            borderWidth: 0,
                            borderDash: [6, 4],
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 0,
                            pointRadius: 0,
                            pointHoverRadius: 0,
                            tension: 0,
                            fill: false,
                            order: 1
                        },
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
                            order: 2
                        },
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
                            order: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            // Filter out any 'Trend' datasets from tooltip items (LPK/NQR/CMR Trend)
                            filter: function (tooltipItem) {
                                return !(tooltipItem && tooltipItem.dataset && tooltipItem.dataset.label && tooltipItem.dataset.label.includes('Trend'));
                            },
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                afterBody: function (context) {
                                    let total = 0;
                                    context.forEach(function (item) {
                                        // Only count bar chart data (LPK, NQR, CMR), not trend lines
                                        if (!item.dataset.label.includes('Trend')) {
                                            total += item.parsed.y || 0;
                                        }
                                    });
                                    return '\nTotal: ' + total;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    size: 10,
                                },
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    size: 10,
                                }
                            }
                        }
                    },
                    elements: {
                        bar: {
                            borderSkipped: false,
                        }
                    }
                }
            });

            const dataLabelPlugin = {
                id: 'dataLabelPlugin',
                afterDatasetsDraw(chart, args, options) {
                    // Disabled: numeric labels above bars are hidden per user request.
                    return;
                }
            };

            Chart.register(dataLabelPlugin);

            // Plugin to draw trend nodes aligned exactly on top of their corresponding bars
            // Plugin to draw dashed trend lines and nodes exactly using bar top coordinates
            const drawTrendLinesPlugin = {
                id: 'drawTrendLinesPlugin',
                afterDatasetsDraw(chart, args, options) {
                    if (!chart || !chart.canvas || chart.canvas.id !== 'monthlyChart') return;

                    const ctx = chart.ctx;
                    ctx.save();

                    chart.data.datasets.forEach((dataset, datasetIndex) => {
                        if (!dataset.label || !dataset.label.includes('Trend')) return;

                        // base label e.g. 'LPK Trend' -> 'LPK'
                        const baseLabel = dataset.label.replace(/\s*Trend\s*$/i, '');
                        const barIndex = chart.data.datasets.findIndex(d => d.label === baseLabel && (!d.type || d.type === 'bar'));
                        if (barIndex === -1) return;

                        const barMeta = chart.getDatasetMeta(barIndex);
                        if (!barMeta || !barMeta.data) return;

                        // Build array of points from bar elements so x,y match bar tops exactly.
                        // We'll offset the y slightly above the bar top so the node and dashed
                        // line sit neatly above rounded bar corners.
                        const nodeOffset = 6; // px above bar top
                        const points = [];
                        for (let i = 0; i < barMeta.data.length; i++) {
                            const barEl = barMeta.data[i];
                            if (!barEl || typeof barEl.x === 'undefined' || typeof barEl.y === 'undefined') continue;

                            // Determine bar height; skip if zero (no visible bar)
                            const base = (typeof barEl.base !== 'undefined') ? barEl.base : null;
                            const height = base !== null ? Math.abs(base - barEl.y) : (barEl.height || 0);

                            const value = dataset.data[i];
                            if (!value || height <= 0) {
                                // skip points with no value or no visible bar
                                continue;
                            }

                            points.push({ x: barEl.x, y: barEl.y - nodeOffset, value: value });
                        }

                        // Draw dashed polyline connecting the bar-top points (only valid points)
                        if (points.length > 0) {
                            ctx.beginPath();
                            ctx.setLineDash(dataset.borderDash || [6, 4]);
                            ctx.strokeStyle = dataset.borderColor || 'rgba(0,0,0,1)';
                            ctx.lineWidth = dataset.borderWidth || 2;

                            ctx.moveTo(points[0].x, points[0].y);
                            for (let i = 1; i < points.length; i++) {
                                ctx.lineTo(points[i].x, points[i].y);
                            }
                            ctx.stroke();
                        }

                        // Draw nodes on each bar top for this dataset unless the chart is being hovered
                        // (when tooltip is active) — this prevents nodes from popping on hover.
                        const isHoverActive = chart && chart.tooltip && Array.isArray(chart.tooltip._active) && chart.tooltip._active.length > 0;
                        if (!isHoverActive) {
                            points.forEach(p => {
                                // outer white circle for contrast
                                ctx.beginPath();
                                ctx.fillStyle = '#ffffff';
                                ctx.arc(p.x, p.y, 6, 0, Math.PI * 2);
                                ctx.fill();

                                // inner colored circle
                                ctx.beginPath();
                                ctx.fillStyle = dataset.borderColor || 'rgba(0,0,0,1)';
                                ctx.arc(p.x, p.y, 4, 0, Math.PI * 2);
                                ctx.fill();
                            });
                        }
                    });

                    ctx.setLineDash([]);
                    ctx.restore();
                }
            };

            Chart.register(drawTrendLinesPlugin);

            const doughnutLabelPlugin = {
                id: 'doughnutLabelPlugin',
                afterDraw(chart, args, options) {
                    if (!chart || !chart.canvas || chart.canvas.id !== 'statusChart') return;

                    const ctx = chart.ctx;
                    ctx.save();

                    const meta = chart.getDatasetMeta(0);
                    const data = chart.data.datasets[0].data;
                    const total = data.reduce((a, b) => a + b, 0);

                    meta.data.forEach((arc, i) => {
                        const value = data[i];

                        if (value === 0 || value === null || value === undefined) return;

                        const startAngle = arc.startAngle;
                        const endAngle = arc.endAngle;
                        const midAngle = (startAngle + endAngle) / 2;

                        const innerR = arc.innerRadius || 0;
                        const r = innerR > 0 ? (arc.outerRadius + innerRadius) / 2 : arc.outerRadius * 0.6;

                        const x = arc.x + Math.cos(midAngle) * r;
                        const y = arc.y + Math.sin(midAngle) * r;

                        const distFromCenter = Math.hypot(x - arc.x, y - arc.y);
                        const minLabelGap = 12;
                        if (innerR > 0 && distFromCenter <= innerR + minLabelGap) return;

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        ctx.fillStyle = '#ffffff';
                        ctx.font = '600 10px ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial';
                        ctx.fillText(value.toLocaleString(), x, y - 6);

                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                        ctx.font = '500 9px ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial';
                        ctx.fillText(percentage + '%', x, y + 9);
                    });

                    ctx.restore();
                }
            };

            Chart.register(doughnutLabelPlugin);

            const yearFilter = document.getElementById('yearFilter');
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
                        // Normalize response into arrays for labels and datasets.
                        // If month is provided, ensure labels are day numbers 1..N for that month.
                        let labels = data.labels || [];

                        if (month) {
                            // compute last day of month
                            const lastDay = new Date(parseInt(year, 10), parseInt(month, 10), 0).getDate();
                            labels = [];
                            for (let d = 1; d <= lastDay; d++) labels.push(String(d));
                        }

                        // Helper to normalize data which might be array or object keyed by day
                        function normalizeSeries(series, expectedLength) {
                            if (!series) return new Array(expectedLength).fill(0);
                            if (Array.isArray(series)) {
                                // If array length matches expected, use it; otherwise pad/truncate
                                const arr = series.slice(0, expectedLength);
                                while (arr.length < expectedLength) arr.push(0);
                                return arr;
                            }
                            if (typeof series === 'object') {
                                // series keyed by day (1..N) -> value
                                const out = [];
                                for (let i = 1; i <= expectedLength; i++) {
                                    out.push(series[i] || 0);
                                }
                                return out;
                            }
                            return new Array(expectedLength).fill(0);
                        }

                        const expectedLen = labels.length || (data.lpk && data.lpk.length) || 0;

                        const lpkSeries = normalizeSeries(data.lpk, expectedLen);
                        const nqrSeries = normalizeSeries(data.nqr, expectedLen);
                        const cmrSeries = normalizeSeries(data.cmr, expectedLen);

                        monthlyChart.data.labels = labels;
                        monthlyChart.data.datasets[0].data = lpkSeries;
                        monthlyChart.data.datasets[1].data = nqrSeries;
                        monthlyChart.data.datasets[2].data = cmrSeries;

                        // For trend datasets we reuse the same normalized arrays so lines follow bars/dates
                        monthlyChart.data.datasets[3].data = lpkSeries.slice();
                        monthlyChart.data.datasets[4].data = nqrSeries.slice();
                        monthlyChart.data.datasets[5].data = cmrSeries.slice();

                        monthlyChart.update('active');

                        setTimeout(() => {
                            chartLoading.style.display = 'none';
                        }, 150);
                    })
                    .catch(error => {
                        console.error('Error fetching chart data:', error);
                        chartLoading.style.display = 'none';

                        alert('Failed to load chart data. Please try again.');
                    });
            }

            // wire up both filters
            const monthFilter = document.getElementById('monthFilter');

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

            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: ['Approved/Completed', 'Pending', 'Rejected'],
                    datasets: [{
                        data: [
                                                                                                                                        <?php echo e($statusDistribution['approved']); ?>,
                                                                                                                                        <?php echo e($statusDistribution['pending']); ?>,
                            <?php echo e($statusDistribution['rejected']); ?>

                        ],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(234, 179, 8, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgb(34, 197, 94)',
                            'rgb(234, 179, 8)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 2,
                        hoverBackgroundColor: [
                            'rgba(34, 197, 94, 1)',
                            'rgba(234, 179, 8, 1)',
                            'rgba(239, 68, 68, 1)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function (context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((context.parsed / total) * 100);
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            const cards = document.querySelectorAll('.hover\\:shadow-md');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>

    <style>
        .hover\:shadow-md {
            transition: all 0.3s ease;
        }

        canvas {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (max-width: 640px) {
            .grid {
                gap: 1rem;
            }

            .p-4 {
                padding: 0.75rem;
            }

            .text-2xl {
                font-size: 1.5rem;
            }

            .h-64 {
                height: 14rem;
            }
        }

        @media (min-width: 641px) and (max-width: 768px) {
            .h-72 {
                height: 18rem;
            }
        }

        @media (max-width: 1024px) {
            .flex-wrap {
                flex-wrap: wrap;
            }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/ppchead/dashboard.blade.php ENDPATH**/ ?>