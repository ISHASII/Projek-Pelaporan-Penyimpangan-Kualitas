<?php $__env->startSection('content'); ?>
<div class="w-full m-0 p-0 -mt-0">
    <!-- Flash/session notification removed for clean UI -->

    <div class="m-0">
        <div class="bg-white rounded-none shadow-none overflow-hidden border-t-0 border-l-0 border-r-0 border-gray-100 w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div></div>
                </div>
                
                <form id="filter-form" method="GET" action="<?php echo e(route('depthead.lpk.index')); ?>" class="mb-4">
                    <?php
                        // Prepare a safely formatted date value for the date inputs.
                        $dateValue = '';
                        if (request('date')) {
                            try {
                                $dateValue = \Carbon\Carbon::parse(request('date'))->format('d-m-Y');
                            } catch (\Exception $e) {
                                // If parsing fails, fall back to the raw request value so user input is preserved
                                $dateValue = request('date');
                            }
                        }
                    ?>
                    <div class="rounded-md border border-gray-200 p-3 sm:p-4 bg-white shadow-sm">
                        
                        <input type="hidden" name="date" id="date-hidden" value="<?php echo e(request('date')); ?>" />
                        
                        <div class="block lg:hidden space-y-2">
                            <div>
                                <label class="text-xs text-gray-600 font-medium">Pencarian</label>
                                <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari no reg, supplier, part..." class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Tanggal</label>
                                    <input type="text" id="date-picker-lpk-mobile" name="date_display" value="<?php echo e($dateValue); ?>" placeholder="dd-mm-yyyy" readonly class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Tahun</label>
                                    <select name="year" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        <?php if(!empty($years) && count($years)): ?>
                                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <?php for($y=date('Y'); $y>=date('Y')-5; $y--): ?>
                                                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                            <?php endfor; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Status LPK</label>
                                    <select name="status_lpk" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        <option value="claim" <?php echo e(request('status_lpk') == 'claim' ? 'selected' : ''); ?>>Claim</option>
                                        <option value="complaint" <?php echo e(request('status_lpk') == 'complaint' ? 'selected' : ''); ?>>Complaint</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                    <select name="approval_status" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
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

                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <button type="submit" class="inline-flex justify-center items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">Terapkan</button>
                                <a href="<?php echo e(route('depthead.lpk.index')); ?>" class="inline-flex justify-center items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">Reset</a>
                            </div>
                        </div>

                        
                        <div class="hidden lg:flex gap-2 items-end">
                            <div class="flex-1 min-w-0">
                                <label class="text-xs text-gray-600 font-medium">Pencarian</label>
                                <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari no reg, supplier, part, PO, deskripsi..." class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                            </div>

                            <div class= "w-36">
                                <label class="text-xs text-gray-600 font-medium">Tanggal</label>
                                <input type="text" id="date-picker-lpk" name="date_display" value="<?php echo e($dateValue); ?>" placeholder="dd-mm-yyyy" readonly class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                            </div>

                            <div class="w-28">
                                <label class="text-xs text-gray-600 font-medium">Tahun</label>
                                <select name="year" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="">Semua</option>
                                    <?php if(!empty($years) && count($years)): ?>
                                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <?php for($y=date('Y'); $y>=date('Y')-5; $y--): ?>
                                            <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                        <?php endfor; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="w-36">
                                <label class="text-xs text-gray-600 font-medium">Status LPK</label>
                                <select name="status_lpk" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="">Semua</option>
                                    <option value="claim" <?php echo e(request('status_lpk') == 'claim' ? 'selected' : ''); ?>>Claim</option>
                                    <option value="complaint" <?php echo e(request('status_lpk') == 'complaint' ? 'selected' : ''); ?>>Complaint</option>
                                </select>
                            </div>

                            <div class="w-40">
                                <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                <select name="approval_status" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="">Semua</option>
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
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md whitespace-nowrap transition-colors">Terapkan</button>
                                <a href="<?php echo e(route('depthead.lpk.index')); ?>" class="inline-flex items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md whitespace-nowrap transition-colors">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="responsive-table overflow-x-auto rounded-md ring-1 ring-gray-50">
                    <?php if(isset($lpks) && count($lpks)): ?>
                        <table class="min-w-full divide-y divide-gray-200 table-fixed">
                            <thead class="bg-red-600 text-white">
                                <tr>
                                    <th class="px-3 py-2 text-left w-44">
                                        <span class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tl-lg">No Reg</span>
                                    </th>
                                    <th class="px-3 py-2 text-left w-28">
                                        <span class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Tanggal Terbit</span>
                                    </th>
                                    <th class="px-3 py-2 text-left w-36">
                                        <span class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Supplier</span>
                                    </th>
                                    <th class="px-3 py-2 text-left w-40">
                                        <span class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Nama</span>
                                    </th>
                                    <th class="px-3 py-2 text-left w-20">
                                        <span class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">No Part</span>
                                    </th>
                                    <th class="px-3 py-2 text-left">
                                        <span class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Deskripsi</span>
                                    </th>
                                    <th class="px-3 py-2 text-left w-32">
                                        <span class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Status LPK</span>
                                    </th>
                                    <th class="px-3 py-2 text-left w-48">
                                        <span class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Status Approval</span>
                                    </th>
                                    <th class="px-3 py-2 text-center hidden sm:table-cell w-28">
                                        <span class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tr-lg">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php $__currentLoopData = $lpks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $lpk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition-colors" data-lpk-id="<?php echo e($lpk->id); ?>">
                                        <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($lpk->no_reg); ?></td>
                                        <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($lpk->tgl_terbit ? (is_string($lpk->tgl_terbit) ? (strtotime($lpk->tgl_terbit) ? date('d-m-Y', strtotime($lpk->tgl_terbit)) : '') : $lpk->tgl_terbit->format('d-m-Y')) : ''); ?></td>
                                        <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($lpk->nama_supply); ?></td>
                                        <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($lpk->nama_part); ?></td>
                                        <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($lpk->nomor_po); ?></td>
                                        <td class="px-3 py-3 text-sm text-gray-900 align-middle">
                                            <?php if(!empty($lpk->problem)): ?>
                                                <?php $short = \Illuminate\Support\Str::limit($lpk->problem, 120); ?>
                                                <div class="truncate" style="max-width:40ch;" title="<?php echo e($lpk->problem); ?>"><?php echo e($short); ?></div>
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
                                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full <?php echo e($badgeClass); ?>">
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
                                                if ($sect === 'rejected') {
                                                    $statusMsg = 'Ditolak Sect Head';
                                                } elseif ($dept === 'rejected') {
                                                    $statusMsg = 'Ditolak Dept Head';
                                                } elseif ($ppc === 'rejected') {
                                                    $statusMsg = 'Ditolak PPC Head';
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
                                            <div class="font-medium status-text"><?php echo e($statusMsg); ?></div>
                                        </td>
                                        <td class="px-3 py-3 text-center text-sm hidden sm:table-cell">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <div class="flex items-center justify-center gap-4 action-buttons-container">
                                                    <?php
                                                        $dept = strtolower($lpk->depthead_status ?? 'pending');
                                                    ?>
                                                    <?php if(!is_null($lpk->requested_at_qc) && $dept === 'pending'): ?>
                                                        <?php $sect = strtolower($lpk->secthead_status ?? 'pending'); ?>
                                                        <?php if($sect === 'approved'): ?>
                                                            <div class="flex flex-col items-center gap-1">
                                                                <button type="button"
                                                                    class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                                                                    title="Setuju"
                                                                    data-url="<?php echo e(route('depthead.lpk.approve', $lpk->id)); ?>"
                                                                    data-noreg="<?php echo e($lpk->no_reg); ?>"
                                                                    data-lpk-id="<?php echo e($lpk->id); ?>">
                                                                    <img src="<?php echo e(asset('icon/approve.ico')); ?>" alt="Approve" class="w-4 h-4" />
                                                                </button>
                                                                <span class="text-xs text-gray-500 mt-1">Approve</span>
                                                            </div>
                                                            <div class="flex flex-col items-center gap-1">
                                                                <button type="button"
                                                                    class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                                                                    title="Tolak"
                                                                    data-url="<?php echo e(route('depthead.lpk.reject', $lpk->id)); ?>"
                                                                    data-noreg="<?php echo e($lpk->no_reg); ?>"
                                                                    data-lpk-id="<?php echo e($lpk->id); ?>">
                                                                    <img src="<?php echo e(asset('icon/cancel.ico')); ?>" alt="Reject" class="w-4 h-4" />
                                                                </button>
                                                                <span class="text-xs text-gray-500 mt-1">Reject</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php if(!is_null($lpk->requested_at_qc)): ?>
                                                        <div class="flex flex-col items-center gap-1">
                                                            <a href="<?php echo e(route('depthead.lpk.previewPdf', $lpk->id)); ?>" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition" title="Preview PDF">
                                                                <img src="<?php echo e(asset('icon/pdf.ico')); ?>" alt="PDF" class="w-4 h-4" />
                                                            </a>
                                                            <span class="text-xs text-gray-500 mt-1">PDF</span>
                                                        </div>
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
                            <div class="text-sm">Belum ada LPK yang sesuai dengan filter yang dipilih.</div>
                        </div>
                    <?php endif; ?>
                </div>

                
                <?php if(method_exists($lpks, 'links')): ?>
                    <div class="mt-6 border-t border-gray-100 pt-4">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-medium"><?php echo e($lpks->firstItem() ?? 0); ?></span> - <span class="font-medium"><?php echo e($lpks->lastItem() ?? 0); ?></span> dari <span class="font-medium"><?php echo e($lpks->total()); ?></span> data
                            </div>

                            <nav class="flex items-center gap-3" aria-label="Pagination">
                                <?php $prev = $lpks->previousPageUrl(); $next = $lpks->nextPageUrl(); ?>

                                <a href="<?php echo e($prev ?: '#'); ?>" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($lpks->onFirstPage() ? 'text-gray-400 border-gray-200 pointer-events-none bg-white' : 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm'); ?>" aria-disabled="<?php echo e($lpks->onFirstPage() ? 'true' : 'false'); ?>">
                                    <span class="text-sm">< Sebelumnya</span>
                                </a>

                                <div class="hidden sm:inline-flex items-center px-3 py-2 bg-white border border-gray-100 rounded-full shadow-sm text-sm text-gray-700">
                                    Halaman <span class="mx-2 font-semibold"><?php echo e($lpks->currentPage()); ?></span> dari <span class="mx-2 font-medium"><?php echo e($lpks->lastPage()); ?></span>
                                </div>

                                <a href="<?php echo e($next ?: '#'); ?>" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($lpks->hasMorePages() ? 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' : 'text-gray-400 border-gray-200 pointer-events-none bg-white'); ?>" aria-disabled="<?php echo e($lpks->hasMorePages() ? 'false' : 'true'); ?>">
                                    <span class="text-sm">Berikutnya ></span>
                                </a>

                                <div class="sm:hidden px-3 py-1 text-xs text-gray-600">Hal. <span class="font-medium"><?php echo e($lpks->currentPage()); ?></span>/<span class="font-medium"><?php echo e($lpks->lastPage()); ?></span></div>
                            </nav>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Approve and Reject modals for Depthead -->
<div id="approve-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi Approve</h3>
        <p id="approve-modal-msg" class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin Approve LPK ini?</p>
        <div class="flex justify-end gap-3">
            <button id="approve-cancel" type="button" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
            <form id="approve-form" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('POST'); ?>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Approve</button>
            </form>
        </div>
    </div>
</div>

<div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi Reject</h3>
        <p id="reject-modal-msg" class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin Reject LPK ini?</p>
        <div class="flex justify-end gap-3">
            <button id="reject-cancel" type="button" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
            <form id="reject-form" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('POST'); ?>
                <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Reject</button>
            </form>
        </div>
    </div>
</div>


    <?php $__env->startPush('scripts'); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toast notification functions
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-[9999] px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <span>${type === 'success' ? '✓' : '✕'}</span>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);

            setTimeout(() => toast.classList.remove('translate-x-full'), 100);
            setTimeout(() => removeToast(toast), 3000);
        }

        function removeToast(toast) {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }

        // Function to update row status
        function updateRowStatus(lpkId, newStatusText) {
            const row = document.querySelector(`tr[data-lpk-id="${lpkId}"]`);
            if (row) {
                const statusCell = row.querySelector('.status-approval-cell .status-text');
                if (statusCell) {
                    statusCell.textContent = newStatusText;
                }
            }
        }

        // Function to hide action buttons after approval/reject
        function hideActionButtons(lpkId) {
            const row = document.querySelector(`tr[data-lpk-id="${lpkId}"]`);
            if (row) {
                const actionContainer = row.querySelector('.action-buttons-container');
                if (actionContainer) {
                    // Remove approve and reject buttons, keep PDF button
                    const approveBtn = actionContainer.querySelector('.open-approve-modal');
                    const rejectBtn = actionContainer.querySelector('.open-reject-modal');
                    if (approveBtn) approveBtn.closest('.flex.flex-col').remove();
                    if (rejectBtn) rejectBtn.closest('.flex.flex-col').remove();
                }
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

                ['#date-picker-lpk', '#date-picker-lpk-mobile'].forEach(function(selector) {
                    var el = document.querySelector(selector);
                    if (!el) return;
                    try {
                        fp(el, {
                            dateFormat: 'd-m-Y',
                            allowInput: true,
                            defaultDate: el.value ? el.value : undefined,
                            locale: locale
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
            s.onload = function() {
                if (window.flatpickr) {
                    init(window.flatpickr);
                } else {
                    console && console.error('flatpickr failed to initialize from local asset.');
                }
            };
            document.body.appendChild(s);
        })();

        (function() {
            const modal = document.getElementById('delete-modal');
            const deleteForm = document.getElementById('delete-form');
            const cancelBtn = document.getElementById('delete-cancel');

            if (!modal || !deleteForm) return;

            document.querySelectorAll('.open-delete-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    deleteForm.setAttribute('action', url);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            }

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        })();

        (function() {
            const requestModal = document.getElementById('request-modal');
            const requestForm = document.getElementById('request-form');
            const requestCancel = document.getElementById('request-cancel');

            if (!requestModal || !requestForm) return;

            document.querySelectorAll('.open-request-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    const noreg = this.getAttribute('data-noreg');
                    const tglTerbit = this.getAttribute('data-tgl-terbit');
                    const supplier = this.getAttribute('data-supplier');
                    const namaPart = this.getAttribute('data-nama-part');
                    const noPart = this.getAttribute('data-no-part');
                    const deskripsi = this.getAttribute('data-deskripsi');
                    const status = this.getAttribute('data-status');

                    requestForm.setAttribute('action', url);

                    const elNoreg = document.getElementById('modal-noreg');
                    const elTgl = document.getElementById('modal-tgl-terbit');
                    const elSupplier = document.getElementById('modal-supplier');
                    const elNama = document.getElementById('modal-nama-part');
                    const elNoPart = document.getElementById('modal-no-part');
                    const elDeskripsi = document.getElementById('modal-deskripsi');
                    const statusBadge = document.getElementById('modal-status-badge');

                    if (elNoreg) elNoreg.textContent = noreg || '-';
                    if (elTgl) elTgl.textContent = tglTerbit || '-';
                    if (elSupplier) elSupplier.textContent = supplier || '-';
                    if (elNama) elNama.textContent = namaPart || '-';
                    if (elNoPart) elNoPart.textContent = noPart || '-';
                    if (elDeskripsi) elDeskripsi.textContent = deskripsi || '-';

                    if (statusBadge) {
                        statusBadge.textContent = status || '-';
                        if (status === 'Claim') {
                            statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
                        } else if (status && status !== '-') {
                            statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                        } else {
                            statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                        }
                    }

                    requestModal.classList.remove('hidden');
                    requestModal.classList.add('flex');
                });
            });

            if (requestCancel) {
                requestCancel.addEventListener('click', function() {
                    requestModal.classList.add('hidden');
                    requestModal.classList.remove('flex');
                });
            }

            requestModal.addEventListener('click', function(e) {
                if (e.target === requestModal) {
                    requestModal.classList.add('hidden');
                    requestModal.classList.remove('flex');
                }
            });
        })();

        // Modal Approve with AJAX
        (function() {
            const approveModal = document.getElementById('approve-modal');
            const approveForm = document.getElementById('approve-form');
            const approveCancel = document.getElementById('approve-cancel');
            let currentLpkId = null;

            if (!approveModal || !approveForm) return;

            document.querySelectorAll('.open-approve-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    const noreg = this.getAttribute('data-noreg');
                    currentLpkId = this.getAttribute('data-lpk-id');

                    approveForm.setAttribute('action', url);

                    const approveMsg = document.getElementById('approve-modal-msg');
                    if (approveMsg) {
                        approveMsg.textContent = `Apakah Anda yakin ingin Approve LPK ${noreg}?`;
                    }

                    approveModal.classList.remove('hidden');
                    approveModal.classList.add('flex');
                });
            });

            // AJAX form submission for approve
            approveForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const url = this.getAttribute('action');
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Memproses...';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    approveModal.classList.add('hidden');
                    approveModal.classList.remove('flex');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    if (data.success) {
                        showToast(data.message || 'LPK berhasil diapprove!', 'success');
                        if (currentLpkId) {
                            updateRowStatus(currentLpkId, data.newStatusText || 'Menunggu approval PPC Head');
                            hideActionButtons(currentLpkId);
                        }
                    } else {
                        showToast(data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    approveModal.classList.add('hidden');
                    approveModal.classList.remove('flex');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    showToast('Terjadi kesalahan saat memproses', 'error');
                });
            });

            if (approveCancel) {
                approveCancel.addEventListener('click', function() {
                    approveModal.classList.add('hidden');
                    approveModal.classList.remove('flex');
                });
            }

            approveModal.addEventListener('click', function(e) {
                if (e.target === approveModal) {
                    approveModal.classList.add('hidden');
                    approveModal.classList.remove('flex');
                }
            });
        })();

        // Modal Reject with AJAX
        (function() {
            const rejectModal = document.getElementById('reject-modal');
            const rejectForm = document.getElementById('reject-form');
            const rejectCancel = document.getElementById('reject-cancel');
            let currentLpkId = null;

            if (!rejectModal || !rejectForm) return;

            document.querySelectorAll('.open-reject-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    const noreg = this.getAttribute('data-noreg');
                    currentLpkId = this.getAttribute('data-lpk-id');

                    rejectForm.setAttribute('action', url);

                    const rejectMsg = document.getElementById('reject-modal-msg');
                    if (rejectMsg) {
                        rejectMsg.textContent = `Apakah Anda yakin ingin Reject LPK ${noreg}?`;
                    }

                    rejectModal.classList.remove('hidden');
                    rejectModal.classList.add('flex');
                });
            });

            // AJAX form submission for reject
            rejectForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const url = this.getAttribute('action');
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Memproses...';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    rejectModal.classList.add('hidden');
                    rejectModal.classList.remove('flex');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    if (data.success) {
                        showToast(data.message || 'LPK berhasil ditolak!', 'success');
                        if (currentLpkId) {
                            updateRowStatus(currentLpkId, data.newStatusText || 'Ditolak Dept Head');
                            hideActionButtons(currentLpkId);
                        }
                    } else {
                        showToast(data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    rejectModal.classList.add('hidden');
                    rejectModal.classList.remove('flex');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    showToast('Terjadi kesalahan saat memproses', 'error');
                });
            });

            if (rejectCancel) {
                rejectCancel.addEventListener('click', function() {
                    rejectModal.classList.add('hidden');
                    rejectModal.classList.remove('flex');
                });
            }

            rejectModal.addEventListener('click', function(e) {
                if (e.target === rejectModal) {
                    rejectModal.classList.add('hidden');
                    rejectModal.classList.remove('flex');
                }
            });
        })();

        // Sync visible dd-mm-YYYY display into hidden ISO date before submit
        var filterForm = document.getElementById('filter-form');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                try {
                    var desktop = document.getElementById('date-picker-lpk');
                    var mobile = document.getElementById('date-picker-lpk-mobile');
                    var visible = desktop && desktop.value ? desktop : (mobile && mobile.value ? mobile : null);
                    var hidden = document.getElementById('date-hidden');
                    if (!hidden) return;
                    var val = visible ? visible.value.trim() : '';
                    if (!val) { hidden.value = ''; return; }
                    var m = val.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
                    if (m) {
                        var dd = m[1].padStart(2,'0'), mm = m[2].padStart(2,'0'), yy = m[3];
                        hidden.value = yy + '-' + mm + '-' + dd;
                    } else {
                        var m2 = val.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
                        if (m2) {
                            hidden.value = m2[1] + '-' + m2[2].padStart(2,'0') + '-' + m2[3].padStart(2,'0');
                        } else {
                            hidden.value = val;
                        }
                    }
                } catch (err) {
                    console && console.error('date sync error', err);
                }
            });
        }
    });
    </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/depthead/lpk/index.blade.php ENDPATH**/ ?>