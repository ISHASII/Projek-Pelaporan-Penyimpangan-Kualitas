<?php $__env->startSection('content'); ?>
    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="max-w-screen-lg mx-auto px-6 py-6">
                <form action="<?php echo e(route('procurement.nqr.storePayCompensation', $nqr->id ?? 0)); ?>" method="POST"
                    id="procurement-paycomp-form">
                    <?php echo csrf_field(); ?>

                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-red-600 px-6 py-4">
                            <h1 class="text-white text-lg font-semibold">Input Pay Compensation (Procurement)</h1>
                        </div>

                        <div class="px-6 pt-6">
                            <div class="flex items-center gap-3">
                                <a href="<?php echo e(route('procurement.nqr.index')); ?>"
                                    class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                                    <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                                    <span>Kembali</span>
                                </a>

                                <?php if(!empty($nqr->id)): ?>
                                    <a href="<?php echo e(route('procurement.nqr.previewFpdf', $nqr->id)); ?>" target="_blank"
                                        rel="noopener"
                                        class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-red-600 hover:bg-red-700 text-white">
                                        <span>Download PDF</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="px-6 pt-4">
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">NQR Detail</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
                                    <div>
                                        <div class="text-xs text-gray-500">No. Registrasi</div>
                                        <div class="font-medium text-gray-900"><?php echo e($nqr->no_reg_nqr ?? '-'); ?></div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Nama Supplier</div>
                                        <div class="font-medium text-gray-900"><?php echo e($nqr->nama_supplier ?? '-'); ?></div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Nama Part</div>
                                        <div class="font-medium text-gray-900"><?php echo e($nqr->nama_part ?? '-'); ?></div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Nomor PO</div>
                                        <div class="font-medium text-gray-900"><?php echo e($nqr->nomor_po ?? '-'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Uang <span
                                        class="text-red-500">*</span></label>
                                <select name="pay_compensation_currency" id="pay_compensation_currency"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    required>
                                    <option value="">-- Pilih Mata Uang --</option>
                                    <option value="IDR" data-symbol="Rp">Rupiah (Rp)</option>
                                    <option value="JPY" data-symbol="¥">Japanese Yen (¥)</option>
                                    <option value="USD" data-symbol="$">US Dollar ($)</option>
                                    <option value="MYR" data-symbol="RM">Malaysian Ringgit (RM)</option>
                                    <option value="VND" data-symbol="₫">Vietnamese Dong (₫)</option>
                                    <option value="THB" data-symbol="฿">Thai Baht (฿)</option>
                                    <option value="KRW" data-symbol="₩">Korean Won (₩)</option>
                                    <option value="INR" data-symbol="₹">Indian Rupee (₹)</option>
                                    <option value="CNY" data-symbol="¥">Chinese Yuan (¥)</option>
                                    <option value="CUSTOM">Custom / Manual Input</option>
                                </select>
                                <?php $__errorArgs = ['pay_compensation_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div id="currency_symbol_field" style="display: none;" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Simbol Mata Uang (Manual) <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="pay_compensation_currency_symbol"
                                    id="pay_compensation_currency_symbol"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="cth: €, £, ₽" maxlength="10" />
                                <p class="text-xs text-gray-500 mt-1">Masukkan simbol mata uang khusus (maks 10 karakter)
                                </p>
                            </div>

                            <div id="pay_compensation_field" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Pay Compensation <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="pay_compensation_display" placeholder="0"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    required>
                                <input type="hidden" name="pay_compensation_value" id="pay_compensation_value">
                                <?php $__errorArgs = ['pay_compensation_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mt-6">
                                <button type="submit" id="approve-btn"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded text-sm">Approve</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const currencySelect = document.getElementById('pay_compensation_currency');
            const currencySymbolField = document.getElementById('currency_symbol_field');
            const currencySymbolInput = document.getElementById('pay_compensation_currency_symbol');
            const payCompensationDisplay = document.getElementById('pay_compensation_display');
            const payCompensationInput = document.getElementById('pay_compensation_value');
            const form = document.getElementById('procurement-paycomp-form');
            const approveBtn = document.getElementById('approve-btn');

            function formatNumber(input) {
                if (!input) return '';
                let number_string = String(input).replace(/[^0-9,\.]/g, '');
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
                this.value = formatNumber(this.value);
            });

            currencySelect && currencySelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                if (this.value === 'CUSTOM') {
                    currencySymbolField.style.display = 'block';
                    currencySymbolInput.setAttribute('required', 'required');
                } else {
                    currencySymbolField.style.display = 'none';
                    currencySymbolInput.removeAttribute('required');
                    currencySymbolInput.value = '';
                    const sym = selected.getAttribute('data-symbol');
                    if (sym) currencySymbolInput.value = sym;
                }
            });

            if (form) {
                form.addEventListener('submit', function (e) {
                    const currencyVal = currencySelect ? currencySelect.value : '';
                    const amountVal = payCompensationInput ? payCompensationInput.value : '';
                    if (!currencyVal) {
                        e.preventDefault();
                        alert('Silakan pilih Mata Uang untuk Pay Compensation.');
                        currencySelect && currencySelect.focus();
                        return false;
                    }
                    if (!amountVal || isNaN(amountVal) || Number(amountVal) <= 0) {
                        e.preventDefault();
                        alert('Silakan masukkan Nilai Pay Compensation yang valid.');
                        payCompensationDisplay && payCompensationDisplay.focus();
                        return false;
                    }
                    // let server handle storage and approval; show loading state
                    if (approveBtn) {
                        approveBtn.disabled = true;
                        approveBtn.innerText = 'Memproses...';
                    }
                    return true;
                });
            }

            // initialize if value prefilled
            if (currencySelect && currencySelect.value === 'CUSTOM') {
                currencySymbolField.style.display = 'block';
                currencySymbolInput.setAttribute('required', 'required');
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/procurement/nqr/input_pay_compensation.blade.php ENDPATH**/ ?>