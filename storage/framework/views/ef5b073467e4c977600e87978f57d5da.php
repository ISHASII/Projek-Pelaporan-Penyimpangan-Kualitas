<?php $__env->startSection('content'); ?>
    <div class="w-full m-0 p-0 -mt-0">
        <div class="m-0">
            <div
                class="bg-white rounded-none shadow-none overflow-hidden border-t-0 border-l-0 border-r-0 border-gray-100 w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                    </div>

                    <form method="GET" action="<?php echo e(route('qc.lpk.index')); ?>" class="mb-4">
                        <?php

                            $dateValue = '';
                            if (request('date')) {
                                try {
                                    $dateValue = \Carbon\Carbon::parse(request('date'))->format('d-m-Y');
                                } catch (\Exception $e) {
                                    $dateValue = request('date');
                                }
                            }
                        ?>
                        <div class="rounded-md border border-gray-200 p-3 sm:p-4 bg-white shadow-sm">
                            <div class="block lg:hidden space-y-2">
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Pencarian</label>
                                    <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                                        placeholder="Cari no reg, supplier, part..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Tanggal</label>
                                        <input type="text" id="date-picker-lpk-mobile" name="date" value="<?php echo e($dateValue); ?>"
                                            placeholder="dd-mm-yyyy" readonly
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Tahun</label>
                                        <select name="year"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            <?php if(!empty($years) && count($years)): ?>
                                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                                    <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?>

                                                    </option>
                                                <?php endfor; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Status LPK</label>
                                        <select name="status_lpk"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            <option value="claim" <?php echo e(request('status_lpk') == 'claim' ? 'selected' : ''); ?>>
                                                Claim</option>
                                            <option value="complaint" <?php echo e(request('status_lpk') == 'complaint' ? 'selected' : ''); ?>>Complaint</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                        <select name="approval_status"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            <option value="menunggu_request" <?php echo e(request('approval_status') == 'menunggu_request' ? 'selected' : ''); ?>>Menunggu
                                                Request</option>
                                            <option value="menunggu_sect" <?php echo e(request('approval_status') == 'menunggu_sect' ? 'selected' : ''); ?>>Menunggu Sect</option>
                                            <option value="menunggu_dept" <?php echo e(request('approval_status') == 'menunggu_dept' ? 'selected' : ''); ?>>Menunggu Dept</option>
                                            <option value="menunggu_ppc" <?php echo e(request('approval_status') == 'menunggu_ppc' ? 'selected' : ''); ?>>Menunggu PPC</option>
                                            <option value="ditolak_sect" <?php echo e(request('approval_status') == 'ditolak_sect' ? 'selected' : ''); ?>>Ditolak Sect</option>
                                            <option value="ditolak_dept" <?php echo e(request('approval_status') == 'ditolak_dept' ? 'selected' : ''); ?>>Ditolak Dept</option>
                                            <option value="ditolak_ppc" <?php echo e(request('approval_status') == 'ditolak_ppc' ? 'selected' : ''); ?>>Ditolak PPC</option>
                                            <option value="selesai" <?php echo e(request('approval_status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-2 pt-1">
                                    <button type="submit"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">Terapkan</button>
                                    <a href="<?php echo e(route('qc.lpk.index')); ?>"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">Reset</a>
                                    <a href="<?php echo e(route('qc.lpk.create')); ?>"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                                        <span class="text-lg leading-none">+</span>
                                    </a>
                                </div>
                            </div>

                            <div class="hidden lg:flex gap-2 items-end">
                                <div class="flex-1 min-w-0">
                                    <label class="text-xs text-gray-600 font-medium">Pencarian</label>
                                    <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                                        placeholder="Cari no reg, supplier, part, PO, deskripsi..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Tanggal</label>
                                    <input type="text" id="date-picker-lpk" name="date" value="<?php echo e($dateValue); ?>"
                                        placeholder="dd-mm-yyyy" readonly
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="w-28">
                                    <label class="text-xs text-gray-600 font-medium">Tahun</label>
                                    <select name="year"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1836 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        <?php if(!empty($years) && count($years)): ?>
                                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?>

                                                </option>
                                            <?php endfor; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Status LPK</label>
                                    <select name="status_lpk"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        <option value="claim" <?php echo e(request('status_lpk') == 'claim' ? 'selected' : ''); ?>>Claim
                                        </option>
                                        <option value="complaint" <?php echo e(request('status_lpk') == 'complaint' ? 'selected' : ''); ?>>Complaint</option>
                                    </select>
                                </div>

                                <div class="w-40">
                                    <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                    <select name="approval_status"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        <option value="menunggu_request" <?php echo e(request('approval_status') == 'menunggu_request' ? 'selected' : ''); ?>>Menunggu Request</option>
                                        <option value="menunggu_sect" <?php echo e(request('approval_status') == 'menunggu_sect' ? 'selected' : ''); ?>>Menunggu Sect</option>
                                        <option value="menunggu_dept" <?php echo e(request('approval_status') == 'menunggu_dept' ? 'selected' : ''); ?>>Menunggu Dept</option>
                                        <option value="menunggu_ppc" <?php echo e(request('approval_status') == 'menunggu_ppc' ? 'selected' : ''); ?>>Menunggu PPC</option>
                                        <option value="ditolak_sect" <?php echo e(request('approval_status') == 'ditolak_sect' ? 'selected' : ''); ?>>Ditolak Sect</option>
                                        <option value="ditolak_dept" <?php echo e(request('approval_status') == 'ditolak_dept' ? 'selected' : ''); ?>>Ditolak Dept</option>
                                        <option value="ditolak_ppc" <?php echo e(request('approval_status') == 'ditolak_ppc' ? 'selected' : ''); ?>>Ditolak PPC</option>
                                        <option value="selesai" <?php echo e(request('approval_status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                                    </select>
                                </div>

                                <div class="flex gap-2 items-center flex-shrink-0">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md whitespace-nowrap transition-colors">Terapkan</button>
                                    <a href="<?php echo e(route('qc.lpk.index')); ?>"
                                        class="inline-flex items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md whitespace-nowrap transition-colors">Reset</a>
                                    <a href="<?php echo e(route('qc.lpk.create')); ?>"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md whitespace-nowrap transition-colors">
                                        <img src="<?php echo e(asset('icon/add.ico')); ?>" alt="add" class="w-4 h-4 mr-1.5"
                                            style="filter: brightness(0) invert(1);" />
                                        <span>Create</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="responsive-table overflow-x-auto rounded-md ring-1 ring-gray-50">
                        <?php if($lpks->count() > 0): ?>
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-red-600 text-white">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-44">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tl-lg">No
                                                Reg</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-28">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Tanggal
                                                Terbit</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-36">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Supplier</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Nama</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-20">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">No
                                                Part</span>
                                        </th>
                                        <th class="px-3 py-2 text-left">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Deskripsi</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Status
                                                LPK</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-48">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Status
                                                Approval</span>
                                        </th>
                                        <th class="px-3 py-2 text-center hidden sm:table-cell w-28">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tr-lg">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php $__currentLoopData = $lpks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lpk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition-colors"
                                            data-lpk-id="<?php echo e($lpk->id); ?>">
                                            <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($lpk->no_reg); ?></td>
                                            <td class="px-3 py-3 text-sm text-gray-900">
                                                <?php echo e($lpk->tgl_terbit ? (is_string($lpk->tgl_terbit) ? (strtotime($lpk->tgl_terbit) ? date('d-m-Y', strtotime($lpk->tgl_terbit)) : '') : $lpk->tgl_terbit->format('Y-m-d')) : ''); ?>

                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($lpk->nama_supply); ?></td>
                                            <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($lpk->nama_part); ?></td>
                                            <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($lpk->nomor_po); ?></td>
                                            <td class="px-3 py-3 text-sm text-gray-900 align-middle">
                                                <?php if(!empty($lpk->problem)): ?>
                                                    <?php $short = \Illuminate\Support\Str::limit($lpk->problem, 120); ?>
                                                    <div class="truncate" style="max-width:40ch;" title="<?php echo e($lpk->problem); ?>">
                                                        <?php echo e($short); ?></div>
                                                <?php else: ?>
                                                    &mdash;
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-900 text-center">
                                                <?php
                                                    $statusLpk = strtolower(trim($lpk->status ?? ''));
                                                    $statusText = '';
                                                    $badgeClass = 'bg-gray-100 text-gray-800';

                                                    if ($statusLpk === 'claim') {
                                                        $statusText = 'Claim';
                                                        $badgeClass = 'bg-red-100 text-red-800';
                                                    } else {
                                                        $statusText = 'Complaint (Informasi)';
                                                        $badgeClass = 'bg-blue-100 text-blue-800';
                                                    }
                                                ?>
                                                <?php if($statusText): ?>
                                                    <span
                                                        class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full <?php echo e($badgeClass); ?>">
                                                        <?php echo e($statusText); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-900 status-approval-cell">
                                                <?php
                                                    $sect = strtolower($lpk->secthead_status ?? 'pending');
                                                    $dept = strtolower($lpk->depthead_status ?? 'pending');
                                                    $ppc = strtolower($lpk->ppchead_status ?? 'pending');

                                                    if (is_null($lpk->requested_at_qc)) {
                                                        $statusMsg = 'Menunggu request dikirimkan';
                                                    } elseif ($sect === 'rejected') {
                                                        $statusMsg = 'Ditolak Sect Head';
                                                    } elseif ($dept === 'rejected') {
                                                        $statusMsg = 'Ditolak Dept Head';
                                                    } elseif ($ppc === 'rejected') {
                                                        $statusMsg = 'Ditolak PPC Head';
                                                    } elseif (in_array('canceled', [$sect, $dept, $ppc])) {
                                                        $statusMsg = 'Dibatalkan';
                                                    } elseif ($sect === 'pending') {
                                                        $statusMsg = 'Menunggu approval Sect Head';
                                                    } elseif ($sect === 'approved' && $dept === 'pending') {
                                                        $statusMsg = 'Menunggu approval Dept Head';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $ppc === 'pending') {
                                                        $statusMsg = 'Menunggu approval PPC Head';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $ppc === 'approved') {
                                                        $statusMsg = 'Selesai';
                                                    } else {
                                                        $statusMsg = '-';
                                                    }
                                                ?>
                                                <div class="font-medium"><?php echo e($statusMsg); ?></div>
                                            </td>
                                            <td class="px-3 py-3 text-center text-sm hidden sm:table-cell">
                                                <?php
                                                    $sect = strtolower($lpk->secthead_status ?? 'pending');
                                                    $dept = strtolower($lpk->depthead_status ?? 'pending');
                                                    $ppc = strtolower($lpk->ppchead_status ?? 'pending');
                                                    $isSelesai = ($sect === 'approved' && $dept === 'approved' && $ppc === 'approved');
                                                    $hasRejected = in_array('rejected', [$sect, $dept, $ppc]);
                                                    $isCanceled = in_array('canceled', [$sect, $dept, $ppc]);
                                                    $locked = $isSelesai || $hasRejected;
                                                ?>
                                                <div class="flex flex-col items-center justify-center gap-1">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <?php if($isCanceled): ?>
                                                            <div class="flex flex-col items-center">
                                                                <button type="button"
                                                                    class="open-delete-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                                                                    data-url="<?php echo e(route('qc.lpk.destroy', $lpk->id)); ?>"
                                                                    aria-label="Hapus LPK <?php echo e($lpk->no_reg); ?>" title="Hapus">
                                                                    <img src="<?php echo e(asset('icon/trash.ico')); ?>" alt="Delete"
                                                                        class="w-4 h-4" />
                                                                </button>
                                                                <span class="text-xs text-gray-500 mt-1">Hapus</span>
                                                            </div>
                                                        <?php else: ?>
                                                            <?php if (! ($locked)): ?>
                                                                <?php if(is_null($lpk->requested_at_qc)): ?>
                                                                    <div class="flex flex-col items-center request-btn-container">
                                                                        <button type="button" data-url="<?php echo e(route('qc.lpk.request', $lpk->id)); ?>"
                                                                            data-lpk-id="<?php echo e($lpk->id); ?>" data-noreg="<?php echo e($lpk->no_reg); ?>"
                                                                            data-tgl-terbit="<?php echo e($lpk->tgl_terbit ? \Carbon\Carbon::parse($lpk->tgl_terbit)->format('d/m/Y') : '-'); ?>"
                                                                            data-supplier="<?php echo e($lpk->nama_supply ?? '-'); ?>"
                                                                            data-nama-part="<?php echo e($lpk->nama_part ?? '-'); ?>"
                                                                            data-no-part="<?php echo e($lpk->nomor_part ?? '-'); ?>"
                                                                            data-deskripsi="<?php echo e($lpk->problem ?? '-'); ?>"
                                                                            data-status="<?php echo e($lpk->status ?? '-'); ?>"
                                                                            class="open-request-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-yellow-50 transition"
                                                                            title="Request Approval for <?php echo e($lpk->no_reg); ?>">
                                                                            <img src="<?php echo e(asset('icon/send.ico')); ?>" alt="Request"
                                                                                class="w-4 h-4" />
                                                                        </button>
                                                                        <span class="text-xs text-gray-500 mt-1">Request</span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <?php if (! ($locked)): ?>
                                                                <?php
                                                                    $showEdit = is_null($lpk->requested_at_qc) ||
                                                                                ($sect === 'pending') ||
                                                                                ($sect === 'approved' && $dept === 'pending') ||
                                                                                ($sect === 'approved' && $dept === 'approved' && $ppc === 'pending');
                                                                ?>
                                                                <?php if($showEdit): ?>
                                                                    <div class="flex flex-col items-center edit-btn-container">
                                                                        <a href="<?php echo e(route('qc.lpk.edit', $lpk->id)); ?>"
                                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition"
                                                                            title="Edit LPK <?php echo e($lpk->no_reg); ?>">
                                                                            <img src="<?php echo e(asset('icon/edit.ico')); ?>" alt="Edit"
                                                                                class="w-4 h-4" />
                                                                        </a>
                                                                        <span class="text-xs text-gray-500 mt-1">Edit</span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <?php
                                                                $canDelete = is_null($lpk->requested_at_qc) ||
                                                                    (!is_null($lpk->requested_at_qc) && $lpk->secthead_status === 'pending');
                                                            ?>
                                                            <?php if($canDelete): ?>
                                                                <div class="flex flex-col items-center">
                                                                    <button type="button"
                                                                        class="open-delete-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                                                                        data-url="<?php echo e(route('qc.lpk.destroy', $lpk->id)); ?>"
                                                                        aria-label="Hapus LPK <?php echo e($lpk->no_reg); ?>" title="Hapus">
                                                                        <img src="<?php echo e(asset('icon/trash.ico')); ?>" alt="Delete"
                                                                            class="w-4 h-4" />
                                                                    </button>
                                                                    <span class="text-xs text-gray-500 mt-1">Hapus</span>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if(!is_null($lpk->requested_at_qc)): ?>
                                                                <div class="flex flex-col items-center pdf-btn-container">
                                                                    <a href="<?php echo e(route('qc.lpk.previewPdf', $lpk->id)); ?>" target="_blank"
                                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition"
                                                                        title="Preview PDF">
                                                                        <img src="<?php echo e(asset('icon/pdf.ico')); ?>" alt="PDF" class="w-4 h-4" />
                                                                    </a>
                                                                    <span class="text-xs text-gray-500 mt-1">PDF</span>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="flex flex-col items-center pdf-btn-container"
                                                                    style="display:none;">
                                                                    <a href="<?php echo e(route('qc.lpk.previewPdf', $lpk->id)); ?>" target="_blank"
                                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition"
                                                                        title="Preview PDF">
                                                                        <img src="<?php echo e(asset('icon/pdf.ico')); ?>" alt="PDF" class="w-4 h-4" />
                                                                    </a>
                                                                    <span class="text-xs text-gray-500 mt-1">PDF</span>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-center py-12 text-gray-500">
                                <div class="text-lg font-medium">Tidak ada data LPK</div>
                                <div class="text-sm">Belum ada LPK yang sesuai dengan filter yang dipilih. <a
                                        href="<?php echo e(route('qc.lpk.create')); ?>" class="text-blue-600">Buat LPK baru</a></div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6 border-t border-gray-100 pt-4">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-medium"><?php echo e($lpks->firstItem() ?? 0); ?></span> - <span
                                    class="font-medium"><?php echo e($lpks->lastItem() ?? 0); ?></span> dari <span
                                    class="font-medium"><?php echo e($lpks->total()); ?></span> data
                            </div>

                            <nav class="flex items-center gap-3" aria-label="Pagination">
                                <?php $prev = $lpks->previousPageUrl();
                                $next = $lpks->nextPageUrl(); ?>

                                <a href="<?php echo e($prev ?: '#'); ?>"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($lpks->onFirstPage() ? 'text-gray-400 border-gray-200 pointer-events-none bg-white' : 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm'); ?>"
                                    aria-disabled="<?php echo e($lpks->onFirstPage() ? 'true' : 'false'); ?>">
                                    <span class="text-sm">
                                        < Sebelumnya</span>
                                </a>

                                <div
                                    class="hidden sm:inline-flex items-center px-3 py-2 bg-white border border-gray-100 rounded-full shadow-sm text-sm text-gray-700">
                                    Halaman <span class="mx-2 font-semibold"><?php echo e($lpks->currentPage()); ?></span> dari <span
                                        class="mx-2 font-medium"><?php echo e($lpks->lastPage()); ?></span>
                                </div>

                                <a href="<?php echo e($next ?: '#'); ?>"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($lpks->hasMorePages() ? 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' : 'text-gray-400 border-gray-200 pointer-events-none bg-white'); ?>"
                                    aria-disabled="<?php echo e($lpks->hasMorePages() ? 'false' : 'true'); ?>">
                                    <span class="text-sm">Berikutnya ></span>
                                </a>

                                <div class="sm:hidden px-3 py-1 text-xs text-gray-600">Hal. <span
                                        class="font-medium"><?php echo e($lpks->currentPage()); ?></span>/<span
                                        class="font-medium"><?php echo e($lpks->lastPage()); ?></span></div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin menghapus data LPK ini? Aksi ini tidak dapat
                    dibatalkan.</p>
                <div class="flex justify-end gap-3">
                    <button id="delete-cancel" type="button"
                        class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                    <form id="delete-form" method="POST" action="">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit"
                            class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
                    </form>
                </div>
            </div>
        </div>

        <div id="request-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-3">Konfirmasi Request Persetujuan</h3>

                <p class="text-sm text-gray-600 mb-4">Anda akan mengirim request approval untuk LPK berikut:</p>

                <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-3">
                    <div class="grid grid-cols-3 gap-2 text-sm">
                        <div class="font-medium text-gray-700">No. Reg:</div>
                        <div class="col-span-2 text-gray-900" id="modal-noreg">-</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                        <div class="font-medium text-gray-700">Tgl Terbit:</div>
                        <div class="col-span-2 text-gray-900" id="modal-tgl-terbit">-</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                        <div class="font-medium text-gray-700">Supplier:</div>
                        <div class="col-span-2 text-gray-900" id="modal-supplier">-</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                        <div class="font-medium text-gray-700">Nama Part:</div>
                        <div class="col-span-2 text-gray-900" id="modal-nama-part">-</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                        <div class="font-medium text-gray-700">No. Part:</div>
                        <div class="col-span-2 text-gray-900" id="modal-no-part">-</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                        <div class="font-medium text-gray-700">Deskripsi:</div>
                        <div class="col-span-2 text-gray-900 break-words whitespace-pre-wrap" id="modal-deskripsi">-</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                        <div class="font-medium text-gray-700">Status LPK:</div>
                        <div class="col-span-2">
                            <span id="modal-status-badge"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium">-</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Pilih Sect Head yang ingin menerima email request (centang salah satu atau beberapa):</p>
                    <div class="mb-2 flex items-center justify-between">
                        <div class="text-xs text-gray-500">Pilih penerima:</div>
                        <div class="text-xs text-gray-500"><label class="inline-flex items-center gap-2"><input type="checkbox" id="recipients-select-all"> Pilih semua</label></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded p-2 bg-white">
                        <?php $__empty_1 = true; $__currentLoopData = $sectApprovers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="recipients[]" value="<?php echo e($sa->npk); ?>" class="recipient-checkbox">
                                <span class="truncate"><?php echo e($sa->name); ?> <?php if($sa->email): ?> &lt;<?php echo e($sa->email); ?>&gt; <?php endif; ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-2 text-sm text-gray-500 italic">Tidak ada approver Sect Head QA yang tersedia.</div>
                        <?php endif; ?>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Tidak memilih siapa pun akan mengirim ke semua approver (sect/dept/ppc) seperti sebelumnya.</div>
                </div>

                <div class="flex justify-end gap-3 border-t pt-4">
                    <button id="request-cancel" type="button"
                        class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition">Batal</button>
                    <form id="request-form" method="POST" action="">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="px-5 py-2 rounded bg-yellow-600 text-white hover:bg-yellow-700 font-medium transition">Kirim
                            Request</button>
                    </form>
                </div>
            </div>
        </div>

        <?php $__env->startPush('scripts'); ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Toast notification helper
                    function showToast(message, type = 'success') {
                        // Remove existing toast
                        const existingToast = document.getElementById('ajax-toast');
                        if (existingToast) existingToast.remove();

                        const toast = document.createElement('div');
                        toast.id = 'ajax-toast';
                        toast.className = 'fixed top-4 right-4 z-[100] px-6 py-3 rounded-lg shadow-lg text-white text-sm font-medium transition-all duration-300 transform translate-x-0';
                        toast.style.cssText = 'animation: slideIn 0.3s ease-out;';

                        if (type === 'success') {
                            toast.classList.add('bg-green-600');
                        } else if (type === 'error') {
                            toast.classList.add('bg-red-600');
                        } else {
                            toast.classList.add('bg-gray-700');
                        }

                        toast.textContent = message;
                        document.body.appendChild(toast);

                        // Auto dismiss after 4 seconds
                        setTimeout(() => {
                            toast.style.opacity = '0';
                            toast.style.transform = 'translateX(100%)';
                            setTimeout(() => toast.remove(), 300);
                        }, 4000);
                    }

                    // Helper to update row status after AJAX action
                    function updateRowStatus(lpkId, newStatus) {
                        const row = document.querySelector(`tr[data-lpk-id="${lpkId}"]`);
                        if (!row) return;

                        // Update status approval cell
                        const statusCell = row.querySelector('.status-approval-cell');
                        if (statusCell) {
                            statusCell.innerHTML = '<div class="font-medium">Menunggu approval Sect Head</div>';
                        }

                        // Hide the request button and show PDF button
                        const requestBtn = row.querySelector('.open-request-modal');
                        if (requestBtn) {
                            requestBtn.closest('.flex-col').style.display = 'none';
                        }

                        // Show PDF button if not already visible
                        const actionsContainer = row.querySelector('.actions-container');
                        if (actionsContainer && !actionsContainer.querySelector('.pdf-link')) {
                            // PDF link will appear on page reload, but for now we just hide request button
                        }
                    }

                    (function attachCalendar() {
                        function init(fp) {
                            var locale = {
                                firstDayOfWeek: 1,
                                weekdays: {
                                    shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                                    longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                                },
                                months: {
                                    shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                                    longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                                },
                            };

                            ['#date-picker-lpk', '#date-picker-lpk-mobile'].forEach(function (selector) {
                                var el = document.querySelector(selector);
                                if (!el) return;
                                try {
                                    fp(el, {
                                        dateFormat: 'd-m-Y',
                                        allowInput: true,
                                        defaultDate: el.value ? el.value : undefined,
                                        locale: locale,
                                        onOpen: function(selectedDates, dateStr, instance) { if (!instance.input.value) instance.jumpToDate(new Date()); }
                                    });
                                } catch (err) {
                                    console && console.error('flatpickr init error', err);
                                }
                            });
                        }

                        if (window.flatpickr) {
                            init(window.flatpickr);
                            return;
                        }

                        var link = document.createElement('link');
                        link.rel = 'stylesheet';
                        link.href = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.css")); ?>';
                        document.head.appendChild(link);

                        var s = document.createElement('script');
                        s.src = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.js")); ?>';
                        s.onload = function () {
                            if (window.flatpickr) {
                                init(window.flatpickr);
                            } else {
                                console && console.error('flatpickr failed to initialize from local asset.');
                            }
                        };
                        document.body.appendChild(s);
                    })();

                    const modal = document.getElementById('delete-modal');
                    const deleteForm = document.getElementById('delete-form');
                    const cancelBtn = document.getElementById('delete-cancel');

                    document.querySelectorAll('.open-delete-modal').forEach(btn => {
                        btn.addEventListener('click', function () {
                            const url = this.getAttribute('data-url');
                            deleteForm.setAttribute('action', url);
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        });
                    });

                    cancelBtn.addEventListener('click', function () {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });

                    modal.addEventListener('click', function (e) {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }
                    });

                    const requestModal = document.getElementById('request-modal');
                    const requestForm = document.getElementById('request-form');
                    const requestCancel = document.getElementById('request-cancel');
                    let currentRequestLpkId = null;

                    document.querySelectorAll('.open-request-modal').forEach(btn => {
                        btn.addEventListener('click', function () {
                            const url = this.getAttribute('data-url');
                            const lpkId = this.getAttribute('data-lpk-id');
                            const noreg = this.getAttribute('data-noreg');
                            const tglTerbit = this.getAttribute('data-tgl-terbit');
                            const supplier = this.getAttribute('data-supplier');
                            const namaPart = this.getAttribute('data-nama-part');
                            const noPart = this.getAttribute('data-no-part');
                            const deskripsi = this.getAttribute('data-deskripsi');
                            const status = this.getAttribute('data-status');

                            requestForm.setAttribute('action', url);
                            currentRequestLpkId = lpkId;

                            document.getElementById('modal-noreg').textContent = noreg || '-';
                            document.getElementById('modal-tgl-terbit').textContent = tglTerbit || '-';
                            document.getElementById('modal-supplier').textContent = supplier || '-';
                            document.getElementById('modal-nama-part').textContent = namaPart || '-';
                            document.getElementById('modal-no-part').textContent = noPart || '-';
                            document.getElementById('modal-deskripsi').textContent = deskripsi || '-';

                            const statusBadge = document.getElementById('modal-status-badge');
                            statusBadge.textContent = status || '-';

                            if (status === 'Claim') {
                                statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
                            } else if (status && status !== '-') {
                                statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                            } else {
                                statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                            }

                            requestModal.classList.remove('hidden');
                            requestModal.classList.add('flex');
                        });
                    });

                    // Handle request form submission via AJAX
                    requestForm.addEventListener('submit', function (e) {
                        e.preventDefault();

                        const url = this.getAttribute('action');
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.textContent;

                        // Collect selected recipients (user ids)
                        const checkedRecipients = Array.from(requestModal.querySelectorAll('input[name="recipients[]"]:checked')).map(i => i.value);

                        // Disable button and show loading
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Mengirim...';

                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ recipients: checkedRecipients })
                        })
                            .then(response => response.json().then(data => ({ status: response.status, body: data })))
                            .then(({ status, body }) => {
                                // Close modal
                                requestModal.classList.add('hidden');
                                requestModal.classList.remove('flex');

                                if (body.success) {
                                    showToast(body.message, 'success');

                                    // Update the row in the table without reload
                                    if (currentRequestLpkId) {
                                        const row = document.querySelector(`tr[data-lpk-id="${currentRequestLpkId}"]`);
                                        if (row) {
                                            // Update status text
                                            const statusCell = row.querySelector('.status-approval-cell');
                                            if (statusCell) {
                                                statusCell.innerHTML = '<div class="font-medium">Menunggu approval Sect Head</div>';
                                            }

                                            // Hide request button container
                                            const requestBtnContainer = row.querySelector('.request-btn-container');
                                            if (requestBtnContainer) {
                                                requestBtnContainer.style.display = 'none';
                                            }

                                            // Note: Edit button remains visible for "Menunggu approval Sect Head" status
                                            // Users can still edit LPK while it's pending Sect Head approval

                                            // Show PDF button
                                            const pdfBtnContainer = row.querySelector('.pdf-btn-container');
                                            if (pdfBtnContainer) {
                                                pdfBtnContainer.style.display = 'flex';
                                            }
                                        }
                                    }
                                } else {
                                    showToast(body.message || 'Terjadi kesalahan', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Request failed:', error);
                                showToast('Terjadi kesalahan saat mengirim permintaan.', 'error');

                                requestModal.classList.add('hidden');
                                requestModal.classList.remove('flex');
                            })
                            .finally(() => {
                                submitBtn.disabled = false;
                                submitBtn.textContent = originalText;
                            });
                    });

                    requestCancel.addEventListener('click', function () {
                        requestModal.classList.add('hidden');
                        requestModal.classList.remove('flex');
                    });

                    // Quick select all behavior for recipients
                    const selectAllRecipients = document.getElementById('recipients-select-all');
                    if (selectAllRecipients) {
                        selectAllRecipients.addEventListener('change', function () {
                            const checked = this.checked;
                            requestModal.querySelectorAll('input[name="recipients[]"]').forEach(cb => cb.checked = checked);
                        });
                    }

                    requestModal.addEventListener('click', function (e) {
                        if (e.target === requestModal) {
                            requestModal.classList.add('hidden');
                            requestModal.classList.remove('flex');
                        }
                    });
                });
            </script>
            <style>
                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateX(100%);
                    }

                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
            </style>
        <?php $__env->stopPush(); ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/qc/lpk/index.blade.php ENDPATH**/ ?>