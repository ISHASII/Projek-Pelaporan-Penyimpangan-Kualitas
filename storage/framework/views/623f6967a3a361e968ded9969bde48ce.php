<?php $__env->startSection('content'); ?>
    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="max-w-screen-xl mx-auto px-6 py-6">
                <form action="<?php echo e(route('ppchead.nqr.update', $nqr)); ?>" method="POST" id="nqr-ppc-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-red-600 px-6 py-4">
                            <h1 class="text-white text-lg font-semibold">Lengkapi Input PPC</h1>
                        </div>

                        <div class="px-6 pt-6">
                            <div class="flex items-center gap-3">
                                <a href="<?php echo e(route('ppchead.nqr.index')); ?>"
                                    class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                                    <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                                    <span>Kembali</span>
                                </a>

                                <?php if(!empty($nqr->id)): ?>
                                    <a href="<?php echo e(route('ppchead.nqr.previewFpdf', $nqr->id)); ?>" target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-red-600 hover:bg-red-700 text-white">
                                        <span>Download PDF</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="px-6 pt-4">
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail NQR</h3>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm text-gray-700">
                                    <div class="space-y-4">
                                        <div>
                                            <div class="text-xs text-gray-500">Tgl Terbit NQR</div>
                                            <div class="font-medium text-gray-900">
                                                <?php echo e($nqr->tgl_terbit_nqr ? $nqr->tgl_terbit_nqr->format('d-m-Y') : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Tgl Delivery</div>
                                            <div class="font-medium text-gray-900">
                                                <?php echo e($nqr->tgl_delivery ? $nqr->tgl_delivery->format('d-m-Y') : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Nomor PO</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->nomor_po ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Status NQR</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->status_nqr ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Claim occurance freq.</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->claim_occurence_freq ?? '-'); ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4 text-center">
                                        <div>
                                            <div class="text-xs text-gray-500">Nama Supplier</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->nama_supplier ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Nama Part</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->nama_part ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Nomor Part</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->nomor_part ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Disposition Inventory</div>
                                            <?php
                                                $dispLoc = $nqr->disposition_inventory_location ?? '';
                                                $dispAct = $nqr->disposition_inventory_action ?? '';
                                                $dispText = trim($dispLoc . ($dispLoc && $dispAct ? ' / ' : '') . $dispAct);
                                            ?>
                                            <div class="font-medium text-gray-900"><?php echo e($dispText !== '' ? $dispText : '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500 mt-2">Gambar</div>
                                            <div class="mt-2">
                                                <?php if(!empty($nqr->gambar)): ?>
                                                    <a href="<?php echo e(asset('storage/' . $nqr->gambar)); ?>" target="_blank"
                                                        title="Lihat gambar">
                                                        <img src="<?php echo e(asset('storage/' . $nqr->gambar)); ?>" alt="gambar-nqr"
                                                            class="mx-auto w-28 h-20 object-cover rounded border border-gray-200 shadow-sm" />
                                                    </a>
                                                    <?php if(!empty($nqr->detail_gambar)): ?>
                                                        <div class="text-xs text-gray-500 mt-1"><?php echo e($nqr->detail_gambar); ?></div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <div class="text-xs text-gray-400">-</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <div class="text-xs text-gray-500">Status NQR (Approval)</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->status_approval ?? '-'); ?></div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Location Claim Occur</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->location_claim_occur ?? '-'); ?>

                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Disposition Defect Part</div>
                                            <div class="font-medium text-gray-900">
                                                <?php echo e($nqr->disposition_defect_part ?? '-'); ?>

                                            </div>
                                        </div>

                                        <div class="pt-2">
                                            <div class="text-xs text-gray-500">Problem / Deskripsi</div>
                                            <?php
                                                $problemText = $nqr->detail_gambar ?? $nqr->note ?? $nqr->problem ?? null;
                                            ?>
                                            <div
                                                class="mt-1 text-sm text-gray-800 leading-relaxed max-h-24 overflow-auto border border-transparent">
                                                <?php echo $problemText ? nl2br(e($problemText)) : '-'; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 border-t pt-4">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                                        <div>
                                            <div class="text-xs text-gray-500">Invoice</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->invoice ?? '-'); ?></div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Total Delivered</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->total_del ?? '-'); ?></div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Total Claim</div>
                                            <div class="font-medium text-gray-900"><?php echo e($nqr->total_claim ?? '-'); ?></div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">-</div>
                                            <div class="font-medium text-gray-900">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Input PPC - Style Simple -->
                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Disposition Claim <span class="text-red-500">*</span>
                                </label>
                                <select name="disposition_claim" id="disposition_claim" required
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Send the Replacement" selected>Send the Replacement</option>
                                </select>
                                <?php $__errorArgs = ['disposition_claim'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Send Replacement Field (Conditional) -->
                            <div id="send_replacement_field" style="display: none;" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Perlakuan Terhadap Claim <span class="text-red-500">*</span>
                                </label>
                                <select name="send_replacement_method" id="send_replacement_method"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="By Air" <?php echo e(old('send_replacement_method', $nqr->send_replacement_method) == 'By Air' ? 'selected' : ''); ?>>By Air</option>
                                    <option value="By Sea" <?php echo e(old('send_replacement_method', $nqr->send_replacement_method) == 'By Sea' ? 'selected' : ''); ?>>By Sea</option>
                                </select>
                                <?php $__errorArgs = ['send_replacement_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mt-6">
                                <button type="button" id="open-approve-modal-btn"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded text-sm">
                                    Approve
                                </button>
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
            <p class="text-sm text-gray-700 mb-4">Apakah Anda yakin ingin Approve NQR <?php echo e($nqr->no_reg_nqr ?? ''); ?>?</p>

            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Pilih VDD yang akan menerima request approval (opsional):</p>
                <div class="mb-2 flex items-center justify-between">
                    <div class="text-xs text-gray-500">Pilih penerima VDD:</div>
                    <div class="text-xs text-gray-500"><label class="inline-flex items-center gap-2"><input type="checkbox"
                                id="approve-select-all-recipients"> Pilih semua</label></div>
                </div>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded p-2 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $vddApprovers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $va): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="approve_recipients[]" value="<?php echo e($va->npk); ?>"
                                class="approve-recipient-checkbox">
                            <span class="truncate"><?php echo e($va->name); ?> <?php if($va->email): ?> &lt;<?php echo e($va->email); ?>&gt; <?php endif; ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-2 text-sm text-gray-500 italic">Tidak ada approver VDD yang tersedia.</div>
                    <?php endif; ?>
                </div>
                <div class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin meneruskan ke VDD secara spesifik.</div>
            </div>

            <div class="flex justify-end gap-3">
                <button id="approve-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <button type="button" id="approve-btn"
                    class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Approve</button>
            </div>
        </div>
    </div>
    <script>
        // Simplify PPC form: disposition now only supports Send the Replacement.
        document.addEventListener('DOMContentLoaded', function () {
            const sendReplacementField = document.getElementById('send_replacement_field');
            const sendReplacementMethod = document.getElementById('send_replacement_method');
            if (sendReplacementField) {
                sendReplacementField.style.display = 'block';
                sendReplacementMethod && sendReplacementMethod.setAttribute('required', 'required');
            }

            // Modal handling
            const openModalBtn = document.getElementById('open-approve-modal-btn');
            const approveModal = document.getElementById('approve-modal');
            const cancelBtn = document.getElementById('approve-cancel');
            const selectAllCheckbox = document.getElementById('approve-select-all-recipients');
            const recipientCheckboxes = document.querySelectorAll('.approve-recipient-checkbox');

            openModalBtn && openModalBtn.addEventListener('click', function () {
                // Validate form first
                const form = document.getElementById('nqr-ppc-form');
                const fields = form.querySelectorAll('input[required], select[required]');
                let allValid = true;
                fields.forEach(field => {
                    if (field.tagName === 'SELECT') {
                        if (field.value === '') allValid = false;
                    } else {
                        if (field.value.trim() === '') allValid = false;
                    }
                });
                if (!allValid) {
                    alert('Mohon lengkapi semua field yang wajib diisi.');
                    return;
                }
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
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                const form = document.getElementById('nqr-ppc-form');
                const btn = document.getElementById('approve-btn');
                const approveModal = document.getElementById('approve-modal');
                if (!form || !btn) return;

                function validateField(field) {
                    let isValid = true;
                    const errorDiv = document.querySelector(`.error-message[data-field="${field.name}"]`);
                    field.classList.remove('border-red-500');
                    if (errorDiv) errorDiv.classList.add('hidden');

                    if (field.hasAttribute('required')) {
                        if (field.tagName === 'SELECT') {
                            isValid = field.value !== '';
                        } else {
                            isValid = field.value.trim() !== '';
                        }

                        if (!isValid) {
                            field.classList.add('border-red-500');
                            if (errorDiv) errorDiv.classList.remove('hidden');
                        }
                    }

                    return isValid;
                }

                function validateAllFields() {
                    let allValid = true;
                    const fields = form.querySelectorAll('input[required], select[required]');
                    fields.forEach(field => { if (!validateField(field)) allValid = false; });
                    return allValid;
                }

                const fields = form.querySelectorAll('input, select');
                fields.forEach(field => {
                    field.addEventListener('blur', function () { validateField(this); });
                    field.addEventListener('change', function () { validateField(this); });
                    field.addEventListener('focus', function () { this.classList.remove('border-red-500'); const errorDiv = document.querySelector(`.error-message[data-field="${this.name}"]`); if (errorDiv) errorDiv.classList.add('hidden'); });
                });

                async function postFormData(url, data) {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;
                    const headers = { 'X-Requested-With': 'XMLHttpRequest' };
                    if (token) headers['X-CSRF-TOKEN'] = token;

                    const res = await fetch(url, {
                        method: 'POST',
                        headers,
                        body: data,
                        credentials: 'same-origin'
                    });
                    return res;
                }

                btn.addEventListener('click', async function (e) {
                    e.preventDefault();

                    btn.disabled = true;
                    const prev = btn.innerHTML;
                    btn.innerHTML = 'Memproses...';
                    btn.classList.add('opacity-50', 'cursor-not-allowed');

                    // Collect selected recipients
                    const selectedRecipients = [];
                    document.querySelectorAll('.approve-recipient-checkbox:checked').forEach(cb => {
                        selectedRecipients.push(cb.value);
                    });

                    try {
                        // First: save PPC inputs via the existing update route (form action)
                        const storeUrl = form.getAttribute('action');
                        const formData = new FormData(form);
                        const storeRes = await postFormData(storeUrl, formData);

                        if (!storeRes.ok) {
                            let msg = 'Gagal menyimpan input PPC.';
                            try { const j = await storeRes.json(); if (j.message) msg = j.message; } catch (e) { }
                            alert(msg);
                            btn.disabled = false; btn.innerHTML = prev; btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            return;
                        }

                        // Second: call approve endpoint with recipients
                        const approveUrl = '<?php echo e(route('ppchead.nqr.approve', $nqr->id ?? 0)); ?>';
                        const approveRes = await fetch(approveUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value,
                                'Content-Type': 'application/json'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ approve_recipients: selectedRecipients })
                        });

                        if (!approveRes.ok) {
                            let msg = 'Gagal melakukan approve.';
                            try { const j = await approveRes.json(); if (j.message) msg = j.message; } catch (e) { }
                            alert(msg);
                            btn.disabled = false; btn.innerHTML = prev; btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            return;
                        }

                        const js = await approveRes.json();
                        if (js.success) {
                            approveModal && (approveModal.style.display = 'none');
                            window.location.href = '<?php echo e(route('ppchead.nqr.index')); ?>';
                            return;
                        } else {
                            alert(js.message || 'Approve gagal');
                        }

                    } catch (err) {
                        console && console.error('Approve flow error', err);
                        alert('Terjadi kesalahan saat memproses. Periksa konsol.');
                    } finally {
                        btn.disabled = false; btn.innerHTML = prev; btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });

            } catch (err) {
                console && console.error('Approve script error', err);
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/ppchead/nqr/ppc_form.blade.php ENDPATH**/ ?>