<?php $__env->startSection('content'); ?>
    <div class="w-full m-0 p-0 -mt-0">
        <div class="m-0">
            <div
                class="bg-white rounded-none shadow-none overflow-hidden border-t-0 border-l-0 border-r-0 border-gray-100 w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <!-- Notification area fully removed for clean UI -->
                    </div>

                    
                    <form id="filter-form" method="GET" action="<?php echo e(route('ppchead.nqr.index')); ?>" class="mb-4">
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
                            
                            
                            <input type="hidden" name="date" id="date-hidden" value="<?php echo e(request('date')); ?>" />
                            <div class="block lg:hidden space-y-2">
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Pencarian</label>
                                    <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                                        placeholder="Cari no reg, part, problem..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Tanggal</label>
                                        <input type="text" id="date-picker-ppchead-mobile" name="date_display"
                                            value="<?php echo e($dateValue); ?>" placeholder="dd-mm-yyyy"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500"
                                            readonly />
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Tahun</label>
                                        <select name="year"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?>

                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Status NQR</label>
                                        <select name="status_nqr"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            <option value="Claim" <?php echo e(request('status_nqr') == 'Claim' ? 'selected' : ''); ?>>
                                                Claim</option>
                                            <option value="Complaint (Informasi)" <?php echo e(request('status_nqr') == 'Complaint (Informasi)' ? 'selected' : ''); ?>>Complaint (Informasi)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                        <select name="approval_status"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            <option value="menunggu_request" <?php echo e(request('approval_status') == 'menunggu_request' ? 'selected' : ''); ?>>Menunggu
                                                Request</option>
                                            <option value="menunggu_foreman" <?php echo e(request('approval_status') == 'menunggu_foreman' ? 'selected' : ''); ?>>Menunggu
                                                Foreman</option>
                                            <option value="menunggu_sect" <?php echo e(request('approval_status') == 'menunggu_sect' ? 'selected' : ''); ?>>Menunggu Sect</option>
                                            <option value="menunggu_dept" <?php echo e(request('approval_status') == 'menunggu_dept' ? 'selected' : ''); ?>>Menunggu Dept</option>
                                            <option value="menunggu_ppc" <?php echo e(request('approval_status') == 'menunggu_ppc' ? 'selected' : ''); ?>>Menunggu PPC</option>
                                            <option value="menunggu_vdd" <?php echo e(request('approval_status') == 'menunggu_vdd' ? 'selected' : ''); ?>>Menunggu VDD</option>
                                            <option value="menunggu_procurement" <?php echo e(request('approval_status') == 'menunggu_procurement' ? 'selected' : ''); ?>>Menunggu Procurement</option>
                                            <option value="ditolak_foreman" <?php echo e(request('approval_status') == 'ditolak_foreman' ? 'selected' : ''); ?>>Ditolak Foreman</option>
                                            <option value="ditolak_sect" <?php echo e(request('approval_status') == 'ditolak_sect' ? 'selected' : ''); ?>>Ditolak Sect</option>
                                            <option value="ditolak_dept" <?php echo e(request('approval_status') == 'ditolak_dept' ? 'selected' : ''); ?>>Ditolak Dept</option>
                                            <option value="ditolak_ppc" <?php echo e(request('approval_status') == 'ditolak_ppc' ? 'selected' : ''); ?>>Ditolak PPC</option>
                                            <option value="ditolak_vdd" <?php echo e(request('approval_status') == 'ditolak_vdd' ? 'selected' : ''); ?>>Ditolak VDD</option>
                                            <option value="ditolak_procurement" <?php echo e(request('approval_status') == 'ditolak_procurement' ? 'selected' : ''); ?>>Ditolak Procurement</option>
                                            <option value="selesai" <?php echo e(request('approval_status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 pt-1">
                                    <button type="submit"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">Terapkan</button>
                                    <a href="<?php echo e(route('ppchead.nqr.index')); ?>"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">Reset</a>
                                </div>
                            </div>

                            <div class="hidden lg:flex gap-2 items-end">
                                <div class="flex-1 min-w-0">
                                    <label class="text-xs text-gray-600 font-medium">Pencarian</label>
                                    <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                                        placeholder="Cari no reg, supplier, part, PO..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Tanggal</label>
                                    <input type="text" id="date-picker-ppchead" name="date_display" value="<?php echo e($dateValue); ?>"
                                        placeholder="dd-mm-yyyy"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500"
                                        readonly />
                                </div>

                                <div class="w-28">
                                    <label class="text-xs text-gray-600 font-medium">Tahun</label>
                                    <select name="year"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                            <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?>

                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Status NQR</label>
                                    <select name="status_nqr"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        <option value="Claim" <?php echo e(request('status_nqr') == 'Claim' ? 'selected' : ''); ?>>Claim
                                        </option>
                                        <option value="Complaint (Informasi)" <?php echo e(request('status_nqr') == 'Complaint (Informasi)' ? 'selected' : ''); ?>>Complaint (Informasi)</option>
                                    </select>
                                </div>

                                <div class="w-40">
                                    <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                    <select name="approval_status"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        <option value="menunggu_request" <?php echo e(request('approval_status') == 'menunggu_request' ? 'selected' : ''); ?>>Menunggu Request</option>
                                        <option value="menunggu_foreman" <?php echo e(request('approval_status') == 'menunggu_foreman' ? 'selected' : ''); ?>>Menunggu Foreman</option>
                                        <option value="menunggu_sect" <?php echo e(request('approval_status') == 'menunggu_sect' ? 'selected' : ''); ?>>Menunggu Sect</option>
                                        <option value="menunggu_dept" <?php echo e(request('approval_status') == 'menunggu_dept' ? 'selected' : ''); ?>>Menunggu Dept</option>
                                        <option value="menunggu_ppc" <?php echo e(request('approval_status') == 'menunggu_ppc' ? 'selected' : ''); ?>>Menunggu PPC</option>
                                        <option value="menunggu_vdd" <?php echo e(request('approval_status') == 'menunggu_vdd' ? 'selected' : ''); ?>>Menunggu VDD</option>
                                        <option value="menunggu_procurement" <?php echo e(request('approval_status') == 'menunggu_procurement' ? 'selected' : ''); ?>>Menunggu Procurement</option>
                                        <option value="ditolak_foreman" <?php echo e(request('approval_status') == 'ditolak_foreman' ? 'selected' : ''); ?>>Ditolak Foreman</option>
                                        <option value="ditolak_sect" <?php echo e(request('approval_status') == 'ditolak_sect' ? 'selected' : ''); ?>>Ditolak Sect</option>
                                        <option value="ditolak_dept" <?php echo e(request('approval_status') == 'ditolak_dept' ? 'selected' : ''); ?>>Ditolak Dept</option>
                                        <option value="ditolak_ppc" <?php echo e(request('approval_status') == 'ditolak_ppc' ? 'selected' : ''); ?>>Ditolak PPC</option>
                                        <option value="ditolak_vdd" <?php echo e(request('approval_status') == 'ditolak_vdd' ? 'selected' : ''); ?>>Ditolak VDD</option>
                                        <option value="ditolak_procurement" <?php echo e(request('approval_status') == 'ditolak_procurement' ? 'selected' : ''); ?>>Ditolak Procurement</option>
                                        <option value="selesai" <?php echo e(request('approval_status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                                    </select>
                                </div>

                                <div class="flex gap-2 items-center flex-shrink-0">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md whitespace-nowrap transition-colors">Terapkan</button>
                                    <a href="<?php echo e(route('ppchead.nqr.index')); ?>"
                                        class="inline-flex items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md whitespace-nowrap transition-colors">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <script>
                        // Load Flatpickr from local vendor only (offline mode)
                        (function () {
                            function initFlatpickr(fp) {
                                if (!fp) return;
                                var selectors = ['#date-picker-ppchead', '#date-picker-ppchead-mobile'];
                                selectors.forEach(function (sel) {
                                    var el = document.querySelector(sel);
                                    if (!el) return;
                                    try {
                                        fp(el, {
                                            dateFormat: 'd-m-Y',
                                            allowInput: true,
                                            onChange: function (selectedDates, dateStr) {
                                                // Keep d-m-Y format for the server-side controller which expects d-m-Y
                                                if (dateStr) {
                                                    var hidden = document.getElementById('date-hidden');
                                                    if (hidden) hidden.value = dateStr;
                                                }
                                            }
                                        });
                                    } catch (e) {
                                        // ignore init errors
                                    }
                                });

                                // Ensure form submit converts visible date_display -> ISO
                                var form = document.getElementById('filter-form');
                                if (form) {
                                    form.addEventListener('submit', function (e) {
                                        var visible = document.querySelector('input[name="date_display"]');
                                        var hidden = document.getElementById('date-hidden');
                                        // copy visible d-m-Y into hidden so controller receives expected format
                                        if (visible && visible.value && hidden) {
                                            hidden.value = visible.value;
                                        }
                                    });
                                }
                            }

                            var cssHref = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.css")); ?>';
                            var jsSrc = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.js")); ?>';

                            // Load CSS
                            var link = document.createElement('link');
                            link.rel = 'stylesheet';
                            link.href = cssHref;
                            document.head.appendChild(link);

                            // Load JS
                            var script = document.createElement('script');
                            script.src = jsSrc;
                            script.defer = true;
                            script.onload = function () {
                                initFlatpickr(window.flatpickr || window.fp || null);
                            };
                            document.body.appendChild(script);
                        })();
                    </script>

                    <div class="responsive-table overflow-x-auto rounded-md ring-1 ring-gray-50">
                        <?php if($nqrs->count() > 0): ?>
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-red-600 text-white">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-44">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tl-lg">No
                                                Reg</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Tanggal
                                                Terbit</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Supplier</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Nama</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">No
                                                Part</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Status
                                                NQR</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-36">
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
                                    <?php $__currentLoopData = $nqrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nqr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition-colors"
                                                                    data-nqr-id="<?php echo e($nqr->id); ?>">
                                                                    <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($nqr->no_reg_nqr); ?></td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900">
                                                                        <?php echo e($nqr->tgl_terbit_nqr ? $nqr->tgl_terbit_nqr->format('d-m-Y') : '-'); ?>

                                                                    </td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($nqr->nama_supplier); ?></td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($nqr->nama_part); ?></td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($nqr->nomor_part); ?></td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900 text-center">
                                                                        <?php
                                                                            $statusNqr = $nqr->status_nqr;
                                                                            $badgeClass = $statusNqr === 'Claim' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800';
                                                                        ?>
                                                                        <span
                                                                            class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full <?php echo e($badgeClass); ?>">
                                                                            <?php echo e($statusNqr); ?>

                                                                        </span>
                                                                    </td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900 status-approval-cell">
                                                                        <?php echo e($nqr->status_approval); ?>

                                                                    </td>
                                                                    <td class="px-3 py-3 text-center text-sm hidden sm:table-cell">
                                                                        <div class="action-buttons-container">
                                                                            <?php if(
                                                                                    $nqr->status_approval === 'Selesai' ||
                                                                                    $nqr->status_approval === 'Ditolak Foreman' ||
                                                                                    $nqr->status_approval === 'Ditolak Sect Head' ||
                                                                                    $nqr->status_approval === 'Ditolak Dept Head' ||
                                                                                    $nqr->status_approval === 'Ditolak PPC Head'
                                                                                ): ?>
                                                                                <div class="flex flex-col items-center justify-center">
                                                                                    <div class="flex flex-row items-center justify-center gap-6 mb-2">
                                                                                        <div class="flex flex-col items-center">
                                                                                            <a href="<?php echo e(route('ppchead.nqr.previewFpdf', $nqr->id)); ?>"
                                                                                                target="_blank"
                                                                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                                                                                                title="Preview PDF (FPDF) - Print Preview">
                                                                                                <img src="<?php echo e(asset('icon/pdf.ico')); ?>" alt="Preview PDF"
                                                                                                    class="w-4 h-4" />
                                                                                            </a>
                                                                                            <span class="text-xs mt-1">PDF</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php elseif($nqr->status_approval === 'Menunggu Approval PPC Head'): ?>
                                                                                <?php
                                                                                    $ppcComplete = $nqr->disposition_claim && (
                                                                                        $nqr->disposition_claim === 'Pay Compensation' ||
                                                                                        ($nqr->disposition_claim === 'Send the Replacement' && $nqr->send_replacement_method)
                                                                                    );
                                                                                ?>
                                                                                
                                                                                <div class="flex flex-row items-center justify-center gap-6">
                                                                                    <div class="flex flex-col items-center gap-1">
                                                                                        <a href="<?php echo e(route('ppchead.nqr.edit', $nqr->id)); ?>"
                                                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                                                                                            title="Approve">
                                                                                            <img src="<?php echo e(asset('icon/approve.ico')); ?>" alt="Approve"
                                                                                                class="w-4 h-4" />
                                                                                        </a>
                                                                                        <span class="text-xs text-gray-500 mt-1">Approve</span>
                                                                                    </div>
                                                                                    <div class="flex flex-col items-center gap-1">
                                                                                        <button type="button"
                                                                                            class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                                                                                            title="Tolak" data-id="<?php echo e($nqr->id); ?>"
                                                                                            data-no-reg="<?php echo e($nqr->no_reg_nqr); ?>">
                                                                                            <img src="<?php echo e(asset('icon/cancel.ico')); ?>" alt="Reject"
                                                                                                class="w-4 h-4" />
                                                                                        </button>
                                                                                        <span class="text-xs text-gray-500 mt-1">Reject</span>
                                                                                    </div>
                                                                                    <div class="flex flex-col items-center">
                                                                                        <a href="<?php echo e(route('ppchead.nqr.previewFpdf', $nqr->id)); ?>"
                                                                                            target="_blank"
                                                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                                                                                            title="Preview PDF (FPDF) - Print Preview">
                                                                                            <img src="<?php echo e(asset('icon/pdf.ico')); ?>" alt="Preview PDF"
                                                                                                class="w-4 h-4" />
                                                                                        </a>
                                                                                        <span class="text-xs text-gray-500 mt-1">PDF</span>
                                                                                    </div>
                                                                                </div>
                                                                            <?php else: ?>
                                                                                
                                                                                <div class="flex flex-col items-center justify-center">
                                                                                    <div class="flex flex-row items-center justify-center gap-6 mb-2">
                                                                                        <div class="flex flex-col items-center">
                                                                                            <a href="<?php echo e(route('ppchead.nqr.previewFpdf', $nqr->id)); ?>"
                                                                                                target="_blank"
                                                                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                                                                                                title="Preview PDF (FPDF) - Print Preview">
                                                                                                <img src="<?php echo e(asset('icon/pdf.ico')); ?>" alt="Preview PDF"
                                                                                                    class="w-4 h-4" />
                                                                                            </a>
                                                                                            <span class="text-xs mt-1">PDF</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-center py-12 bg-white">
                                <p class="text-gray-500 text-sm">Tidak ada NQR yang perlu di-approve.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="mt-6 border-t border-gray-100 pt-4">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-medium"><?php echo e($nqrs->firstItem() ?? 0); ?></span> - <span
                                    class="font-medium"><?php echo e($nqrs->lastItem() ?? 0); ?></span> dari <span
                                    class="font-medium"><?php echo e($nqrs->total()); ?></span> data
                            </div>

                            <nav class="flex items-center gap-3" aria-label="Pagination">
                                <?php $prev = $nqrs->previousPageUrl();
                                $next = $nqrs->nextPageUrl(); ?>

                                
                                <a href="<?php echo e($prev ?: '#'); ?>"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($nqrs->onFirstPage() ? 'text-gray-400 border-gray-200 pointer-events-none bg-white' : 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm'); ?>"
                                    aria-disabled="<?php echo e($nqrs->onFirstPage() ? 'true' : 'false'); ?>">
                                    <span class="text-sm">
                                        < Sebelumnya</span>
                                </a>

                                
                                <div
                                    class="hidden sm:inline-flex items-center px-3 py-2 bg-white border border-gray-100 rounded-full shadow-sm text-sm text-gray-700">
                                    Halaman <span class="mx-2 font-semibold"><?php echo e($nqrs->currentPage()); ?></span> dari <span
                                        class="mx-2 font-medium"><?php echo e($nqrs->lastPage()); ?></span>
                                </div>

                                
                                <a href="<?php echo e($next ?: '#'); ?>"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($nqrs->hasMorePages() ? 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' : 'text-gray-400 border-gray-200 pointer-events-none bg-white'); ?>"
                                    aria-disabled="<?php echo e($nqrs->hasMorePages() ? 'false' : 'true'); ?>">
                                    <span class="text-sm">Berikutnya ></span>
                                </a>

                                
                                <div class="sm:hidden px-3 py-1 text-xs text-gray-600">Hal. <span
                                        class="font-medium"><?php echo e($nqrs->currentPage()); ?></span>/<span
                                        class="font-medium"><?php echo e($nqrs->lastPage()); ?></span></div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PPC Redirect Modal (styled) -->
    <div id="ppc-redirect-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        style="display: none;">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-2">PPC Form belum diisi</h3>
            <p id="ppc-redirect-msg" class="text-sm text-gray-700 mb-4">Anda harus mengisi PPC form sebelum menyetujui.</p>
            <div class="flex justify-end gap-3">
                <button id="ppc-redirect-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <button id="ppc-redirect-open" type="button"
                    class="px-4 py-2 rounded bg-yellow-500 text-white hover:bg-yellow-600">Buka Form</button>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div id="approve-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display: none;">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Approve</h3>
            <p id="approve-modal-msg" class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin Approve NQR ini?</p>
            <div class="flex justify-end gap-3">
                <button id="approve-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <form id="approve-form" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Approve</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div id="reject-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display: none;">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Reject</h3>
            <p id="reject-modal-msg" class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin Reject NQR ini?</p>
            <div class="flex justify-end gap-3">
                <button id="reject-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <form id="reject-form" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Reject</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toast Notification System
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-[9999] px-6 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
                toast.textContent = message;
                document.body.appendChild(toast);

                setTimeout(() => toast.classList.remove('translate-x-full'), 100);
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Update row after AJAX action
            function updateRowAfterAction(nqrId, newStatus, newStatusText, action) {
                const row = document.querySelector(`tr[data-nqr-id="${nqrId}"]`);
                if (!row) return;

                // Update status cell
                const statusCell = row.querySelector('.status-approval-cell');
                if (statusCell) {
                    statusCell.textContent = newStatusText;
                }

                // Update action buttons
                const actionContainer = row.querySelector('.action-buttons-container');
                if (actionContainer) {
                    // After approve/reject by PPC head, hide action buttons except PDF
                    const approveBtn = actionContainer.querySelector('.open-approve-modal');
                    const rejectBtn = actionContainer.querySelector('.open-reject-modal');
                    const ppcEditBtns = actionContainer.querySelectorAll('a[href*="edit"]');

                    if (approveBtn) approveBtn.closest('.flex.flex-col').style.display = 'none';
                    if (rejectBtn) rejectBtn.closest('.flex.flex-col').style.display = 'none';
                    ppcEditBtns.forEach(btn => {
                        const wrapper = btn.closest('.flex.flex-col');
                        if (wrapper) wrapper.style.display = 'none';
                    });
                }
            }

            // Approve modal
            const approveModal = document.getElementById('approve-modal');
            const approveForm = document.getElementById('approve-form');
            const approveCancel = document.getElementById('approve-cancel');
            const approveMsg = document.getElementById('approve-modal-msg');
            let currentApproveId = null;
            let currentApproveNoreg = null;

            document.querySelectorAll('.open-approve-modal').forEach(btn => {
                btn.addEventListener('click', function () {
                    currentApproveId = this.getAttribute('data-id');
                    currentApproveNoreg = this.getAttribute('data-no-reg');
                    const ppcComplete = this.getAttribute('data-ppc-complete') === 'true';
                    const ppcUrl = this.getAttribute('data-ppc-url');

                    // If PPC inputs are not complete, show a styled modal to redirect user to PPC form
                    if (!ppcComplete && ppcUrl) {
                        const ppcRedirectModal = document.getElementById('ppc-redirect-modal');
                        const ppcRedirectOpen = document.getElementById('ppc-redirect-open');
                        const ppcRedirectCancel = document.getElementById('ppc-redirect-cancel');
                        const ppcRedirectMsg = document.getElementById('ppc-redirect-msg');
                        ppcRedirectMsg.textContent = 'PPC form belum diisi. Anda harus mengisi PPC form sebelum menyetujui. Buka form sekarang?';
                        ppcRedirectOpen.onclick = function () { window.location.href = ppcUrl; };
                        ppcRedirectCancel.onclick = function () {
                            ppcRedirectModal.style.display = 'none';
                        };
                        ppcRedirectModal.style.display = 'flex';
                        return;
                    }

                    approveForm.setAttribute('action', `/ppchead/nqr/${currentApproveId}/approve`);
                    approveMsg.textContent = 'Apakah Anda yakin ingin menyetujui NQR ' + currentApproveNoreg + '?';
                    approveModal.style.display = 'flex';
                });
            });

            approveCancel.addEventListener('click', function () {
                approveModal.style.display = 'none';
            });

            approveModal.addEventListener('click', function (e) {
                if (e.target === approveModal) {
                    approveModal.style.display = 'none';
                }
            });

            // AJAX Approve
            approveForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const url = this.getAttribute('action');
                const formData = new FormData(this);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        approveModal.style.display = 'none';

                        if (data.success) {
                            showToast(data.message, 'success');
                            updateRowAfterAction(currentApproveId, data.newStatus, data.newStatusText, 'approve');
                        } else {
                            showToast(data.message || 'Gagal menyetujui NQR', 'error');
                        }
                    })
                    .catch(error => {
                        approveModal.style.display = 'none';
                        showToast('Terjadi kesalahan', 'error');
                    });
            });

            // Reject modal
            const rejectModal = document.getElementById('reject-modal');
            const rejectForm = document.getElementById('reject-form');
            const rejectCancel = document.getElementById('reject-cancel');
            const rejectMsg = document.getElementById('reject-modal-msg');
            let currentRejectId = null;
            let currentRejectNoreg = null;

            document.querySelectorAll('.open-reject-modal').forEach(btn => {
                btn.addEventListener('click', function () {
                    currentRejectId = this.getAttribute('data-id');
                    currentRejectNoreg = this.getAttribute('data-no-reg');
                    rejectForm.setAttribute('action', `/ppchead/nqr/${currentRejectId}/reject`);
                    rejectMsg.textContent = 'Apakah Anda yakin ingin menolak NQR ' + currentRejectNoreg + '?';
                    rejectModal.style.display = 'flex';
                });
            });

            rejectCancel.addEventListener('click', function () {
                rejectModal.style.display = 'none';
            });

            rejectModal.addEventListener('click', function (e) {
                if (e.target === rejectModal) {
                    rejectModal.style.display = 'none';
                }
            });

            // AJAX Reject
            rejectForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const url = this.getAttribute('action');
                const formData = new FormData(this);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        rejectModal.style.display = 'none';

                        if (data.success) {
                            showToast(data.message, 'success');
                            updateRowAfterAction(currentRejectId, data.newStatus, data.newStatusText, 'reject');
                        } else {
                            showToast(data.message || 'Gagal menolak NQR', 'error');
                        }
                    })
                    .catch(error => {
                        rejectModal.style.display = 'none';
                        showToast('Terjadi kesalahan', 'error');
                    });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/ppchead/nqr/index.blade.php ENDPATH**/ ?>