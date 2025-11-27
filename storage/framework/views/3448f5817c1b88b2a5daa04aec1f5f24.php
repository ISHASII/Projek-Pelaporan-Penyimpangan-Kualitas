<?php $__env->startSection('content'); ?>
    <div class="w-full m-0 p-0 -mt-0">
        <div class="m-0">
            <div
                class="bg-white rounded-none shadow-none overflow-hidden border-t-0 border-l-0 border-r-0 border-gray-100 w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                    </div>
                    <form method="GET" action="<?php echo e(route('qc.cmr.index')); ?>" class="mb-4">

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
                                    <label class="text-xs text-gray-600 font-medium">Search</label>
                                    <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                                        placeholder="Search reg no, supplier, part..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Date</label>
                                        <input type="text" id="date-picker-qc-mobile" name="date" value="<?php echo e($dateValue); ?>"
                                            placeholder="dd-mm-yyyy" readonly
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Year</label>
                                        <select name="year"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">All</option>
                                            <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?>

                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Product</label>
                                        <select name="product"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">All</option>
                                            <option value="SKA" <?php echo e(request('product') == 'SKA' ? 'selected' : ''); ?>>SKA
                                            </option>
                                            <option value="FF" <?php echo e(request('product') == 'FF' ? 'selected' : ''); ?>>FF</option>
                                            <option value="OCU" <?php echo e(request('product') == 'OCU' ? 'selected' : ''); ?>>OCU
                                            </option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Approval Status</label>
                                        <select name="approval_status"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">All</option>
                                            <option value="pending_request" <?php echo e(request('approval_status') == 'pending_request' ? 'selected' : ''); ?>>Pending Request</option>
                                            <option value="waiting_sect" <?php echo e(request('approval_status') == 'waiting_sect' ? 'selected' : ''); ?>>Waiting For Sect Head</option>
                                            <option value="waiting_dept" <?php echo e(request('approval_status') == 'waiting_dept' ? 'selected' : ''); ?>>Waiting For Dept Head</option>
                                            <option value="waiting_agm" <?php echo e(request('approval_status') == 'waiting_agm' ? 'selected' : ''); ?>>Waiting For AGM</option>
                                            <option value="waiting_ppc" <?php echo e(request('approval_status') == 'waiting_ppc' ? 'selected' : ''); ?>>Waiting For PPC Head</option>
                                            <option value="waiting_procurement" <?php echo e(request('approval_status') == 'waiting_procurement' ? 'selected' : ''); ?>>
                                                Waiting For Procurement</option>
                                            <option value="rejected_sect" <?php echo e(request('approval_status') == 'rejected_sect' ? 'selected' : ''); ?>>Rejected By Sect Head</option>
                                            <option value="rejected_dept" <?php echo e(request('approval_status') == 'rejected_dept' ? 'selected' : ''); ?>>Rejected By Dept Head</option>
                                            <option value="rejected_agm" <?php echo e(request('approval_status') == 'rejected_agm' ? 'selected' : ''); ?>>Rejected By AGM</option>
                                            <option value="rejected_ppc" <?php echo e(request('approval_status') == 'rejected_ppc' ? 'selected' : ''); ?>>Rejected By PPC Head</option>
                                            <option value="rejected_procurement" <?php echo e(request('approval_status') == 'rejected_procurement' ? 'selected' : ''); ?>>
                                                Rejected By Procurement</option>
                                            <option value="completed" <?php echo e(request('approval_status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-2 pt-1">
                                    <button type="submit"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">Apply</button>
                                    <a href="<?php echo e(route('qc.cmr.index')); ?>"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">Reset</a>
                                    <a href="<?php echo e(route('qc.cmr.create')); ?>"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                                        <span class="text-lg leading-none">+</span>
                                    </a>
                                </div>
                            </div>

                            <div class="hidden lg:flex gap-2 items-end">
                                <div class="flex-1 min-w-0">
                                    <label class="text-xs text-gray-600 font-medium">Search</label>
                                    <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                                        placeholder="Search reg no, supplier, part, PO..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Date</label>
                                    <input type="text" id="date-picker-qc" name="date" value="<?php echo e($dateValue); ?>"
                                        placeholder="dd-mm-yyyy" readonly
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="w-28">
                                    <label class="text-xs text-gray-600 font-medium">Year</label>
                                    <select name="year"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">All</option>
                                        <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                            <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?>

                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Product</label>
                                    <select name="product"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">All</option>
                                        <option value="SKA" <?php echo e(request('product') == 'SKA' ? 'selected' : ''); ?>>SKA</option>
                                        <option value="FF" <?php echo e(request('product') == 'FF' ? 'selected' : ''); ?>>FF</option>
                                        <option value="OCU" <?php echo e(request('product') == 'OCU' ? 'selected' : ''); ?>>OCU</option>
                                    </select>
                                </div>

                                <div class="w-40">
                                    <label class="text-xs text-gray-600 font-medium">Approval Status</label>
                                    <select name="approval_status"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">All</option>
                                        <option value="pending_request" <?php echo e(request('approval_status') == 'pending_request' ? 'selected' : ''); ?>>Pending Request</option>
                                        <option value="waiting_sect" <?php echo e(request('approval_status') == 'waiting_sect' ? 'selected' : ''); ?>>Waiting For Sect Head</option>
                                        <option value="waiting_dept" <?php echo e(request('approval_status') == 'waiting_dept' ? 'selected' : ''); ?>>Waiting For Dept Head</option>
                                        <option value="waiting_agm" <?php echo e(request('approval_status') == 'waiting_agm' ? 'selected' : ''); ?>>Waiting For AGM</option>
                                        <option value="waiting_ppc" <?php echo e(request('approval_status') == 'waiting_ppc' ? 'selected' : ''); ?>>Waiting For PPC Head</option>
                                        <option value="waiting_procurement" <?php echo e(request('approval_status') == 'waiting_procurement' ? 'selected' : ''); ?>>Waiting
                                            For Procurement</option>
                                        <option value="rejected_sect" <?php echo e(request('approval_status') == 'rejected_sect' ? 'selected' : ''); ?>>Rejected By Sect Head</option>
                                        <option value="rejected_dept" <?php echo e(request('approval_status') == 'rejected_dept' ? 'selected' : ''); ?>>Rejected By Dept Head</option>
                                        <option value="rejected_agm" <?php echo e(request('approval_status') == 'rejected_agm' ? 'selected' : ''); ?>>Rejected By AGM</option>
                                        <option value="rejected_ppc" <?php echo e(request('approval_status') == 'rejected_ppc' ? 'selected' : ''); ?>>Rejected By PPC Head</option>
                                        <option value="rejected_procurement" <?php echo e(request('approval_status') == 'rejected_procurement' ? 'selected' : ''); ?>>Rejected
                                            By Procurement</option>
                                        <option value="completed" <?php echo e(request('approval_status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                    </select>
                                </div>

                                <div class="flex gap-2 items-center flex-shrink-0">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md whitespace-nowrap transition-colors">Apply</button>
                                    <a href="<?php echo e(route('qc.cmr.index')); ?>"
                                        class="inline-flex items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md whitespace-nowrap transition-colors">Reset</a>
                                    <a href="<?php echo e(route('qc.cmr.create')); ?>"
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
                        <?php if($cmrs->count() > 0): ?>
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
                                    <?php $__currentLoopData = $cmrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition-colors"
                                                                    data-cmr-id="<?php echo e($cmr->id); ?>">
                                                                    <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($cmr->no_reg); ?></td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900">
                                                                        <?php echo e(optional($cmr->tgl_terbit_cmr ?? $cmr->tgl_terbit_nqr)->format('d-m-Y') ?? '-'); ?>

                                                                    </td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($cmr->nama_supplier); ?></td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($cmr->nama_part); ?></td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900"><?php echo e($cmr->nomor_part); ?></td>
                                                                    <td class="px-3 py-3 text-sm text-gray-900 text-center">
                                                                        <?php
                                                                            $product = $cmr->product ?? '-';
                                                                            // default neutral styling
                                                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                                                            if (strtoupper($product) === 'SKA') {
                                                                                $badgeClass = 'bg-amber-100 text-amber-800';
                                                                            } elseif (strtoupper($product) === 'OCU') {
                                                                                $badgeClass = 'bg-blue-100 text-blue-800';
                                                                            } elseif (strtoupper($product) === 'FF') {
                                                                                $badgeClass = 'bg-green-100 text-green-800';
                                                                            }
                                                                        ?>

                                         <span
                                                                            class="inline-flex items-center justify-center px-3 py-1 text-xs font-medium rounded-full <?php echo e($badgeClass); ?>">
                                                                            <?php echo e($product); ?>

                                                                        </span>
                                                                    </td>

                                                                    <td class="px-3 py-3 text-sm text-gray-900 status-approval-cell">
                                                                        <?php
                                                                            $sect = strtolower($cmr->secthead_status ?? 'pending');
                                                                            $dept = strtolower($cmr->depthead_status ?? 'pending');
                                                                            $agm = strtolower($cmr->agm_status ?? '');
                                                                            $ppc = strtolower($cmr->ppchead_status ?? 'pending');
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
                                                                        <div class="font-medium"><?php echo e($statusMsg); ?></div>
                                                                    </td>

                                                                    <!-- Desktop actions cell -->
                                                                    <td class="px-3 py-3 text-center text-sm hidden sm:table-cell action-buttons-container">
                                                                        <?php
                                                                            $sect = strtolower($cmr->secthead_status ?? 'pending');
                                                                            $dept = strtolower($cmr->depthead_status ?? 'pending');
                                                                            $agm = strtolower($cmr->agm_status ?? '');
                                                                            $ppc = strtolower($cmr->ppchead_status ?? 'pending');
                                                                            $proc = strtolower($cmr->procurement_status ?? '');
                                                                            $isSelesai = ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'approved' && ($proc === 'approved' || empty($proc)));
                                                                            $hasRejected = in_array('rejected', [$sect, $dept, $agm, $ppc, $proc]);
                                                                            $isCanceled = in_array('canceled', [$sect, $dept, $agm, $ppc, $proc]);
                                                                            $locked = $isSelesai || $hasRejected;
                                                                        ?>
                                                                        <div class="flex items-center justify-center gap-1">
                                                                            <?php if($isCanceled): ?>
                                                                                <div class="flex flex-col items-center">
                                                                                    <button type="button"
                                                                                        class="open-delete-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                                                                                        data-url="<?php echo e(route('qc.cmr.destroy', $cmr->id)); ?>"
                                                                                        aria-label="Hapus CMR <?php echo e($cmr->no_reg); ?>" title="Hapus">
                                                                                        <img src="<?php echo e(asset('icon/trash.ico')); ?>" alt="Delete" class="w-4 h-4" />
                                                                                    </button>
                                                                                    <span class="text-xs text-gray-500 mt-1">Delete</span>
                                                                                </div>
                                                                            <?php else: ?>
                                                                                <?php if (! ($locked)): ?>
                                                                                    <?php if(is_null($cmr->requested_at_qc)): ?>
                                                                                        <div class="flex flex-col items-center">
                                                                                            
                                                                                            <form id="request-hidden-<?php echo e($cmr->id); ?>" method="POST"
                                                                                                action="<?php echo e(route('qc.cmr.requestApproval', $cmr->id)); ?>"
                                                                                                style="display:none"><?php echo csrf_field(); ?></form>

                                                                                            <button type="button" data-hidden-form-id="request-hidden-<?php echo e($cmr->id); ?>"
                                                                                                data-url="<?php echo e(route('qc.cmr.requestApproval', $cmr->id)); ?>"
                                                                                                data-cmr-id="<?php echo e($cmr->id); ?>" data-noreg="<?php echo e($cmr->no_reg); ?>"
                                                                                                data-tgl-terbit="<?php echo e(optional($cmr->tgl_terbit_cmr ?? $cmr->tgl_terbit_nqr)->format('d/m/Y') ?? '-'); ?>"
                                                                                                data-supplier="<?php echo e($cmr->nama_supplier ?? '-'); ?>"
                                                                                                data-nama-part="<?php echo e($cmr->nama_part ?? '-'); ?>"
                                                                                                data-no-part="<?php echo e($cmr->nomor_part ?? '-'); ?>"
                                                                                                data-product="<?php echo e($cmr->product ?? '-'); ?>"
                                                                                                class="open-request-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-blue-50 transition"
                                                                                                title="Request Approval for <?php echo e($cmr->no_reg); ?>">
                                                                                                <img src="<?php echo e(asset('icon/send.ico')); ?>" alt="Request" class="w-4 h-4" />
                                                                                            </button>
                                                                                            <span class="text-xs text-gray-500 mt-1">Request</span>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>

                                                                                <?php if (! ($locked)): ?>
                                                                                    <div class="flex flex-col items-center">
                                                                                        <a href="<?php echo e(route('qc.cmr.edit', $cmr->id)); ?>"
                                                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition"
                                                                                            title="Edit CMR <?php echo e($cmr->no_reg); ?>">
                                                                                            <img src="<?php echo e(asset('icon/edit.ico')); ?>" alt="Edit" class="w-4 h-4" />
                                                                                        </a>
                                                                                        <span class="text-xs text-gray-500 mt-1">Edit</span>
                                                                                    </div>
                                                                                <?php endif; ?>

                                                                                <?php
                                                                                    $canDelete = is_null($cmr->requested_at_qc) || (!is_null($cmr->requested_at_qc) && $cmr->secthead_status === 'pending');
                                                                                ?>
                                                                                <?php if($canDelete): ?>
                                                                                    <div class="flex flex-col items-center">
                                                                                        <button type="button"
                                                                                            class="open-delete-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                                                                                            data-url="<?php echo e(route('qc.cmr.destroy', $cmr->id)); ?>"
                                                                                            aria-label="Delete CMR <?php echo e($cmr->no_reg); ?>" title="Delete">
                                                                                            <img src="<?php echo e(asset('icon/trash.ico')); ?>" alt="Delete" class="w-4 h-4" />
                                                                                        </button>
                                                                                        <span class="text-xs text-gray-500 mt-1">Delete</span>
                                                                                    </div>
                                                                                <?php endif; ?>

                                                                                <?php if(!is_null($cmr->requested_at_qc)): ?>
                                                                                    <div class="flex flex-col items-center">
                                                                                        <a href="<?php echo e(route('qc.cmr.previewFpdf', $cmr->id)); ?>" target="_blank"
                                                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition"
                                                                                            title="Preview PDF">
                                                                                            <img src="<?php echo e(asset('icon/pdf.ico')); ?>" alt="PDF" class="w-4 h-4" />
                                                                                        </a>
                                                                                        <span class="text-xs text-gray-500 mt-1">PDF</span>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-center py-12 bg-white">
                                <p class="text-gray-500 text-sm">No CMR data yet.</p>
                                <a href="<?php echo e(route('qc.cmr.create')); ?>"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm">
                                    <img src="<?php echo e(asset('icon/add.ico')); ?>" alt="add" class="w-4 h-4"
                                        style="filter: brightness(0) invert(1);" />
                                    <span>Create CMR</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6 border-t border-gray-100 pt-4">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="text-sm text-gray-600">
                                Showing <span class="font-medium"><?php echo e($cmrs->firstItem() ?? 0); ?></span> - <span
                                    class="font-medium"><?php echo e($cmrs->lastItem() ?? 0); ?></span> of <span
                                    class="font-medium"><?php echo e($cmrs->total()); ?></span> items
                            </div>

                            <nav class="flex items-center gap-3" aria-label="Pagination">
                                <?php $prev = $cmrs->previousPageUrl();
                                $next = $cmrs->nextPageUrl(); ?>

                                <a href="<?php echo e($prev ?: '#'); ?>"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($cmrs->onFirstPage() ? 'text-gray-400 border-gray-200 pointer-events-none bg-white' : 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm'); ?>"
                                    aria-disabled="<?php echo e($cmrs->onFirstPage() ? 'true' : 'false'); ?>">
                                    <span class="text-sm">
                                        < Sebelumnya</span>
                                </a>

                                <div
                                    class="hidden sm:inline-flex items-center px-3 py-2 bg-white border border-gray-100 rounded-full shadow-sm text-sm text-gray-700">
                                    Page <span class="mx-2 font-semibold"><?php echo e($cmrs->currentPage()); ?></span> of <span
                                        class="mx-2 font-medium"><?php echo e($cmrs->lastPage()); ?></span>
                                </div>

                                <a href="<?php echo e($next ?: '#'); ?>"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border <?php echo e($cmrs->hasMorePages() ? 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' : 'text-gray-400 border-gray-200 pointer-events-none bg-white'); ?>"
                                    aria-disabled="<?php echo e($cmrs->hasMorePages() ? 'false' : 'true'); ?>">
                                    <span class="text-sm">Next ></span>
                                </a>

                                <div class="sm:hidden px-3 py-1 text-xs text-gray-600">Pg. <span
                                        class="font-medium"><?php echo e($cmrs->currentPage()); ?></span>/<span
                                        class="font-medium"><?php echo e($cmrs->lastPage()); ?></span></div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Delete Confirmation</h3>
            <p class="text-sm text-gray-700 mb-6">Are you sure you want to delete this CMR? This action cannot be undone.
            </p>
            <div class="flex justify-end gap-3">
                <button id="delete-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Cancel</button>
                <form id="delete-form" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <div id="request-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-3">Confirm Approval Request</h3>

            <p class="text-sm text-gray-600 mb-4">You will send an approval request for the following CMR:</p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-3">
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <div class="font-medium text-gray-700">Reg No.:</div>
                    <div class="col-span-2 text-gray-900" id="modal-noreg">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">Issue Date:</div>
                    <div class="col-span-2 text-gray-900" id="modal-tgl-terbit">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">Supplier:</div>
                    <div class="col-span-2 text-gray-900" id="modal-supplier">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">Part Name:</div>
                    <div class="col-span-2 text-gray-900" id="modal-nama-part">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">Part No.:</div>
                    <div class="col-span-2 text-gray-900" id="modal-no-part">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">Product:</div>
                    <div class="col-span-2">
                        <span id="modal-product-badge"
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium">-</span>
                    </div>
                </div>
            </div>

            <p class="text-sm text-gray-600 mb-6">Are you sure you want to send the approval request?</p>

            <div class="flex justify-end gap-3 border-t pt-4">
                <button id="request-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition">Cancel</button>
                <button id="request-confirm-btn" type="button"
                    class="px-5 py-2 rounded bg-yellow-600 text-white hover:bg-yellow-700 font-medium transition">Send
                    Request</button>
            </div>
        </div>
    </div>

    <div id="approve-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Approve Confirmation</h3>
            <p id="approve-modal-msg" class="text-sm text-gray-700 mb-6">Are you sure you want to approve this CMR?</p>
            <div class="flex justify-end gap-3">
                <button id="approve-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Cancel</button>
                <form id="approve-form" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Approve</button>
                </form>
            </div>
        </div>
    </div>

    <div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Reject Confirmation</h3>
            <p class="text-sm text-gray-700 mb-6">Are you sure you want to reject this CMR?</p>
            <div class="flex justify-end gap-3">
                <button id="reject-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Cancel</button>
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

                // Update row after action. Accepts either a simple newStatus or a data object returned from server.
                function updateRowAfterAction(cmrId, data) {
                    var row = document.querySelector('tr[data-cmr-id="' + cmrId + '"]');
                    if (!row) return;
                    var statusCell = row.querySelector('.status-approval-cell');
                    var newStatus = null;
                    var actionsHtml = null;

                    if (data && typeof data === 'object') {
                        newStatus = data.new_status || null;
                        actionsHtml = data.actions_html || null;
                    } else {
                        newStatus = data;
                    }

                    if (statusCell && newStatus) {
                        statusCell.innerHTML = '<div class="font-medium">' + newStatus + '</div>';
                    }

                    var actionCell = row.querySelector('.action-buttons-container');
                    if (actionCell) {
                        if (actionsHtml) {
                            actionCell.innerHTML = actionsHtml;

                            attachRowActionHandlers(actionCell);
                        } else if (data && data.hide_actions) {

                            actionCell.innerHTML = '<div class="flex flex-col items-center"><a href="<?php echo e(url("qc/cmr")); ?>/' + cmrId + '/preview-fpdf" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition" title="Preview PDF"><img src="<?php echo e(asset("icon/pdf.ico")); ?>" alt="PDF" class="w-4 h-4" /></a><span class="text-xs text-gray-500 mt-1">PDF</span></div>';
                        }
                    }
                }
                function attachRowActionHandlers(actionCell) {
                    if (!actionCell) return;

                    actionCell.querySelectorAll('.open-delete-modal').forEach(btn => {

                        const newBtn = btn.cloneNode(true);
                        btn.parentNode.replaceChild(newBtn, btn);
                        newBtn.addEventListener('click', function () {
                            const url = this.getAttribute('data-url');
                            if (deleteForm) deleteForm.setAttribute('action', url);
                            if (modal) {
                                modal.classList.remove('hidden');
                                modal.classList.add('flex');
                            }
                        });
                    });

                    actionCell.querySelectorAll('.open-request-modal').forEach(btn => {
                        const newBtn = btn.cloneNode(true);
                        btn.parentNode.replaceChild(newBtn, btn);
                        newBtn.addEventListener('click', function () {
                            const url = this.getAttribute('data-url');
                            const cmrId = this.getAttribute('data-cmr-id');
                            const noreg = this.getAttribute('data-noreg');
                            const tglTerbit = this.getAttribute('data-tgl-terbit');
                            const supplier = this.getAttribute('data-supplier');
                            const namaPart = this.getAttribute('data-nama-part');
                            const noPart = this.getAttribute('data-no-part');
                            const product = this.getAttribute('data-product');

                            currentRequestUrl = url;
                            currentRequestCmrId = cmrId;

                            const elNoreg = document.getElementById('modal-noreg');
                            const elTgl = document.getElementById('modal-tgl-terbit');
                            const elSupplier = document.getElementById('modal-supplier');
                            const elNamaPart = document.getElementById('modal-nama-part');
                            const elNoPart = document.getElementById('modal-no-part');
                            const productBadge = document.getElementById('modal-product-badge');

                            if (elNoreg) elNoreg.textContent = noreg || '-';
                            if (elTgl) elTgl.textContent = tglTerbit || '-';
                            if (elSupplier) elSupplier.textContent = supplier || '-';
                            if (elNamaPart) elNamaPart.textContent = namaPart || '-';
                            if (elNoPart) elNoPart.textContent = noPart || '-';
                            if (productBadge) productBadge.textContent = product || '-';

                            if (productBadge) {
                                var p = (product || '').toUpperCase();
                                var cls = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                                if (p === 'SKA') {
                                    cls = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800';
                                } else if (p === 'OCU') {
                                    cls = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                                } else if (p === 'FF') {
                                    cls = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                                }
                                productBadge.className = cls;
                            }

                            requestModal.classList.remove('hidden');
                            requestModal.classList.add('flex');
                        });
                    });
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

                        ['#date-picker-qc', '#date-picker-qc-mobile'].forEach(function (selector) {
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
                    } else {
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
                    }
                })();

                const modal = document.getElementById('delete-modal');
                const deleteForm = document.getElementById('delete-form');
                const cancelBtn = document.getElementById('delete-cancel');

                document.querySelectorAll('.open-delete-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const url = this.getAttribute('data-url');
                        if (deleteForm) deleteForm.setAttribute('action', url);
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                });

                if (cancelBtn) {
                    cancelBtn.addEventListener('click', function () {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });
                }

                if (modal) {
                    modal.addEventListener('click', function (e) {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }
                    });
                }

                const requestModal = document.getElementById('request-modal');
                const requestCancel = document.getElementById('request-cancel');
                const requestConfirmBtn = document.getElementById('request-confirm-btn');

                let currentRequestUrl = null;
                let currentRequestCmrId = null;

                document.querySelectorAll('.open-request-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const url = this.getAttribute('data-url');
                        const cmrId = this.getAttribute('data-cmr-id');
                        const noreg = this.getAttribute('data-noreg');
                        const tglTerbit = this.getAttribute('data-tgl-terbit');
                        const supplier = this.getAttribute('data-supplier');
                        const namaPart = this.getAttribute('data-nama-part');
                        const noPart = this.getAttribute('data-no-part');
                        const product = this.getAttribute('data-product');

                        currentRequestUrl = url;
                        currentRequestCmrId = cmrId;

                        const elNoreg = document.getElementById('modal-noreg');
                        const elTgl = document.getElementById('modal-tgl-terbit');
                        const elSupplier = document.getElementById('modal-supplier');
                        const elNamaPart = document.getElementById('modal-nama-part');
                        const elNoPart = document.getElementById('modal-no-part');
                        const productBadge = document.getElementById('modal-product-badge');

                        if (elNoreg) elNoreg.textContent = noreg || '-';
                        if (elTgl) elTgl.textContent = tglTerbit || '-';
                        if (elSupplier) elSupplier.textContent = supplier || '-';
                        if (elNamaPart) elNamaPart.textContent = namaPart || '-';
                        if (elNoPart) elNoPart.textContent = noPart || '-';
                        if (productBadge) productBadge.textContent = product || '-';

                        if (productBadge) {
                            var p = (product || '').toUpperCase();
                            var cls = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                            if (p === 'SKA') {
                                cls = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800';
                            } else if (p === 'OCU') {
                                cls = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                            } else if (p === 'FF') {
                                cls = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                            }
                            productBadge.className = cls;
                        }

                        requestModal.classList.remove('hidden');
                        requestModal.classList.add('flex');
                    });
                });

                if (requestCancel) {
                    requestCancel.addEventListener('click', function () {
                        currentRequestUrl = null;
                        currentRequestCmrId = null;
                        requestModal.classList.add('hidden');
                        requestModal.classList.remove('flex');
                    });
                }

                if (requestModal) {
                    requestModal.addEventListener('click', function (e) {
                        if (e.target === requestModal) {
                            currentRequestUrl = null;
                            currentRequestCmrId = null;
                            requestModal.classList.add('hidden');
                            requestModal.classList.remove('flex');
                        }
                    });
                }

                if (requestConfirmBtn) {
                    requestConfirmBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        if (!currentRequestUrl || !currentRequestCmrId) return;

                        var formData = new FormData();
                        formData.append('_token', '<?php echo e(csrf_token()); ?>');

                        requestConfirmBtn.disabled = true;
                        requestConfirmBtn.textContent = 'Sending...';

                        fetch(currentRequestUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                            .then(function (response) { return response.json(); })
                            .then(function (data) {
                                requestConfirmBtn.disabled = false;
                                requestConfirmBtn.textContent = 'Send Request';
                                requestModal.classList.add('hidden');
                                requestModal.classList.remove('flex');

                                if (data.success) {
                                    showToast(data.message || 'Request sent successfully!', 'success');
                                    updateRowAfterAction(currentRequestCmrId, data);
                                } else {
                                    showToast(data.message || 'Failed to send request.', 'error');
                                }
                                currentRequestUrl = null;
                                currentRequestCmrId = null;
                            })
                            .catch(function (err) {
                                requestConfirmBtn.disabled = false;
                                requestConfirmBtn.textContent = 'Send Request';
                                showToast('An error occurred. Please try again.', 'error');
                                console.error(err);
                            });
                    });
                }

                const approveModal = document.getElementById('approve-modal');
                const approveForm = document.getElementById('approve-form');
                const approveCancel = document.getElementById('approve-cancel');

                document.querySelectorAll('.open-approve-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const url = this.getAttribute('data-url');
                        const noreg = this.getAttribute('data-noreg');
                        if (approveForm) approveForm.setAttribute('action', url);
                        const msg = document.getElementById('approve-modal-msg');
                        if (msg) msg.textContent = 'Are you sure you want to approve CMR ' + (noreg || '') + '?';
                        approveModal.classList.remove('hidden');
                        approveModal.classList.add('flex');
                    });
                });

                if (approveCancel) {
                    approveCancel.addEventListener('click', function () {
                        approveModal.classList.add('hidden');
                        approveModal.classList.remove('flex');
                    });
                }

                if (approveModal) {
                    approveModal.addEventListener('click', function (e) {
                        if (e.target === approveModal) {
                            approveModal.classList.add('hidden');
                            approveModal.classList.remove('flex');
                        }
                    });
                }

                const rejectModal = document.getElementById('reject-modal');
                const rejectForm = document.getElementById('reject-form');
                const rejectCancel = document.getElementById('reject-cancel');

                document.querySelectorAll('.open-reject-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const url = this.getAttribute('data-url');
                        const noreg = this.getAttribute('data-noreg');
                        if (rejectForm) rejectForm.setAttribute('action', url);
                        rejectModal.classList.remove('hidden');
                        rejectModal.classList.add('flex');
                    });
                });

                if (rejectCancel) {
                    rejectCancel.addEventListener('click', function () {
                        rejectModal.classList.add('hidden');
                        rejectModal.classList.remove('flex');
                    });
                }

                if (rejectModal) {
                    rejectModal.addEventListener('click', function (e) {
                        if (e.target === rejectModal) {
                            rejectModal.classList.add('hidden');
                            rejectModal.classList.remove('flex');
                        }
                    });
                }

            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/qc/cmr/index.blade.php ENDPATH**/ ?>