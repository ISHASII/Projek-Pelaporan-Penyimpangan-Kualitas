<?php $__env->startSection('content'); ?>
    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="px-6 py-4 rounded-none">
                <div class="max-w-screen-xl mx-auto flex items-center">
                    <h1 class="text-red-600 text-lg font-semibold">Create NQR</h1>
                </div>
            </div>

            <div class="max-w-screen-xl mx-auto px-6 py-6">
                <form action="<?php echo e(route('qc.nqr.store')); ?>" method="POST" enctype="multipart/form-data" id="nqr-form">
                    <?php echo csrf_field(); ?>

                    <div class="flex items-center mb-8">
                        <a href="<?php echo e(route('qc.nqr.index')); ?>"
                            class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                            <img src="<?php echo e(asset('icon/back.ico')); ?>" alt="back" class="w-4 h-4" />
                            <span>Kembali</span>
                        </a>
                    </div>

                    <div class="form-compact">
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <!-- Tanggal Terbit NQR -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TGL TERBIT NQR <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="tgl_terbit_nqr" id="tgl_terbit_nqr"
                                    value="<?php echo e(old('tgl_terbit_nqr')); ?>" required>
                                <input type="text" id="tgl_terbit_nqr_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('tgl_terbit_nqr') ? date('d-m-Y', strtotime(old('tgl_terbit_nqr'))) : ''); ?>">
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_terbit_nqr">TGL
                                    TERBIT NQR wajib diisi</div>
                                <?php $__errorArgs = ['tgl_terbit_nqr'];
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

                            <!-- Tanggal Delivery -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TGL DELIVERY <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="tgl_delivery" id="tgl_delivery" value="<?php echo e(old('tgl_delivery')); ?>"
                                    required>
                                <input type="text" id="tgl_delivery_display" inputmode="numeric" pattern="\d{2}-\d{2}-\d{4}"
                                    placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('tgl_delivery') ? date('d-m-Y', strtotime(old('tgl_delivery'))) : ''); ?>">
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_delivery">TGL
                                    DELIVERY wajib diisi</div>
                                <?php $__errorArgs = ['tgl_delivery'];
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
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <!-- Nama Supplier (searchable dropdown) -->
                            <div id="supplier_dropdown" class="relative">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    NAMA SUPPLIER <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="nama_supplier" id="nama_supplier"
                                    value="<?php echo e(old('nama_supplier')); ?>" required>
                                <button type="button" id="nama_supplier_btn"
                                    class="w-full text-left border rounded-lg px-3 py-2 text-sm flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <span
                                        id="nama_supplier_btn_text"><?php echo e(old('nama_supplier') ?: 'Pilih atau ketik nama supplier'); ?></span>
                                    <img src="<?php echo e(asset('icon/dropdown.ico')); ?>" alt="dropdown" class="w-4 h-4">
                                </button>
                                <div id="nama_supplier_panel"
                                    class="absolute z-50 mt-1 w-full bg-white border rounded shadow-lg hidden">
                                    <div class="px-3 py-2">
                                        <input id="nama_supplier_panel_search" type="text" placeholder="Cari supplier..."
                                            class="w-full border rounded px-2 py-1 text-sm" />
                                    </div>
                                    <div id="nama_supplier_list" class="max-h-48 overflow-auto"></div>
                                </div>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nama_supplier">NAMA
                                    SUPPLIER wajib diisi</div>
                                <?php $__errorArgs = ['nama_supplier'];
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

                            <div id="part_dropdown" class="relative">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    NOMOR PART <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="nomor_part" id="nomor_part" value="<?php echo e(old('nomor_part')); ?>"
                                    required>
                                <input type="hidden" name="nama_part" id="nama_part" value="<?php echo e(old('nama_part')); ?>">
                                <button type="button" id="nomor_part_btn"
                                    class="w-full text-left border rounded-lg px-3 py-2 text-sm flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <span
                                        id="nomor_part_btn_text"><?php echo e(old('nomor_part') ? old('nomor_part') . ' — ' . old('nama_part') : 'Pilih atau cari nomor part'); ?></span>
                                    <img src="<?php echo e(asset('icon/dropdown.ico')); ?>" alt="dropdown" class="w-4 h-4">
                                </button>
                                <div id="nomor_part_panel"
                                    class="absolute z-50 mt-1 w-full bg-white border rounded shadow-lg hidden">
                                    <div class="px-3 py-2">
                                        <input id="nomor_part_panel_search" type="text"
                                            placeholder="Cari nomor part atau nama..."
                                            class="w-full border rounded px-2 py-1 text-sm" />
                                    </div>
                                    <div id="nomor_part_list" class="max-h-48 overflow-auto"></div>
                                </div>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nomor_part">NOMOR
                                    PART wajib diisi</div>
                                <?php $__errorArgs = ['nomor_part'];
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
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <!-- Nomor PO -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    NOMOR PO <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nomor_po" value="<?php echo e(old('nomor_po')); ?>" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="NOMOR PO">
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nomor_po">NOMOR PO
                                    wajib diisi</div>
                                <p class="text-xs text-gray-500 mt-1">ORDER akan otomatis mengikuti Nomor PO</p>
                                <?php $__errorArgs = ['nomor_po'];
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

                            <!-- (part handled by searchable dropdown above) -->
                            <div aria-hidden="true"></div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <!-- Status NQR -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    STATUS NQR <span class="text-red-500">*</span>
                                </label>
                                <select name="status_nqr" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Claim" <?php echo e(old('status_nqr') == 'Claim' ? 'selected' : ''); ?>>Claim</option>
                                    <option value="Complaint (Informasi)" <?php echo e(old('status_nqr') == 'Complaint (Informasi)' ? 'selected' : ''); ?>>Complaint (Informasi)</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="status_nqr">Status
                                    NQR wajib dipilih</div>
                                <?php $__errorArgs = ['status_nqr'];
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

                            <!-- Location Claim Occur -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    LOCATION CLAIM OCCUR <span class="text-red-500">*</span>
                                </label>
                                <select name="location_claim_occur" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih Location --</option>
                                    <option value="Receiving Insp" <?php echo e(old('location_claim_occur') == 'Receiving Insp' ? 'selected' : ''); ?>>Receiving Insp</option>
                                    <option value="In-Process" <?php echo e(old('location_claim_occur') == 'In-Process' ? 'selected' : ''); ?>>In-Process</option>
                                    <option value="Customer" <?php echo e(old('location_claim_occur') == 'Customer' ? 'selected' : ''); ?>>Customer</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="location_claim_occur">Location Claim Occur wajib dipilih</div>
                                <?php $__errorArgs = ['location_claim_occur'];
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
                        </div>

                        <!-- Disposition of Inventory -->
                        <div class="border-t pt-4 mb-6">
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <!-- Location -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        DISPOSTION OF INVENTORY <span class="text-red-500">*</span>
                                    </label>
                                    <select name="disposition_inventory_location" id="disposition_inventory_location"
                                        required
                                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="">-- Pilih Location --</option>
                                        <option value="At Customer" <?php echo e(old('disposition_inventory_location') == 'At Customer' ? 'selected' : ''); ?>>At Customer</option>
                                        <option value="At PT.KYBI" <?php echo e(old('disposition_inventory_location') == 'At PT.KYBI' ? 'selected' : ''); ?>>At PT.KYBI</option>
                                    </select>
                                    <div class="error-message text-red-500 text-xs mt-1 hidden"
                                        data-field="disposition_inventory_location">Disposition of Inventory wajib dipilih
                                    </div>
                                    <?php $__errorArgs = ['disposition_inventory_location'];
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

                                <!-- Action (Conditional) -->
                                <div id="action_field" style="display: none;">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        Action <span class="text-red-500">*</span>
                                    </label>
                                    <select name="disposition_inventory_action" id="disposition_inventory_action" required
                                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="">-- Pilih Action --</option>
                                    </select>
                                    <div class="error-message text-red-500 text-xs mt-1 hidden"
                                        data-field="disposition_inventory_action">Action wajib dipilih</div>
                                    <?php $__errorArgs = ['disposition_inventory_action'];
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
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <!-- Claim Occurence Freq -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    CLAIM OCCURENCE FREQ <span class="text-red-500">*</span>
                                </label>
                                <select name="claim_occurence_freq" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih Frequency --</option>
                                    <option value="First Time" <?php echo e(old('claim_occurence_freq') == 'First Time' ? 'selected' : ''); ?>>First Time</option>
                                    <option value="Reoccurred/Routine" <?php echo e(old('claim_occurence_freq') == 'Reoccurred/Routine' ? 'selected' : ''); ?>>Reoccurred/Routine</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="claim_occurence_freq">Claim Occurence Freq wajib dipilih</div>
                                <?php $__errorArgs = ['claim_occurence_freq'];
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

                            <!-- Disposition Defect Part -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    DISPOSITION OF DEFECT PART <span class="text-red-500">*</span>
                                </label>
                                <select name="disposition_defect_part" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih Disposition --</option>
                                    <option value="Keep to Use" <?php echo e(old('disposition_defect_part') == 'Keep to Use' ? 'selected' : ''); ?>>Keep to Use</option>
                                    <option value="Return to Supplier" <?php echo e(old('disposition_defect_part') == 'Return to Supplier' ? 'selected' : ''); ?>>Return to Supplier</option>
                                    <option value="Scrapped at PT.KYBI" <?php echo e(old('disposition_defect_part') == 'Scrapped at PT.KYBI' ? 'selected' : ''); ?>>Scrapped at PT.KYBI</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="disposition_defect_part">Disposition of Defect Part wajib dipilih</div>
                                <?php $__errorArgs = ['disposition_defect_part'];
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
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <!-- Invoice -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    INVOICE <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="invoice" value="<?php echo e(old('invoice')); ?>" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="INVOICE">
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="invoice">INVOICE
                                    wajib diisi</div>
                                <?php $__errorArgs = ['invoice'];
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

                            <!-- Total Del -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TOTAL DEL <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="total_del" value="<?php echo e(old('total_del')); ?>" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="TOTAL DEL">
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_del">TOTAL DEL
                                    wajib diisi</div>
                                <?php $__errorArgs = ['total_del'];
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
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                TOTAL CLAIM <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="total_claim" value="<?php echo e(old('total_claim')); ?>" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="TOTAL CLAIM">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_claim">TOTAL CLAIM
                                wajib diisi</div>
                            <?php $__errorArgs = ['total_claim'];
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

                        <!-- Upload Gambar -->
                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                INPUT GAMBAR <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="gambar" id="gambar-input" accept="image/*" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <div id="gambar-preview" class="mt-3 hidden">
                                <div class="relative inline-block">
                                    <img id="gambar-preview-img" src="#" alt="preview"
                                        class="w-32 h-24 object-cover border-2 border-gray-300 rounded-lg shadow-sm">
                                    <button type="button" id="remove-main-image"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 focus:outline-none">
                                        ×
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600 mt-2">Preview gambar utama</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, GIF. Max: 2MB</p>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="gambar">Gambar wajib
                                diupload</div>
                            <?php $__errorArgs = ['gambar'];
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

                        <!-- Detail Gambar -->
                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                INPUT PROBLEM
                            </label>
                            <textarea id="detail_gambar" name="detail_gambar" rows="3" maxlength="265"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Keterangan detail gambar (opsional)"><?php echo e(old('detail_gambar')); ?></textarea>
                            <div class="text-xs text-gray-500 mt-1 text-right">
                                <span id="detail_gambar_count"><?php echo e(strlen(old('detail_gambar'))); ?></span>/265 karakter
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var textarea = document.getElementById('detail_gambar');
                                    var counter = document.getElementById('detail_gambar_count');
                                    textarea.addEventListener('input', function () {
                                        counter.textContent = textarea.value.length;
                                    });
                                });
                            </script>
                            <?php $__errorArgs = ['detail_gambar'];
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
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-3 justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm">
                            <img src="<?php echo e(asset('icon/add.ico')); ?>" alt="save" class="w-4 h-4"
                                style="filter: brightness(0) invert(1);" />
                            <span>Create NQR</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Flatpickr for date fields (calendar picker + manual input)
            (function attachCalendar() {
                const fields = [
                    { hidden: 'tgl_terbit_nqr', display: 'tgl_terbit_nqr_display' },
                    { hidden: 'tgl_delivery', display: 'tgl_delivery_display' }
                ];

                function bind(fp) {
                    fields.forEach(({ hidden, display }) => {
                        const h = document.getElementById(hidden);
                        const d = document.getElementById(display);
                        if (!h || !d) return;

                        fp(d, {
                            dateFormat: 'd-m-Y',
                            allowInput: true,
                            defaultDate: h.value ? h.value : undefined,
                                onOpen: function(selectedDates, dateStr, instance) { if (!instance.input.value) instance.jumpToDate(new Date()); },
                            onChange: function (selectedDates, dateStr) {
                                // dateStr is dd-mm-yyyy
                                const parts = dateStr.split('-');
                                if (parts.length === 3) {
                                    h.value = `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert to yyyy-mm-dd
                                }
                            }
                        });
                    });
                }

                // Check if flatpickr is already loaded
                if (window.flatpickr) {
                    bind(window.flatpickr);
                    return;
                }

                // Load flatpickr from local assets (offline)
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.css")); ?>';
                document.head.appendChild(link);

                const script = document.createElement('script');
                script.src = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.js")); ?>';
                script.onload = function () { bind(window.flatpickr); };
                document.body.appendChild(script);
            })();

            // Auto-format date input (dd-mm-yyyy) when typing manually
            function setupDateInputFormatter(displayId, hiddenId) {
                const displayInput = document.getElementById(displayId);
                const hiddenInput = document.getElementById(hiddenId);

                if (!displayInput || !hiddenInput) return;

                displayInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, ''); // Remove non-digits

                    // Auto-add dashes: DD-MM-YYYY
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '-' + value.substring(2);
                    }
                    if (value.length >= 5) {
                        value = value.substring(0, 5) + '-' + value.substring(5, 9);
                    }

                    e.target.value = value;

                    // When complete (dd-mm-yyyy), update hidden ISO field
                    if (value.length === 10) {
                        const parts = value.split('-');
                        if (parts.length === 3) {
                            hiddenInput.value = `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert to yyyy-mm-dd
                        }
                    }
                });
            }

            // Setup formatters for both date fields
            setupDateInputFormatter('tgl_terbit_nqr_display', 'tgl_terbit_nqr');
            setupDateInputFormatter('tgl_delivery_display', 'tgl_delivery');

            const locationSelect = document.getElementById('disposition_inventory_location');
            const actionSelect = document.getElementById('disposition_inventory_action');
            const actionField = document.getElementById('action_field');

            // Handle Disposition Inventory Location and Action
            locationSelect.addEventListener('change', function () {
                const location = this.value;

                // Reset action dropdown
                actionSelect.innerHTML = '<option value="">-- Pilih Action --</option>';

                if (location === 'At Customer') {
                    // Show action field
                    actionField.style.display = 'block';

                    const options = [
                        'Sorted by Customer',
                        'Sorted by Supplier',
                        'Sorted by PT.KYBI',
                        'Keep to Use'
                    ];
                    options.forEach(opt => {
                        const option = document.createElement('option');
                        option.value = opt;
                        option.textContent = opt;
                        actionSelect.appendChild(option);
                    });
                } else if (location === 'At PT.KYBI') {
                    // Show action field
                    actionField.style.display = 'block';

                    const options = [
                        'Sorted by Supplier',
                        'Sorted by PT.KYBI',
                        'Keep to Use'
                    ];
                    options.forEach(opt => {
                        const option = document.createElement('option');
                        option.value = opt;
                        option.textContent = opt;
                        actionSelect.appendChild(option);
                    });
                } else {
                    // Hide action field if no location selected
                    actionField.style.display = 'none';
                }
            });

            // Restore old values on page load (for validation errors)
            if (locationSelect.value) {
                locationSelect.dispatchEvent(new Event('change'));
                const oldAction = "<?php echo e(old('disposition_inventory_action')); ?>";
                if (oldAction) {
                    setTimeout(() => {
                        actionSelect.value = oldAction;
                    }, 100);
                }
            }

            // ===== VALIDATION FUNCTIONS =====
            const form = document.getElementById('nqr-form');
            const submitBtn = form.querySelector('button[type="submit"]');

            // Function to validate individual field
            function validateField(field) {
                let isValid = true;
                const errorDiv = document.querySelector(`.error-message[data-field="${field.name}"]`);

                // Remove existing error styling
                field.classList.remove('border-red-500');
                if (errorDiv) {
                    errorDiv.classList.add('hidden');
                }

                // Check if field is required and visible
                if (field.hasAttribute('required')) {
                    // Skip validation for hidden fields (like action field when not shown)
                    const parentField = field.closest('#action_field');
                    if (parentField && parentField.style.display === 'none') {
                        return true; // Skip validation for hidden field
                    }

                    if (field.type === 'file') {
                        isValid = field.files.length > 0;
                    } else if (field.tagName === 'SELECT') {
                        isValid = field.value !== '';
                    } else if (field.type === 'hidden') {
                        // For hidden ISO date fields
                        isValid = field.value.trim() !== '';
                    } else {
                        isValid = field.value.trim() !== '';
                    }

                    if (!isValid) {
                        field.classList.add('border-red-500');
                        if (errorDiv) {
                            errorDiv.classList.remove('hidden');
                        }
                    }
                }

                return isValid;
            }

            // Function to validate all fields
            function validateAllFields() {
                let allValid = true;
                const fields = form.querySelectorAll('input[required], select[required]');

                fields.forEach(field => {
                    if (!validateField(field)) {
                        allValid = false;
                    }
                });

                return allValid;
            }

            // Add real-time validation
            const allFields = form.querySelectorAll('input, select, textarea');
            allFields.forEach(field => {
                field.addEventListener('blur', function () {
                    if (this.hasAttribute('required')) {
                        validateField(this);
                    }
                });

                field.addEventListener('change', function () {
                    if (this.hasAttribute('required')) {
                        validateField(this);
                    }
                });

                // Remove error styling on focus
                field.addEventListener('focus', function () {
                    this.classList.remove('border-red-500');
                    const errorDiv = document.querySelector(`.error-message[data-field="${this.name}"]`);
                    if (errorDiv) {
                        errorDiv.classList.add('hidden');
                    }
                });
            });

            // Submit button handler
            if (submitBtn) {
                submitBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Validate all fields first
                    if (!validateAllFields()) {
                        // Scroll to first error field
                        const firstErrorField = form.querySelector('.border-red-500');
                        if (firstErrorField) {
                            firstErrorField.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            firstErrorField.focus();
                        }
                        return;
                    }

                    // Use HTML5 validation as backup
                    if (typeof form.reportValidity === 'function') {
                        if (!form.reportValidity()) return;
                    } else if (!form.checkValidity()) {
                        return;
                    }

                    // Visual feedback
                    submitBtn.disabled = true;
                    const prevHTML = submitBtn.innerHTML;
                    submitBtn.innerHTML = 'Submitting...';
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

                    // Submit form
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }

                    // Restore button after 5s in case navigation didn't happen
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = prevHTML;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }, 5000);
                });
            }

            // Image preview for create form
            const gambarInput = document.getElementById('gambar-input');
            const gambarPreview = document.getElementById('gambar-preview');
            const gambarPreviewImg = document.getElementById('gambar-preview-img');
            if (gambarInput) {
                gambarInput.addEventListener('change', function () {
                    const file = this.files && this.files[0];
                    if (!file) {
                        gambarPreview.classList.add('hidden');
                        gambarPreviewImg.src = '#';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        gambarPreviewImg.src = e.target.result;
                        gambarPreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Tombol hapus gambar utama
            const removeMainImageBtn = document.getElementById('remove-main-image');
            if (removeMainImageBtn && gambarInput && gambarPreview && gambarPreviewImg) {
                removeMainImageBtn.addEventListener('click', function () {
                    gambarInput.value = '';
                    gambarPreview.classList.add('hidden');
                    gambarPreviewImg.src = '#';
                });
            }
        });
    </script>
    <script>
        // Searchable dropdown helpers (supplier and part)
        function createDropdown(rootId) {
            const root = document.getElementById(rootId);
            if (!root) return;

            const hidden = root.querySelector('input[type="hidden"]');
            const btn = root.querySelector('button');
            const btnText = btn.querySelector('span');
            const panel = root.querySelector('[id$="panel"]');
            const list = root.querySelector('[id$="list"]');
            const search = root.querySelector('input[type="text"]');

            const items = JSON.parse(root.getAttribute('data-suppliers') || '[]');

            function renderList(filter) {
                list.innerHTML = '';
                const matched = items.filter(i => i.toLowerCase().includes(filter.toLowerCase()));
                matched.forEach(i => {
                    const el = document.createElement('div');
                    el.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm';
                    el.textContent = i;
                    el.addEventListener('click', () => {
                        hidden.value = i;
                        btnText.textContent = i;
                        panel.classList.add('hidden');
                    });
                    list.appendChild(el);
                });
            }

            btn.addEventListener('click', () => {
                panel.classList.toggle('hidden');
                if (!panel.classList.contains('hidden')) {
                    search.focus();
                    renderList(search.value || '');
                }
            });

            search.addEventListener('input', (e) => renderList(e.target.value));

            document.addEventListener('click', (e) => {
                if (!root.contains(e.target)) panel.classList.add('hidden');
            });
        }

        function createPartDropdown(rootId) {
            const root = document.getElementById(rootId);
            if (!root) return;

            const nomorHidden = root.querySelector('input[name="nomor_part"]');
            const namaHidden = root.querySelector('input[name="nama_part"]');
            const btn = root.querySelector('button');
            const btnText = btn.querySelector('span');
            const panel = root.querySelector('#' + btn.id.replace('_btn', '_panel'));
            const list = root.querySelector('#' + btn.id.replace('_btn', '_list'));
            const search = root.querySelector('input[type="text"]');

            const items = JSON.parse(root.getAttribute('data-items') || '[]');

            function renderList(filter) {
                list.innerHTML = '';
                const matched = items.filter(i => (i.kode + ' ' + i.desc).toLowerCase().includes(filter.toLowerCase()));
                matched.forEach(i => {
                    const el = document.createElement('div');
                    el.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm';
                    el.textContent = i.kode + ' — ' + i.desc;
                    el.addEventListener('click', () => {
                        nomorHidden.value = i.kode;
                        namaHidden.value = i.desc;
                        btnText.textContent = i.kode + ' — ' + i.desc;
                        panel.classList.add('hidden');
                    });
                    list.appendChild(el);
                });
            }

            btn.addEventListener('click', () => {
                panel.classList.toggle('hidden');
                if (!panel.classList.contains('hidden')) {
                    search.focus();
                    renderList(search.value || '');
                }
            });

            search.addEventListener('input', (e) => renderList(e.target.value));

            document.addEventListener('click', (e) => {
                if (!root.contains(e.target)) panel.classList.add('hidden');
            });
        }

        // Initialize with server data
        (function initDropdowns() {
            const supRoot = document.getElementById('supplier_dropdown');
            if (supRoot) {
                supRoot.setAttribute('data-suppliers', '<?php echo json_encode($suppliers->pluck("por_nama"), 15, 512) ?>');
            }
            const partRoot = document.getElementById('part_dropdown');
            if (partRoot) {
                partRoot.setAttribute('data-items', '<?php echo json_encode($items->map(function ($i) {
                    return ['kode' => $i->kode, 'desc' => $i->description];
                }), 512) ?>');
            }

            // Call initializers after DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    createDropdown('supplier_dropdown');
                    createPartDropdown('part_dropdown');
                });
            } else {
                createDropdown('supplier_dropdown');
                createPartDropdown('part_dropdown');
            }
        })();
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/qc/nqr/create.blade.php ENDPATH**/ ?>