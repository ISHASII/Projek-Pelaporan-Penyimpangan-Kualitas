<?php $__env->startSection('content'); ?>
    <?php
        $ppc_prefill = null;
        try {
            if (!empty($cmr->ppchead_note)) {
                $decoded = is_string($cmr->ppchead_note) ? json_decode($cmr->ppchead_note, true) : $cmr->ppchead_note;
                if (is_array($decoded)) {
                    if (array_key_exists('ppc', $decoded) && is_array($decoded['ppc'])) {
                        $ppc_prefill = $decoded['ppc'];
                    } else {
                        $ppc_prefill = $decoded;
                    }
                }
            }
        } catch (\Throwable $e) {
            $ppc_prefill = null;
        }
        $ppc_nominal_val = old('ppc_nominal', isset($ppc_prefill['nominal']) ? $ppc_prefill['nominal'] : '');
        $ppc_currency_val = old('ppc_currency', $ppc_prefill['currency'] ?? $cmr->ppc_currency ?? '');
        $ppc_currency_symbol_val = old('ppc_currency_symbol', $ppc_prefill['currency_symbol'] ?? $cmr->ppc_currency_symbol ?? '');
        $ppc_shipping_val = old('ppc_shipping', $ppc_prefill['shipping'] ?? '');
        $ppc_shipping_detail_val = old('ppc_shipping_detail', $ppc_prefill['shipping_detail'] ?? '');
        $ppc_nominal_display = $ppc_nominal_val !== '' ? number_format((float) $ppc_nominal_val, 0, ',', '.') : '';
    ?>

    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="max-w-screen-md mx-auto px-6 py-6">
                <form action="<?php echo e($formAction ?? route('vdd.cmr.approve', $cmr->id)); ?>" method="POST" id="compensation-form">
                    <?php echo csrf_field(); ?>
                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-red-600 px-6 py-4">
                            <h1 class="text-white text-lg font-semibold">Input Pay Compensation
                                (<?php echo e($roleLabel ?? 'VDD'); ?>)</h1>
                        </div>

                        <div class="px-6 pt-6 flex items-center gap-3">
                            <a href="<?php echo e($backRoute ?? route('vdd.cmr.index')); ?>"
                                class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                                <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                                <span>Back</span>
                            </a>

                            <a href="<?php echo e($previewRoute ?? route('vdd.cmr.previewFpdf', $cmr->id)); ?>" target="_blank"
                                rel="noopener"
                                class="inline-flex items-center justify-center gap-2 text-sm px-4 py-2 rounded shadow-md bg-red-700 hover:bg-red-800 text-white">
                                <span>Download PDF</span>
                            </a>
                        </div>

                        
                        <?php if(isset($cmr)): ?>
                            <div class="px-6 pt-4">
                                <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm p-4">
                                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Detail CMR</h2>
                                    <div class="grid grid-cols-1 gap-2 text-sm text-gray-700 sm:grid-cols-2 lg:grid-cols-3">
                                        <div>
                                            <div class="text-xs text-gray-500">CMR ISSUE DATE (発行日)</div>
                                            <div class="font-medium">
                                                <?php echo e($cmr->tgl_terbit_cmr ? (is_string($cmr->tgl_terbit_cmr) ? (strtotime($cmr->tgl_terbit_cmr) ? date('d-m-Y', strtotime($cmr->tgl_terbit_cmr)) : $cmr->tgl_terbit_cmr) : $cmr->tgl_terbit_cmr->format('d-m-Y')) : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DELIVERY DATE (配達日)</div>
                                            <div class="font-medium">
                                                <?php echo e($cmr->tgl_delivery ? (is_string($cmr->tgl_delivery) ? (strtotime($cmr->tgl_delivery) ? date('d-m-Y', strtotime($cmr->tgl_delivery)) : $cmr->tgl_delivery) : $cmr->tgl_delivery->format('d-m-Y')) : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">CMR TYPE</div>
                                            <div class="font-medium"><?php echo e($cmr->cmr_type ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">B/L date (船積日)</div>
                                            <div class="font-medium">
                                                <?php echo e($cmr->bl_date ? (is_string($cmr->bl_date) ? (strtotime($cmr->bl_date) ? date('d-m-Y', strtotime($cmr->bl_date)) : $cmr->bl_date) : $cmr->bl_date->format('d-m-Y')) : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">A/R Date (到着日)</div>
                                            <div class="font-medium">
                                                <?php echo e($cmr->ar_date ? (is_string($cmr->ar_date) ? (strtotime($cmr->ar_date) ? date('d-m-Y', strtotime($cmr->ar_date)) : $cmr->ar_date) : $cmr->ar_date->format('d-m-Y')) : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Found Date (発見日)</div>
                                            <div class="font-medium">
                                                <?php echo e($cmr->found_date ? (is_string($cmr->found_date) ? (strtotime($cmr->found_date) ? date('d-m-Y', strtotime($cmr->found_date)) : $cmr->found_date) : $cmr->found_date->format('d-m-Y')) : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">SUPPLIER NAME (サプライヤ名)</div>
                                            <div class="font-medium"><?php echo e($cmr->nama_supplier ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">PART NAME (部品名)</div>
                                            <div class="font-medium"><?php echo e($cmr->nama_part ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">PO NUMBER (注文番号)</div>
                                            <div class="font-medium"><?php echo e($cmr->nomor_po ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">PART NUMBER (部品番号)</div>
                                            <div class="font-medium"><?php echo e($cmr->nomor_part ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">INVOICE NO (請求書番号)</div>
                                            <div class="font-medium"><?php echo e($cmr->invoice_no ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">ORDER NO (オーダーNo.)</div>
                                            <div class="font-medium"><?php echo e($cmr->order_no ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">PRODUCT (製品)</div>
                                            <div class="font-medium"><?php echo e($cmr->product ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">MODEL (模型)</div>
                                            <div class="font-medium"><?php echo e($cmr->model ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">CRATE NUMBER (箱番号)</div>
                                            <div class="font-medium"><?php echo e($cmr->crate_number ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">LOCATION CLAIM OCCUR (クレーム発生場所)</div>
                                            <div class="font-medium"><?php echo e($cmr->location_claim_occurrence ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DISPOSITION OF INVENTORY TYPE (在庫品処理)</div>
                                            <div class="font-medium"><?php echo e($cmr->disposition_inventory_type ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DISPOSITION INVENTORY CHOICE (在庫品処理)</div>
                                            <div class="font-medium"><?php echo e($cmr->disposition_inventory_choice ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">CLAIM OCCURRENCE FREQUENCY (請求発生頻度)</div>
                                            <div class="font-medium"><?php echo e($cmr->claim_occurrence_frequency ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DISPATCH OF DEFECTIVE PARTS (不良部品の発送)</div>
                                            <div class="font-medium"><?php echo e($cmr->dispatch_defective_parts ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">SEND REPLACEMENT (送替)</div>
                                            <div class="font-medium"><?php echo e($ppc_shipping_val ? $ppc_shipping_val : '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DISPOSITION OF DEFECT PARTS (不良部品の処分)</div>
                                            <div class="font-medium"><?php echo e($cmr->disposition_defect_parts ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">QTY ORDER (注文数量)</div>
                                            <div class="font-medium">
                                                <?php echo e($cmr->qty_order ? number_format($cmr->qty_order) : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">QTY DELIVERED (納品数量)</div>
                                            <div class="font-medium">
                                                <?php echo e($cmr->qty_deliv ? number_format($cmr->qty_deliv) : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">QTY PROBLEM (数量の問題)</div>
                                            <div class="font-medium">
                                                <?php echo e($cmr->qty_problem ? number_format($cmr->qty_problem) : '-'); ?>

                                            </div>
                                        </div>

                                        <div class="md:col-span-2 lg:col-span-3">
                                            <div class="text-xs text-gray-500">PROBLEM</div>
                                            <?php
                                                $problemText = $cmr->input_problem ?? null;
                                            ?>
                                            <div
                                                class="mt-1 text-sm text-gray-800 leading-relaxed max-h-40 overflow-auto border border-transparent">
                                                <?php echo $problemText ? nl2br(e($problemText)) : '-'; ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">IMAGE</div>
                                            <div class="mt-1">
                                                <?php if(!empty($cmr->gambar)): ?>
                                                    <?php
                                                        $imgSrc = $cmr->gambar;
                                                        if ($imgSrc && strpos($imgSrc, '/storage/') !== 0) {
                                                            $imgSrc = asset('storage/' . ltrim($imgSrc, '/'));
                                                        }
                                                    ?>
                                                    <a href="<?php echo e($imgSrc); ?>" target="_blank" class="inline-block">
                                                        <img src="<?php echo e($imgSrc); ?>" alt="cmr-image"
                                                            class="w-28 h-16 object-cover rounded border" />
                                                    </a>
                                                <?php else: ?>
                                                    <div class="text-sm text-gray-500">-</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                <select name="ppc_currency" id="ppc_currency"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Choose Currency --</option>
                                    <option value="IDR" data-symbol="Rp" <?php echo e($ppc_currency_val === 'IDR' ? 'selected' : ''); ?>>
                                        Rupiah (Rp)</option>
                                    <option value="JPY" data-symbol="¥" <?php echo e($ppc_currency_val === 'JPY' ? 'selected' : ''); ?>>
                                        Japanese Yen (¥)</option>
                                    <option value="USD" data-symbol="$" <?php echo e($ppc_currency_val === 'USD' ? 'selected' : ''); ?>>US
                                        Dollar ($)</option>
                                    <option value="MYR" data-symbol="RM" <?php echo e($ppc_currency_val === 'MYR' ? 'selected' : ''); ?>>
                                        Malaysian Ringgit (RM)</option>
                                    <option value="VND" data-symbol="₫" <?php echo e($ppc_currency_val === 'VND' ? 'selected' : ''); ?>>
                                        Vietnamese Dong (₫)</option>
                                    <option value="THB" data-symbol="฿" <?php echo e($ppc_currency_val === 'THB' ? 'selected' : ''); ?>>
                                        Thai Baht (฿)</option>
                                    <option value="KRW" data-symbol="₩" <?php echo e($ppc_currency_val === 'KRW' ? 'selected' : ''); ?>>
                                        Korean Won (₩)</option>
                                    <option value="INR" data-symbol="₹" <?php echo e($ppc_currency_val === 'INR' ? 'selected' : ''); ?>>
                                        Indian Rupee (₹)</option>
                                    <option value="CNY" data-symbol="¥" <?php echo e($ppc_currency_val === 'CNY' ? 'selected' : ''); ?>>
                                        Chinese Yuan (¥)</option>
                                    <option value="CUSTOM" <?php echo e($ppc_currency_val === 'CUSTOM' ? 'selected' : ''); ?>>Custom /
                                        Manual Input</option>
                                </select>
                                <?php $__errorArgs = ['ppc_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div id="currency_symbol_field" style="display: none;" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Currency Symbol (Manual)</label>
                                <input type="text" name="ppc_currency_symbol" id="ppc_currency_symbol"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="e.g., €, £, ₽, etc." value="<?php echo e($ppc_currency_symbol_val); ?>" maxlength="10">
                                <p class="text-xs text-gray-500 mt-1">Enter custom currency symbol (max 10 characters)</p>
                                <?php $__errorArgs = ['ppc_currency_symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pay Compensation Amount</label>
                                <input type="text" id="ppc_nominal_display"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="0" value="<?php echo e($ppc_nominal_display); ?>">
                                <input type="hidden" name="ppc_nominal" id="ppc_nominal" value="<?php echo e($ppc_nominal_val); ?>">
                                <?php $__errorArgs = ['ppc_nominal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mt-6">
                                <p id="comp_amount_error" class="text-red-500 text-sm mb-2" style="display:none;"></p>
                                <input type="hidden" name="skip_input_compensation" id="skip_input_compensation"
                                    value="1" />
                                <button type="button" id="open-approve-modal-btn"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded text-sm">Approve</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Approve dengan Recipient Selection -->
    <div id="approve-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display: none;">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Approve</h3>
            <p class="text-sm text-gray-700 mb-4">Apakah Anda yakin ingin Approve CMR <?php echo e($cmr->no_reg_cmr ?? ''); ?>?</p>

            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Pilih Procurement yang akan menerima request approval (opsional):</p>
                <div class="mb-2 flex items-center justify-between">
                    <div class="text-xs text-gray-500">Pilih penerima Procurement:</div>
                    <div class="text-xs text-gray-500"><label class="inline-flex items-center gap-2"><input type="checkbox"
                                id="approve-select-all-recipients"> Pilih semua</label></div>
                </div>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded p-2 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $procurementApprovers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="approve_recipients[]" value="<?php echo e($pa->npk); ?>"
                                class="approve-recipient-checkbox">
                            <span class="truncate"><?php echo e($pa->name); ?> <?php if($pa->email): ?> &lt;<?php echo e($pa->email); ?>&gt; <?php endif; ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-2 text-sm text-gray-500 italic">Tidak ada approver Procurement yang tersedia.</div>
                    <?php endif; ?>
                </div>
                <div class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin meneruskan ke Procurement secara
                    spesifik.</div>
            </div>

            <div class="flex justify-end gap-3">
                <button id="approve-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <button type="button" id="approve-btn"
                    class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Approve</button>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const currencySelect = document.getElementById('ppc_currency');
                const currencySymbolField = document.getElementById('currency_symbol_field');
                const currencySymbolInput = document.getElementById('ppc_currency_symbol');
                const payCompensationDisplay = document.getElementById('ppc_nominal_display');
                const payCompensationInput = document.getElementById('ppc_nominal');
                const form = document.getElementById('compensation-form');
                const openModalBtn = document.getElementById('open-approve-modal-btn');
                const approveModal = document.getElementById('approve-modal');
                const cancelBtn = document.getElementById('approve-cancel');
                const approveBtn = document.getElementById('approve-btn');
                const skipInput = document.getElementById('skip_input_compensation');
                const selectAllCheckbox = document.getElementById('approve-select-all-recipients');
                const recipientCheckboxes = document.querySelectorAll('.approve-recipient-checkbox');
                const compAmountError = document.getElementById('comp_amount_error');

                function formatRupiah(angka) {
                    if (!angka) return '';
                    let number_string = String(angka).replace(/[^0-9,]/g, '');
                    let split = number_string.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/g);
                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }
                    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                    return rupiah;
                }

                payCompensationDisplay && payCompensationDisplay.addEventListener('input', function (e) {
                    let raw = this.value.replace(/\./g, '').replace(/,/g, '.').replace(/[^0-9\.]/g, '');
                    payCompensationInput.value = raw ? parseFloat(raw) : '';
                    this.value = formatRupiah(this.value);
                });

                currencySelect && currencySelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    if (this.value === 'CUSTOM') {
                        currencySymbolField.style.display = 'block';
                        currencySymbolInput.setAttribute('required', 'required');
                    } else {
                        currencySymbolField.style.display = 'none';
                        currencySymbolInput.removeAttribute('required');
                        currencySymbolInput.value = '';
                        const symbol = selectedOption.getAttribute('data-symbol');
                        if (symbol) currencySymbolInput.value = symbol;
                    }
                });

                // show custom symbol if already selected
                if (currencySelect && currencySelect.value === 'CUSTOM') {
                    currencySymbolField.style.display = 'block';
                    currencySymbolInput.setAttribute('required', 'required');
                }

                // Modal handling
                openModalBtn && openModalBtn.addEventListener('click', function () {
                    compAmountError.style.display = 'none';
                    compAmountError.textContent = '';
                    approveModal.style.display = 'flex';
                });

                cancelBtn && cancelBtn.addEventListener('click', function () {
                    approveModal.style.display = 'none';
                });

                approveModal && approveModal.addEventListener('click', function (e) {
                    if (e.target === approveModal) approveModal.style.display = 'none';
                });

                selectAllCheckbox && selectAllCheckbox.addEventListener('change', function () {
                    recipientCheckboxes.forEach(cb => cb.checked = this.checked);
                });

                recipientCheckboxes.forEach(cb => {
                    cb.addEventListener('change', function () {
                        const allChecked = Array.from(recipientCheckboxes).every(c => c.checked);
                        if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
                    });
                });

                // Approve button in modal
                approveBtn && approveBtn.addEventListener('click', async function (e) {
                    e.preventDefault();

                    const currencyVal = currencySelect ? currencySelect.value : '';
                    const amountVal = payCompensationInput ? payCompensationInput.value : '';

                    // If no amount & currency selected, allow skipping
                    if (!amountVal && !currencyVal) {
                        skipInput && (skipInput.value = 1);
                    } else {
                        skipInput && (skipInput.value = 0);
                        if (amountVal && (isNaN(amountVal) || Number(amountVal) <= 0)) {
                            alert('Silakan masukkan Nilai Pay Compensation yang valid.');
                            payCompensationDisplay && payCompensationDisplay.focus();
                            approveModal.style.display = 'none';
                            return;
                        }
                    }

                    approveBtn.disabled = true;
                    approveBtn.innerText = 'Memproses...';

                    // Collect selected recipients
                    const selectedRecipients = [];
                    document.querySelectorAll('.approve-recipient-checkbox:checked').forEach(cb => {
                        selectedRecipients.push(cb.value);
                    });

                    // Add hidden inputs for recipients to the form
                    selectedRecipients.forEach(npk => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'approve_recipients[]';
                        input.value = npk;
                        form.appendChild(input);
                    });

                    // Submit the form
                    form.submit();
                });
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/vdd/cmr/input_compensation.blade.php ENDPATH**/ ?>