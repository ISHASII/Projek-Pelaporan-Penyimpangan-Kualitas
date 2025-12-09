<?php $__env->startSection('content'); ?>
    <div class="w-full m-0 p-0 -mt-0">
        <div class="m-0">
            <div
                class="bg-white rounded-none shadow-none overflow-hidden border-t-0 border-l-0 border-r-0 border-gray-100 w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div></div>
                    </div>

                    <form id="filter-form" method="GET" action="<?php echo e(route('secthead.cmr.index')); ?>" class="mb-4">
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
                                    <label class="text-xs text-gray-600 font-medium">Search</label>
                                    <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                                        placeholder="Search reg no, supplier, part..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5" />
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Date</label>
                                        <input type="text" id="date-picker-cmr-mobile" name="date_display"
                                            value="<?php echo e($dateValue); ?>" placeholder="dd-mm-yyyy" readonly
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5" />
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Year</label>
                                        <select name="year"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                            <option value="">All</option>
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
                                        <label class="text-xs text-gray-600 font-medium">Product</label>
                                        <select name="product"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                            <option value="">All</option>
                                            <option value="SKA" <?php echo e(request('product') == 'SKA' ? 'selected' : ''); ?>>SKA
                                            </option>
                                            <option value="OCU" <?php echo e(request('product') == 'OCU' ? 'selected' : ''); ?>>OCU
                                            </option>
                                            <option value="FF" <?php echo e(request('product') == 'FF' ? 'selected' : ''); ?>>FF</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Approval Status</label>
                                        <select name="approval_status"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                            <option value="">All</option>
                                            <option value="pending_request" <?php echo e(request('approval_status') == 'pending_request' ? 'selected' : ''); ?>>Pending Request</option>
                                            <option value="waiting_sect" <?php echo e(request('approval_status') == 'waiting_sect' ? 'selected' : ''); ?>>Waiting For Sect Head</option>
                                            <option value="waiting_dept" <?php echo e(request('approval_status') == 'waiting_dept' ? 'selected' : ''); ?>>Waiting For Dept Head</option>
                                            <option value="waiting_agm" <?php echo e(request('approval_status') == 'waiting_agm' ? 'selected' : ''); ?>>Waiting For AGM</option>
                                            <option value="waiting_ppc" <?php echo e(request('approval_status') == 'waiting_ppc' ? 'selected' : ''); ?>>Waiting For PPC Head</option>
                                            <option value="waiting_procurement" <?php echo e(request('approval_status') == 'waiting_procurement' ? 'selected' : ''); ?>>
                                                Waiting For Procurement</option>
                                            <option value="waiting_vdd" <?php echo e(request('approval_status') == 'waiting_vdd' ? 'selected' : ''); ?>>Waiting For VDD</option>
                                            <option value="rejected_sect" <?php echo e(request('approval_status') == 'rejected_sect' ? 'selected' : ''); ?>>Rejected By Sect Head</option>
                                            <option value="rejected_dept" <?php echo e(request('approval_status') == 'rejected_dept' ? 'selected' : ''); ?>>Rejected By Dept Head</option>
                                            <option value="rejected_agm" <?php echo e(request('approval_status') == 'rejected_agm' ? 'selected' : ''); ?>>Rejected By AGM</option>
                                            <option value="rejected_ppc" <?php echo e(request('approval_status') == 'rejected_ppc' ? 'selected' : ''); ?>>Rejected By PPC Head</option>
                                            <option value="rejected_vdd" <?php echo e(request('approval_status') == 'rejected_vdd' ? 'selected' : ''); ?>>Rejected By VDD</option>
                                            <option value="rejected_procurement" <?php echo e(request('approval_status') == 'rejected_procurement' ? 'selected' : ''); ?>>
                                                Rejected By Procurement</option>
                                            <option value="completed" <?php echo e(request('approval_status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-2 pt-1">
                                    <button type="submit"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">Apply</button>
                                    <a href="<?php echo e(route('secthead.cmr.index')); ?>"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">Reset</a>
                                    <div></div>
                                </div>
                            </div>

                            <div class="hidden lg:flex gap-2 items-end">
                                <div class="flex-1 min-w-0">
                                    <label class="text-xs text-gray-600 font-medium">Search</label>
                                    <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                                        placeholder="Search reg no, supplier, part, PO..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5" />
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Date</label>
                                    <input type="text" id="date-picker-cmr" name="date_display" value="<?php echo e($dateValue); ?>"
                                        placeholder="dd-mm-yyyy" readonly
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5" />
                                </div>

                                <div class="w-28">
                                    <label class="text-xs text-gray-600 font-medium">Year</label>
                                    <select name="year"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                        <option value="">All</option>
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
                                    <label class="text-xs text-gray-600 font-medium">Product</label>
                                    <select name="product"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                        <option value="">All</option>
                                        <option value="SKA" <?php echo e(request('product') == 'SKA' ? 'selected' : ''); ?>>SKA</option>
                                        <option value="OCU" <?php echo e(request('product') == 'OCU' ? 'selected' : ''); ?>>OCU</option>
                                        <option value="FF" <?php echo e(request('product') == 'FF' ? 'selected' : ''); ?>>FF</option>
                                    </select>
                                </div>

                                <div class="w-40">
                                    <label class="text-xs text-gray-600 font-medium">Approval Status</label>
                                    <select name="approval_status"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                        <option value="">All</option>
                                        <option value="pending_request" <?php echo e(request('approval_status') == 'pending_request' ? 'selected' : ''); ?>>Pending Request</option>
                                        <option value="waiting_sect" <?php echo e(request('approval_status') == 'waiting_sect' ? 'selected' : ''); ?>>Waiting For Sect Head</option>
                                        <option value="waiting_dept" <?php echo e(request('approval_status') == 'waiting_dept' ? 'selected' : ''); ?>>Waiting For Dept Head</option>
                                        <option value="waiting_agm" <?php echo e(request('approval_status') == 'waiting_agm' ? 'selected' : ''); ?>>Waiting For AGM</option>
                                        <option value="waiting_ppc" <?php echo e(request('approval_status') == 'waiting_ppc' ? 'selected' : ''); ?>>Waiting For PPC Head</option>
                                        <option value="waiting_procurement" <?php echo e(request('approval_status') == 'waiting_procurement' ? 'selected' : ''); ?>>Waiting
                                            For Procurement</option>
                                        <option value="waiting_vdd" <?php echo e(request('approval_status') == 'waiting_vdd' ? 'selected' : ''); ?>>Waiting For VDD</option>
                                        <option value="rejected_sect" <?php echo e(request('approval_status') == 'rejected_sect' ? 'selected' : ''); ?>>Rejected By Sect Head</option>
                                        <option value="rejected_dept" <?php echo e(request('approval_status') == 'rejected_dept' ? 'selected' : ''); ?>>Rejected By Dept Head</option>
                                        <option value="rejected_agm" <?php echo e(request('approval_status') == 'rejected_agm' ? 'selected' : ''); ?>>Rejected By AGM</option>
                                        <option value="rejected_ppc" <?php echo e(request('approval_status') == 'rejected_ppc' ? 'selected' : ''); ?>>Rejected By PPC Head</option>
                                        <option value="rejected_vdd" <?php echo e(request('approval_status') == 'rejected_vdd' ? 'selected' : ''); ?>>Rejected By VDD</option>
                                        <option value="rejected_procurement" <?php echo e(request('approval_status') == 'rejected_procurement' ? 'selected' : ''); ?>>Rejected
                                            By Procurement</option>
                                        <option value="completed" <?php echo e(request('approval_status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                    </select>
                                </div>

                                <div class="flex gap-2 items-center flex-shrink-0">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md whitespace-nowrap transition-colors">Apply</button>
                                    <a href="<?php echo e(route('secthead.cmr.index')); ?>"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md whitespace-nowrap transition-colors">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="responsive-table overflow-x-auto rounded-md ring-1 ring-gray-50">
                        <?php if(isset($cmrs) && count($cmrs)): ?>
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-red-600 text-white">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">No
                                                Reg</span>
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(登録不要)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">CMR
                                                ISSUE DATE</span>
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(発行日)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">SUPPLIER
                                                NAME </span>
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(サプライヤ名)
                                            </span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">PART
                                                NAME</span>
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(部品名)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">PART
                                                NUMBER</span>
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(部品番号)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">PRODUCT</span>
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(製品)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-36">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Status
                                                Approval</span>
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">ステータス承認</span>
                                        </th>
                                        <th class="px-3 py-2 text-center hidden sm:table-cell w-28">
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tr-lg">Action</span>
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tr-lg">アクション</span>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php $__currentLoopData = $cmrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cmr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition-colors"
                                            data-cmr-id="<?php echo e($cmr->id); ?>">
                                            <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($cmr->no_reg); ?></td>
                                            <td class="px-3 py-3 text-sm text-gray-900">
                                                <?php echo e($cmr->tgl_terbit_cmr ? (is_string($cmr->tgl_terbit_cmr) ? (strtotime($cmr->tgl_terbit_cmr) ? date('d-m-Y', strtotime($cmr->tgl_terbit_cmr)) : '') : $cmr->tgl_terbit_cmr->format('d-m-Y')) : ''); ?>

                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($cmr->nama_supplier); ?></td>
                                            <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($cmr->nama_part); ?></td>
                                            <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($cmr->nomor_part); ?></td>

                                            <td class="px-3 py-3 text-sm text-gray-900 text-center">
                                                <?php
                                                    $prod = strtoupper(trim($cmr->product ?? ''));
                                                    $badgeClass = 'bg-gray-100 text-gray-800';
                                                    $prodText = $cmr->product ?? '-';
                                                    if ($prod === 'SKA') {
                                                        $badgeClass = 'bg-amber-100 text-amber-800';
                                                    } elseif ($prod === 'OCU') {
                                                        $badgeClass = 'bg-blue-100 text-blue-800';
                                                    } elseif ($prod === 'FF') {
                                                        $badgeClass = 'bg-green-100 text-green-800';
                                                    }
                                                ?>
                                                <span
                                                    class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full <?php echo e($badgeClass); ?>"><?php echo e($prodText); ?></span>
                                            </td>

                                            <td class="px-3 py-3 text-sm text-gray-900 status-approval-cell">
                                                <?php
                                                    $sect = strtolower($cmr->secthead_status ?? 'pending');
                                                    $dept = strtolower($cmr->depthead_status ?? 'pending');
                                                    $agm = strtolower($cmr->agm_status ?? '');
                                                    $ppc = strtolower($cmr->ppchead_status ?? 'pending');
                                                    $vdd = strtolower($cmr->vdd_status ?? '');
                                                    $proc = strtolower($cmr->procurement_status ?? '');

                                                    if (is_null($cmr->requested_at_qc)) {
                                                        $statusMsg = 'Waiting for request to be sent';
                                                    } elseif ($sect === 'rejected') {
                                                        $statusMsg = 'Rejected by Sect Head';
                                                    } elseif ($dept === 'rejected') {
                                                        $statusMsg = 'Rejected by Dept Head';
                                                    } elseif ($agm === 'rejected') {
                                                        $statusMsg = 'Rejected by AGM';
                                                    } elseif ($ppc === 'rejected') {
                                                        $statusMsg = 'Rejected by PPC Head';
                                                    } elseif ($vdd === 'rejected') {
                                                        $statusMsg = 'Rejected by VDD';
                                                    } elseif ($proc === 'rejected') {
                                                        $statusMsg = 'Rejected by Procurement';
                                                    } elseif (in_array('canceled', [$sect, $dept, $agm, $ppc, $proc])) {
                                                        $statusMsg = 'Canceled';
                                                    } elseif ($sect === 'pending') {
                                                        $statusMsg = 'Waiting for Sect Head approval';
                                                    } elseif ($sect === 'approved' && $dept === 'pending') {
                                                        $statusMsg = 'Waiting for Dept Head approval';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'pending') {
                                                        $statusMsg = 'Waiting for AGM approval';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'pending') {
                                                        $statusMsg = 'Waiting for PPC Head approval';
                                                    } elseif ($ppc === 'approved' && $vdd === 'pending') {
                                                        $statusMsg = 'Waiting for VDD approval';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'approved' && $proc === 'pending') {
                                                        $statusMsg = 'Waiting for Procurement approval';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'approved' && $proc === 'approved') {
                                                        $statusMsg = 'Completed';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'approved' && empty($proc)) {
                                                        $statusMsg = 'Completed';
                                                    } else {
                                                        $statusMsg = $cmr->status_approval ?? '-';
                                                    }
                                                ?>
                                                <div class="font-medium leading-tight"><?php echo e($statusMsg); ?></div>
                                            </td>
                                            <td class="px-3 py-3 text-center text-sm hidden sm:table-cell action-buttons-container">
                                                <div class="flex flex-col items-center justify-center gap-2">
                                                    <div class="flex items-center justify-center gap-4">
                                                        <?php $sectStatus = strtolower($cmr->secthead_status ?? 'pending'); ?>
                                                        <?php if(!is_null($cmr->requested_at_qc) && $sectStatus === 'pending'): ?>
                                                            <div class="flex flex-col items-center gap-1">
                                                                <button type="button"
                                                                    class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50"
                                                                    title="Setuju"
                                                                    data-url="<?php echo e(route('secthead.cmr.approve', $cmr->id)); ?>"
                                                                    data-cmr-id="<?php echo e($cmr->id); ?>" data-noreg="<?php echo e($cmr->no_reg); ?>">
                                                                    <img src="<?php echo e(asset('icon/approve.ico')); ?>" alt="Approve"
                                                                        class="w-4 h-4" />
                                                                </button>
                                                                <span class="text-xs text-gray-500 mt-1">Approve</span>
                                                            </div>
                                                            <div class="flex flex-col items-center gap-1">
                                                                <button type="button"
                                                                    class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50"
                                                                    title="Tolak"
                                                                    data-url="<?php echo e(route('secthead.cmr.reject', $cmr->id)); ?>"
                                                                    data-cmr-id="<?php echo e($cmr->id); ?>" data-noreg="<?php echo e($cmr->no_reg); ?>">
                                                                    <img src="<?php echo e(asset('icon/cancel.ico')); ?>" alt="Reject"
                                                                        class="w-4 h-4" />
                                                                </button>
                                                                <span class="text-xs text-gray-500 mt-1">Reject</span>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if(!is_null($cmr->requested_at_qc)): ?>
                                                            <div class="flex flex-col items-center gap-1">
                                                                <a href="<?php echo e(route('secthead.cmr.previewFpdf', $cmr->id)); ?>"
                                                                    target="_blank"
                                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100"
                                                                    title="Preview PDF">
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
                                <div class="text-lg font-medium">Tidak ada data CMR</div>
                                <div class="text-sm">Belum ada CMR yang sesuai dengan filter yang dipilih.</div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if(isset($cmrs) && $cmrs->hasPages()): ?>
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            <div class="flex items-center justify-between">
                                <nav class="flex items-center justify-center space-x-2 sm:justify-between w-full">
                                    <?php $prev = $cmrs->previousPageUrl();
                                    $next = $cmrs->nextPageUrl(); ?>

                                    <a href="<?php echo e($prev ?: '#'); ?>"
                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($cmrs->onFirstPage() ? 'text-gray-400 border-gray-200 pointer-events-none bg-white' : 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm'); ?>">
                                        <span class="text-sm">
                                            < Sebelumnya</span>
                                    </a>

                                    <div
                                        class="hidden sm:inline-flex items-center px-3 py-2 bg-white border border-gray-100 rounded-full shadow-sm text-sm text-gray-700">
                                        Halaman <span class="mx-2 font-semibold"><?php echo e($cmrs->currentPage()); ?></span> dari <span
                                            class="mx-2 font-medium"><?php echo e($cmrs->lastPage()); ?></span>
                                    </div>

                                    <a href="<?php echo e($next ?: '#'); ?>"
                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($cmrs->hasMorePages() ? 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' : 'text-gray-400 border-gray-200 pointer-events-none bg-white'); ?>">
                                        <span class="text-sm">Berikutnya ></span>
                                    </a>

                                    <div class="sm:hidden px-3 py-1 text-xs text-gray-600">Hal. <span
                                            class="font-medium"><?php echo e($cmrs->currentPage()); ?></span>/<span
                                            class="font-medium"><?php echo e($cmrs->lastPage()); ?></span></div>
                                </nav>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve and Reject modals for Secthead -->
    <div id="approve-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Approve</h3>
            <p id="approve-modal-msg" class="text-sm text-gray-700 mb-4">Apakah Anda yakin ingin Approve CMR ini?</p>

            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Pilih Dept Head yang ingin menerima email notifikasi (centang salah satu atau beberapa):</p>
                <div class="mb-2 flex items-center justify-between">
                    <div class="text-xs text-gray-500">Pilih penerima:</div>
                    <div class="text-xs text-gray-500"><label class="inline-flex items-center gap-2"><input type="checkbox" id="approve-recipients-select-all"> Pilih semua</label></div>
                </div>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded p-2 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $deptApprovers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $da): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="recipients[]" value="<?php echo e($da->npk); ?>" class="approve-recipient-checkbox">
                            <span class="truncate"><?php echo e($da->name); ?> <?php if($da->email): ?> &lt;<?php echo e($da->email); ?>&gt; <?php endif; ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-2 text-sm text-gray-500 italic">Tidak ada approver Dept Head yang tersedia.</div>
                    <?php endif; ?>
                </div>
                <div class="text-xs text-gray-500 mt-1">Tidak memilih siapa pun akan mengirim ke semua approver.</div>
            </div>

            <div class="flex justify-end gap-3">
                <button id="approve-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <button id="approve-confirm-btn" type="button"
                    class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Approve</button>
            </div>
        </div>
    </div>

    <div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Reject</h3>
            <p id="reject-modal-msg" class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin Reject CMR ini?</p>
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

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Toast notification function
                function showToast(message, type = 'success') {
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 z-[9999] px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full';
                    toast.style.backgroundColor = type === 'success' ? '#16a34a' : '#dc2626';
                    toast.innerHTML = '<div class="flex items-center gap-2"><span>' + message + '</span></div>';
                    document.body.appendChild(toast);
                    setTimeout(function () { toast.classList.remove('translate-x-full'); }, 10);
                    setTimeout(function () {
                        toast.classList.add('translate-x-full');
                        setTimeout(function () { toast.remove(); }, 300);
                    }, 3000);
                }

                // Update row after action
                function updateRowAfterAction(cmrId, newStatus, hideActions) {
                    var row = document.querySelector('tr[data-cmr-id="' + cmrId + '"]');
                    if (!row) return;
                    var statusCell = row.querySelector('.status-approval-cell');
                    if (statusCell) {
                        statusCell.innerHTML = '<div class="font-medium leading-tight">' + newStatus + '</div>';
                    }
                    if (hideActions) {
                        var actionCell = row.querySelector('.action-buttons-container');
                        if (actionCell) {
                            actionCell.innerHTML = '<div class="flex flex-col items-center gap-1"><a href="<?php echo e(url("secthead/cmr")); ?>/' + cmrId + '/preview-fpdf" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100" title="Preview PDF"><img src="<?php echo e(asset("icon/pdf.ico")); ?>" alt="PDF" class="w-4 h-4" /></a><span class="text-xs text-gray-500 mt-1">PDF</span></div>';
                        }
                    }
                }

                // flatpickr init (local asset intent)
                (function attachCalendar() {
                    function init(fp) {
                        var locale = {
                            firstDayOfWeek: 1,
                            weekdays: { shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'], longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] },
                            months: { shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] }
                        };

                        ['#date-picker-cmr', '#date-picker-cmr-mobile'].forEach(function (selector) {
                            var el = document.querySelector(selector); if (!el) return;
                            try { fp(el, { dateFormat: 'd-m-Y', allowInput: true, defaultDate: el.value ? el.value : undefined, locale: locale, onOpen: function(selectedDates, dateStr, instance) { if (!instance.input.value) instance.jumpToDate(new Date()); } }); } catch (e) { console && console.error(e); }
                        });
                    }

                    if (window.flatpickr) { init(window.flatpickr); return; }
                    var link = document.createElement('link'); link.rel = 'stylesheet'; link.href = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.css")); ?>'; document.head.appendChild(link);
                    var s = document.createElement('script'); s.src = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.js")); ?>'; s.onload = function () { if (window.flatpickr) init(window.flatpickr); }; document.body.appendChild(s);
                })();

                // Approve/Reject modal handlers with AJAX
                (function () {
                    const approveModal = document.getElementById('approve-modal');
                    const approveConfirmBtn = document.getElementById('approve-confirm-btn');
                    const approveCancel = document.getElementById('approve-cancel');
                    const approveRecipientsSelectAll = document.getElementById('approve-recipients-select-all');
                    const rejectModal = document.getElementById('reject-modal');
                    const rejectForm = document.getElementById('reject-form');
                    const rejectCancel = document.getElementById('reject-cancel');

                    let currentApproveUrl = null;
                    let currentApproveCmrId = null;
                    let currentRejectUrl = null;
                    let currentRejectCmrId = null;

                    // Recipients select all functionality
                    if (approveRecipientsSelectAll) {
                        approveRecipientsSelectAll.addEventListener('change', function() {
                            document.querySelectorAll('#approve-modal .approve-recipient-checkbox').forEach(function(cb) {
                                cb.checked = approveRecipientsSelectAll.checked;
                            });
                        });
                    }

                    document.querySelectorAll('.open-approve-modal').forEach(btn => {
                        btn.addEventListener('click', function () {
                            currentApproveUrl = this.getAttribute('data-url');
                            currentApproveCmrId = this.getAttribute('data-cmr-id');
                            var noreg = this.getAttribute('data-noreg');
                            var msg = document.getElementById('approve-modal-msg'); if (msg) msg.textContent = 'Apakah Anda yakin ingin Approve CMR ' + (noreg || '') + '?';
                            // Reset checkboxes
                            if (approveRecipientsSelectAll) approveRecipientsSelectAll.checked = false;
                            document.querySelectorAll('#approve-modal .approve-recipient-checkbox').forEach(function(cb) { cb.checked = false; });
                            if (approveModal) { approveModal.classList.remove('hidden'); approveModal.classList.add('flex'); }
                        });
                    });

                    document.querySelectorAll('.open-reject-modal').forEach(btn => {
                        btn.addEventListener('click', function () {
                            currentRejectUrl = this.getAttribute('data-url');
                            currentRejectCmrId = this.getAttribute('data-cmr-id');
                            var noreg = this.getAttribute('data-noreg');
                            var msg = document.getElementById('reject-modal-msg'); if (msg) msg.textContent = 'Apakah Anda yakin ingin Reject CMR ' + (noreg || '') + '?';
                            if (rejectModal) { rejectModal.classList.remove('hidden'); rejectModal.classList.add('flex'); }
                        });
                    });

                    if (approveCancel) approveCancel.addEventListener('click', function () { approveModal.classList.add('hidden'); approveModal.classList.remove('flex'); currentApproveUrl = null; currentApproveCmrId = null; });
                    if (rejectCancel) rejectCancel.addEventListener('click', function () { rejectModal.classList.add('hidden'); rejectModal.classList.remove('flex'); currentRejectUrl = null; currentRejectCmrId = null; });

                    [approveModal, rejectModal].forEach(function (mod) { if (!mod) return; mod.addEventListener('click', function (e) { if (e.target === mod) { mod.classList.add('hidden'); mod.classList.remove('flex'); } }); });

                    // AJAX for approve
                    if (approveConfirmBtn) {
                        approveConfirmBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            if (!currentApproveUrl || !currentApproveCmrId) return;

                            var formData = new FormData();
                            formData.append('_token', '<?php echo e(csrf_token()); ?>');

                            // Add selected recipients
                            document.querySelectorAll('#approve-modal .approve-recipient-checkbox:checked').forEach(function(cb) {
                                formData.append('recipients[]', cb.value);
                            });

                            approveConfirmBtn.disabled = true;
                            approveConfirmBtn.textContent = 'Processing...';

                            fetch(currentApproveUrl, {
                                method: 'POST',
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                                body: formData
                            })
                                .then(function (response) { return response.json(); })
                                .then(function (data) {
                                    approveConfirmBtn.disabled = false;
                                    approveConfirmBtn.textContent = 'Approve';
                                    approveModal.classList.add('hidden');
                                    approveModal.classList.remove('flex');

                                    if (data.success) {
                                        showToast(data.message || 'CMR approved successfully!', 'success');
                                        updateRowAfterAction(currentApproveCmrId, data.new_status || 'Waiting for Dept Head approval', data.hide_actions);
                                    } else {
                                        showToast(data.message || 'Failed to approve CMR.', 'error');
                                    }
                                    currentApproveUrl = null;
                                    currentApproveCmrId = null;
                                })
                                .catch(function (err) {
                                    approveConfirmBtn.disabled = false;
                                    approveConfirmBtn.textContent = 'Approve';
                                    showToast('An error occurred. Please try again.', 'error');
                                    console.error(err);
                                });
                        });
                    }

                    // AJAX for reject
                    if (rejectForm) {
                        rejectForm.addEventListener('submit', function (e) {
                            e.preventDefault();
                            if (!currentRejectUrl || !currentRejectCmrId) return;

                            var formData = new FormData();
                            formData.append('_token', '<?php echo e(csrf_token()); ?>');

                            var submitBtn = rejectForm.querySelector('button[type="submit"]');
                            if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Processing...'; }

                            fetch(currentRejectUrl, {
                                method: 'POST',
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                                body: formData
                            })
                                .then(function (response) { return response.json(); })
                                .then(function (data) {
                                    if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Reject'; }
                                    rejectModal.classList.add('hidden');
                                    rejectModal.classList.remove('flex');

                                    if (data.success) {
                                        showToast(data.message || 'CMR rejected successfully!', 'success');
                                        updateRowAfterAction(currentRejectCmrId, data.new_status || 'Rejected by Sect Head', data.hide_actions);
                                    } else {
                                        showToast(data.message || 'Failed to reject CMR.', 'error');
                                    }
                                    currentRejectUrl = null;
                                    currentRejectCmrId = null;
                                })
                                .catch(function (err) {
                                    if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Reject'; }
                                    showToast('An error occurred. Please try again.', 'error');
                                    console.error(err);
                                });
                        });
                    }
                })();

                // Sync visible dd-mm-YYYY display into hidden ISO date before submit
                var filterForm = document.getElementById('filter-form');
                if (filterForm) {
                    filterForm.addEventListener('submit', function (e) {
                        try {
                            var desktop = document.getElementById('date-picker-cmr');
                            var mobile = document.getElementById('date-picker-cmr-mobile');
                            var visible = desktop && desktop.value ? desktop : (mobile && mobile.value ? mobile : null);
                            var hidden = document.getElementById('date-hidden'); if (!hidden) return;
                            var val = visible ? visible.value.trim() : '';
                            if (!val) { hidden.value = ''; return; }
                            var m = val.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
                            if (m) { var dd = m[1].padStart(2, '0'), mm = m[2].padStart(2, '0'), yy = m[3]; hidden.value = yy + '-' + mm + '-' + dd; }
                            else { var m2 = val.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/); if (m2) { hidden.value = m2[1] + '-' + m2[2].padStart(2, '0') + '-' + m2[3].padStart(2, '0'); } else { hidden.value = val; } }
                        } catch (err) { console && console.error('date sync error', err); }
                    });
                }
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/secthead/cmr/index.blade.php ENDPATH**/ ?>