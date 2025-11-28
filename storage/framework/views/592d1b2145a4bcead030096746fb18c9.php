<?php $__env->startSection('title', 'Foreman Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        use App\Models\Nqr;

        $total = Nqr::count();
        $pendingForeman = Nqr::where('status_approval', 'Menunggu Approval Foreman')->count();
        $waitingSect = Nqr::where('status_approval', 'Menunggu Approval Sect Head')->count();
        $completed = Nqr::where('status_approval', 'Selesai')->count();
        $rejected = Nqr::whereIn('status_approval', ['Ditolak Foreman', 'Ditolak Sect Head', 'Ditolak Dept Head', 'Ditolak PPC Head'])->count();
    ?>

    <div class="max-w-7xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Foreman Dashboard</h1>
                    <p class="text-sm text-gray-600">Ringkasan status NQR untuk Foreman</p>
                </div>
                <div class="text-right">
                    <a href="<?php echo e(route('foreman.nqr.index')); ?>"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md shadow hover:bg-red-700">Lihat
                        NQR</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">Total NQR</div>
                    <div class="text-2xl font-semibold text-gray-800"><?php echo e(number_format($total)); ?></div>
                </div>

                <div class="p-4 bg-yellow-50 rounded-lg">
                    <div class="text-sm text-gray-500">Menunggu Approval Foreman</div>
                    <div class="text-2xl font-semibold text-yellow-700"><?php echo e(number_format($pendingForeman)); ?></div>
                </div>

                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="text-sm text-gray-500">Menunggu Approval Sect Head</div>
                    <div class="text-2xl font-semibold text-blue-700"><?php echo e(number_format($waitingSect)); ?></div>
                </div>

                <div class="p-4 bg-green-50 rounded-lg">
                    <div class="text-sm text-gray-500">Selesai</div>
                    <div class="text-2xl font-semibold text-green-700"><?php echo e(number_format($completed)); ?></div>
                </div>
            </div>

            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-800">Terakhir Ditutup / Ditolak</h2>
                <div class="mt-3">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="px-3 py-2 text-sm text-gray-600">No Reg</th>
                                <th class="px-3 py-2 text-sm text-gray-600">Supplier</th>
                                <th class="px-3 py-2 text-sm text-gray-600">Status Approval</th>
                                <th class="px-3 py-2 text-sm text-gray-600">Tanggal</th>
                                <th class="px-3 py-2 text-sm text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = \App\Models\Nqr::orderBy('updated_at', 'desc')->limit(8)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-3 py-2 text-sm"><?php echo e($n->no_reg_nqr); ?></td>
                                    <td class="px-3 py-2 text-sm"><?php echo e($n->nama_supplier); ?></td>
                                    <td class="px-3 py-2 text-sm"><?php echo e($n->status_approval ?? '-'); ?></td>
                                    <td class="px-3 py-2 text-sm"><?php echo e($n->updated_at?->format('d-m-Y H:i') ?? '-'); ?></td>
                                    <td class="px-3 py-2 text-sm">
                                        <a href="<?php echo e(route('foreman.nqr.previewFpdf', $n->id)); ?>" target="_blank"
                                            class="text-sm text-blue-600 hover:underline">Preview</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/foreman/nqr/dashboard.blade.php ENDPATH**/ ?>