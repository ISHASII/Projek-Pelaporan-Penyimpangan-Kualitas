<?php $__env->startSection('content'); ?>
    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="px-6 py-4 rounded-none">
                <div class="max-w-screen-xl mx-auto flex items-center">
                    <h1 class="text-red-600 text-lg font-semibold">Create CMR</h1>
                </div>
            </div>

            <div class="max-w-screen-xl mx-auto px-6 py-6">
                <form action="<?php echo e(route('qc.cmr.store')); ?>" method="POST" enctype="multipart/form-data" id="cmr-form">
                    <?php echo csrf_field(); ?>

                    <div class="flex items-center mb-8">
                        <a href="<?php echo e(route('qc.cmr.index')); ?>"
                            class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                            <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                            <span>Back</span>
                        </a>
                    </div>

                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">CMR TYPE <span
                                    class="text-red-500">*</span></label>
                            <select name="cmr_type" id="cmr_type" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="4W" <?php echo e(old('cmr_type', '4W') == '4W' ? 'selected' : ''); ?>>4W</option>
                                <option value="2W" <?php echo e(old('cmr_type') == '2W' ? 'selected' : ''); ?>>2W</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-compact">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">CMR ISSUE DATE (発行日)<span
                                        class="text-red-500">*</span></label>
                                <input type="hidden" name="tgl_terbit_cmr" id="tgl_terbit_cmr"
                                    value="<?php echo e(old('tgl_terbit_cmr') ? (is_string(old('tgl_terbit_cmr')) && strtotime(old('tgl_terbit_cmr')) ? date('Y-m-d', strtotime(old('tgl_terbit_cmr'))) : old('tgl_terbit_cmr')) : ''); ?>"
                                    required>
                                <input type="text" name="tgl_terbit_cmr_display" id="tgl_terbit_cmr_display"
                                    inputmode="numeric" pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('tgl_terbit_cmr') ? ((strpos(old('tgl_terbit_cmr'), '-') !== false && strtotime(old('tgl_terbit_cmr'))) ? date('d-m-Y', strtotime(old('tgl_terbit_cmr'))) : str_replace('/', '-', old('tgl_terbit_cmr'))) : ''); ?>">
                                <?php $__errorArgs = ['tgl_terbit_cmr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_terbit_cmr">CMR
                                    ISSUE DATE is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">DELIVERY DATE (配達日)<span
                                        class="text-red-500">*</span></label>
                                <input type="hidden" name="tgl_delivery" id="tgl_delivery"
                                    value="<?php echo e(old('tgl_delivery') ? (is_string(old('tgl_delivery')) && strtotime(old('tgl_delivery')) ? date('Y-m-d', strtotime(old('tgl_delivery'))) : old('tgl_delivery')) : ''); ?>"
                                    required>
                                <input type="text" name="tgl_delivery_display" id="tgl_delivery_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('tgl_delivery') ? ((strpos(old('tgl_delivery'), '-') !== false && strtotime(old('tgl_delivery'))) ? date('d-m-Y', strtotime(old('tgl_delivery'))) : str_replace('/', '-', old('tgl_delivery'))) : ''); ?>">
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
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_delivery">
                                    DELIVERY DATE is required</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">B/L date (船積日)</label>
                                <input type="hidden" name="bl_date" id="bl_date"
                                    value="<?php echo e(old('bl_date') ? (is_string(old('bl_date')) && strtotime(old('bl_date')) ? date('Y-m-d', strtotime(old('bl_date'))) : old('bl_date')) : ''); ?>">
                                <input type="text" name="bl_date_display" id="bl_date_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('bl_date') ? ((strpos(old('bl_date'), '-') !== false && strtotime(old('bl_date'))) ? date('d-m-Y', strtotime(old('bl_date'))) : str_replace('/', '-', old('bl_date'))) : ''); ?>">
                                <?php $__errorArgs = ['bl_date'];
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

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">A/R Date (到着日)</label>
                                <input type="hidden" name="ar_date" id="ar_date"
                                    value="<?php echo e(old('ar_date') ? (is_string(old('ar_date')) && strtotime(old('ar_date')) ? date('Y-m-d', strtotime(old('ar_date'))) : old('ar_date')) : ''); ?>">
                                <input type="text" name="ar_date_display" id="ar_date_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('ar_date') ? ((strpos(old('ar_date'), '-') !== false && strtotime(old('ar_date'))) ? date('d-m-Y', strtotime(old('ar_date'))) : str_replace('/', '-', old('ar_date'))) : ''); ?>">
                                <?php $__errorArgs = ['ar_date'];
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Found Date (発見日)</label>
                                <input type="hidden" name="found_date" id="found_date"
                                    value="<?php echo e(old('found_date') ? (is_string(old('found_date')) && strtotime(old('found_date')) ? date('Y-m-d', strtotime(old('found_date'))) : old('found_date')) : ''); ?>">
                                <input type="text" name="found_date_display" id="found_date_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('found_date') ? ((strpos(old('found_date'), '-') !== false && strtotime(old('found_date'))) ? date('d-m-Y', strtotime(old('found_date'))) : str_replace('/', '-', old('found_date'))) : ''); ?>">
                                <?php $__errorArgs = ['found_date'];
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <!-- Supplier searchable dropdown -->
                            <div id="supplier_dropdown" class="relative">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">SUPPLIER NAME (サプライヤ名) <span
                                        class="text-red-500">*</span></label>
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
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nama_supplier">
                                    SUPPLIER NAME is required</div>
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

                            <!-- Part searchable dropdown (nomor_part -> nama_part) -->
                            <div id="part_dropdown" class="relative">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">NOMOR PART (部品番号) <span
                                        class="text-red-500">*</span></label>
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
                                    PART is required</div>
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

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">PO NUMBER (注文番号)<span
                                    class="text-red-500">*</span></label>
                            <input name="nomor_po" value="<?php echo e(old('nomor_po')); ?>" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="PO NUMBER">
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
                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nomor_po">PO NUMBER is
                                required</div>
                        </div>

                        <!-- nomor_part handled in searchable dropdown above -->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">INVOICE NO (請求書番号)<span
                                        class="text-red-500">*</span></label>
                                <input name="invoice_no" value="<?php echo e(old('invoice_no')); ?>" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="INVOICE NO">
                                <?php $__errorArgs = ['invoice_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="invoice_no">INVOICE
                                    NO is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">ORDER NO (|オーダーNo.) <span
                                        class="text-red-500">*</span></label>
                                <input name="order_no" value="<?php echo e(old('order_no')); ?>" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="ORDER NO">
                                <?php $__errorArgs = ['order_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="order_no">ORDER NO
                                    is required</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">PRODUCT (製品) <span
                                        class="text-red-500">*</span></label>
                                <select name="product" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">--Choose --</option>
                                    <option value="SKA" <?php echo e(old('product') == 'SKA' ? 'selected' : ''); ?>>SKA</option>
                                    <option value="FF" <?php echo e(old('product') == 'FF' ? 'selected' : ''); ?>>FF</option>
                                    <option value="OCU" <?php echo e(old('product') == 'OCU' ? 'selected' : ''); ?>>OCU</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="product">PRODUCT is
                                    required</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">MODEL (模型)</label>
                                <input name="model" value="<?php echo e(old('model')); ?>"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Model">
                                <?php $__errorArgs = ['model'];
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
                            <div>

                                <div class="mb-6">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">CRATE NUMBER (箱番号)</label>
                                    <input name="crate_number" value="<?php echo e(old('crate_number')); ?>"
                                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        placeholder="CRATE NUMBER">
                                    <?php $__errorArgs = ['crate_number'];
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
                                <label class="block text-xs font-semibold text-gray-700 mb-1">LOCATION CLAIM OCCUR
                                    (クレーム発生場所)<span class="text-red-500">*</span></label>
                                <select name="location_claim_occurrence" id="lokasi_penemuan_claim" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">--Choose --</option>
                                    <option value="Receiving Inspect" <?php echo e(old('location_claim_occurrence') == 'Receiving Inspect' ? 'selected' : ''); ?>>Receiving Inspect (受入检查)</option>
                                    <option value="In-Process" <?php echo e(old('location_claim_occurrence') == 'In-Process' ? 'selected' : ''); ?>>In-Process (工程内)</option>
                                    <option value="Customer" <?php echo e(old('location_claim_occurrence') == 'Customer' ? 'selected' : ''); ?>>Customer (客先)</option>
                                </select>
                                <?php $__errorArgs = ['location_claim_occurrence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="location_claim_occurrence">LOCATION CLAIM OCCUR is required</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">DISPOSITION OF INVENTORY TYPE
                                    (在庫品处理)<span class="text-red-500">*</span></label>
                                <select name="disposition_inventory_type" id="disposition_inventory_type" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">--Choose --</option>
                                    <option value="AT CUSTOMER" <?php echo e(old('disposition_inventory_type') == 'AT CUSTOMER' ? 'selected' : ''); ?>>AT CUSTOMER (客先にて)</option>
                                    <option value="AT PT.KYBI" <?php echo e(old('disposition_inventory_type') == 'AT PT.KYBI' ? 'selected' : ''); ?>>AT PT.KYBI (PT KYB にて)</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="disposition_inventory_type">DISPOSITION OF INVENTORY TYPE is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">DISPOSITION INVENTORY CHOICE
                                    (在庫品处理)<span class="text-red-500">*</span></label>
                                <select name="disposition_inventory_choice" id="disposition_inventory_choice" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">--Choose --</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="disposition_inventory_choice">DISPOSITION INVENTORY CHOICE is required</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">CLAIM OCCURRENCE FREQUENCY
                                    (請求発生頻度)<span class="text-red-500">*</span></label>
                                <select name="claim_occurrence_frequency" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">--Choose --</option>
                                    <option value="First time" <?php echo e(old('claim_occurrence_frequency') == 'First time' ? 'selected' : ''); ?>>First time (初回)</option>
                                    <option value="Reoccurred" <?php echo e(old('claim_occurrence_frequency') == 'Reoccurred' ? 'selected' : ''); ?>>Reoccurred (再発)</option>
                                    <option value="Intermittently" <?php echo e(old('claim_occurrence_frequency') == 'Intermittently' ? 'selected' : ''); ?>>Intermittently (断統的)</option>
                                    <option value="Continuously" <?php echo e(old('claim_occurrence_frequency') == 'Continuously' ? 'selected' : ''); ?>>Continuously (總統的)</option>
                                    <option value="Other" <?php echo e(old('claim_occurrence_frequency') == 'Other' ? 'selected' : ''); ?>>Other (その他)</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="claim_occurrence_frequency">CLAIM OCCURRENCE FREQUENCY is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">DISPATCH OF DEFECTIVE PARTS
                                    (不良部品の発送)<span class="text-red-500">*</span></label>
                                <select name="dispatch_defective_parts" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">--Choose --</option>
                                    <option value="Dispatch with this report" <?php echo e(old('dispatch_defective_parts') == 'Dispatch with this report' ? 'selected' : ''); ?>>Dispatch with this report (本レポートと共に送付)
                                    </option>
                                    <option value="Dispatch separately" <?php echo e(old('dispatch_defective_parts') == 'Dispatch separately' ? 'selected' : ''); ?>>Dispatch separately (別途送付)</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="dispatch_defective_parts">DISPATCH OF DEFECTIVE PARTS is required</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">DISPOSITION OF DEFECT PARTS
                                    (不良部品の処分)<span class="text-red-500">*</span></label>
                                <select name="disposition_defect_parts" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">--Choose --</option>
                                    <option value="Keep to use" <?php echo e(old('disposition_defect_parts') == 'Keep to use' ? 'selected' : ''); ?>>Keep to use (總統使用)</option>
                                    <option value="Return to KYB" <?php echo e(old('disposition_defect_parts') == 'Return to KYB' ? 'selected' : ''); ?>>Return to KYB (KYB 返却)</option>
                                    <option value="Scrapped at PT.KYB" <?php echo e(old('disposition_defect_parts') == 'Scrapped at PT.KYB' ? 'selected' : ''); ?>>Scrapped at PT.KYB (PTKYB にて廃却)</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="disposition_defect_parts">DISPOSITION OF DEFECT PARTS is required</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">QTY ORDER (注文数量)<span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="qty_order" value="<?php echo e(old('qty_order')); ?>" required min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="QTY ORDER">
                                <?php $__errorArgs = ['qty_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="qty_order">QTY ORDER
                                    is required</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">QTY DELIVERED (納品数量)<span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="qty_deliv" value="<?php echo e(old('qty_deliv')); ?>" required min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="QTY DELIVERED">
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="qty_deliv">QTY
                                    DELIVERED is required</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">QTY PROBLEM (数量の問題)<span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="qty_problem" value="<?php echo e(old('qty_problem')); ?>" required min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="QTY PROBLEM">
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="qty_problem">QTY
                                    PROBLEM is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">IMAGE INPUT (画像入力)<span
                                        class="text-red-500">*</span></label>
                                <input type="file" name="gambar" id="gambar-input" accept="image/*" required
                                    class="w-full text-sm border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <div id="gambar-preview" class="mt-3 hidden">
                                    <div class="relative inline-block">
                                        <img id="gambar-preview-img" src="#" alt="preview"
                                            class="w-32 h-24 object-cover border-2 border-gray-300 rounded-lg shadow-sm">
                                        <button type="button" id="remove-main-image"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 focus:outline-none">×</button>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-2">Preview IMAGE</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">INPUT PROBLEM (入力問題)</label>
                            <input name="input_problem" id="input_problem" value="<?php echo e(old('input_problem')); ?>" type="text"
                                maxlength="75"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Describe problem (max 75 characters)">
                            <div class="text-right text-xs text-gray-500 mt-1">
                                <span id="input_problem_counter">0</span>/75 karakter
                            </div>
                            <?php $__errorArgs = ['input_problem'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="gambar">IMAGE is
                                required</div>
                        </div>

                        <div class="pt-4 border-t flex justify-end items-center">
                            <button type="button" id="force-submit-btn"
                                class="bg-red-600 text-white px-5 py-3 rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">Create
                                CMR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                // Character counter for input_problem
                const inputProblem = document.getElementById('input_problem');
                const inputProblemCounter = document.getElementById('input_problem_counter');

                if (inputProblem && inputProblemCounter) {
                    // Set initial count
                    inputProblemCounter.textContent = inputProblem.value.length;

                    // Update counter on input
                    inputProblem.addEventListener('input', function () {
                        inputProblemCounter.textContent = this.value.length;
                    });
                }

                // Attach flatpickr if available for the two date fields
                (function attachCalendar() {
                    const fields = [
                        { hidden: 'tgl_terbit_cmr', display: 'tgl_terbit_cmr_display' },
                        { hidden: 'tgl_delivery', display: 'tgl_delivery_display' },
                        { hidden: 'bl_date', display: 'bl_date_display' },
                        { hidden: 'ar_date', display: 'ar_date_display' },
                        { hidden: 'found_date', display: 'found_date_display' }
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
                                onOpen: function (selectedDates, dateStr, instance) { if (!instance.input.value) instance.jumpToDate(new Date()); },
                                onChange: function (selectedDates, dateStr) {
                                    const parts = dateStr.split('-');
                                    if (parts.length === 3) {
                                        h.value = `${parts[2]}-${parts[1]}-${parts[0]}`;
                                    }
                                }
                            });
                        });
                    }
                    if (window.flatpickr) {
                        bind(window.flatpickr);
                        return;
                    }
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.css")); ?>';
                    document.head.appendChild(link);
                    const s = document.createElement('script');
                    s.src = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.js")); ?>';
                    s.onload = function () { bind(window.flatpickr); };
                    document.body.appendChild(s);
                })();

                const form = document.getElementById('cmr-form');
                const btn = document.getElementById('force-submit-btn');
                if (!form || !btn) return;

                function toISO(dmy) {
                    if (!dmy) return '';
                    const m = dmy.match(/^(\d{2})\-(\d{2})\-(\d{4})$/);
                    if (!m) return '';
                    const [_, dd, mm, yyyy] = m;
                    return `${yyyy}-${mm}-${dd}`;
                }
                function toDMY(iso) {
                    if (!iso) return '';
                    const m = iso.match(/^(\d{4})-(\d{2})-(\d{2})$/);
                    if (!m) return '';
                    const [_, yyyy, mm, dd] = m;
                    return `${dd}-${mm}-${yyyy}`;
                }

                function maskDMY(value) {
                    const digits = (value || '').replace(/\D/g, '').slice(0, 8);
                    if (!digits) return '';
                    if (digits.length <= 2) return digits;
                    if (digits.length <= 4) return `${digits.slice(0, 2)}-${digits.slice(2)}`;
                    return `${digits.slice(0, 2)}-${digits.slice(2, 4)}-${digits.slice(4)}`;
                }

                const datePairs = [
                    { hidden: 'tgl_terbit_cmr', display: 'tgl_terbit_cmr_display' },
                    { hidden: 'tgl_delivery', display: 'tgl_delivery_display' },
                    { hidden: 'bl_date', display: 'bl_date_display' },
                    { hidden: 'ar_date', display: 'ar_date_display' },
                    { hidden: 'found_date', display: 'found_date_display' }
                ];

                datePairs.forEach(({ hidden, display }) => {
                    const h = document.getElementById(hidden);
                    const d = document.getElementById(display);
                    if (!h || !d) return;
                    if (!d.value && h.value) d.value = toDMY(h.value);
                    d.addEventListener('input', () => {
                        const masked = maskDMY(d.value);
                        if (d.value !== masked) d.value = masked;
                        if (/^\d{2}-\d{2}-\d{4}$/.test(masked)) {
                            h.value = toISO(masked);
                        }
                        d.classList.remove('border-red-500');
                    });
                    d.addEventListener('blur', () => {
                        const iso = toISO(d.value.trim());
                        h.value = iso;
                        if (!iso && d.value.trim() !== '') {
                            d.classList.add('border-red-500');
                        } else {
                            d.classList.remove('border-red-500');
                        }
                    });
                });

                // Image preview
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

                const removeMainImageBtn = document.getElementById('remove-main-image');
                if (removeMainImageBtn && gambarInput && gambarPreview && gambarPreviewImg) {
                    removeMainImageBtn.addEventListener('click', function () {
                        gambarInput.value = '';
                        gambarPreview.classList.add('hidden');
                        gambarPreviewImg.src = '#';
                    });
                }

                function validateField(field) {
                    let isValid = true;
                    const errorDiv = document.querySelector(`.error-message[data-field="${field.name}"]`);
                    field.classList.remove('border-red-500');
                    if (errorDiv) errorDiv.classList.add('hidden');

                    if (field.hasAttribute('required')) {
                        if (field.type === 'file') {
                            isValid = field.files.length > 0;
                        } else if (field.tagName === 'SELECT') {
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

                const fields = form.querySelectorAll('input, select, textarea');
                fields.forEach(field => {
                    field.addEventListener('blur', function () { validateField(this); });
                    field.addEventListener('change', function () { validateField(this); });
                    field.addEventListener('focus', function () {
                        this.classList.remove('border-red-500');
                        const errorDiv = document.querySelector(`.error-message[data-field="${this.name}"]`);
                        if (errorDiv) errorDiv.classList.add('hidden');
                    });
                });

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (!validateAllFields()) {
                        const firstErrorField = form.querySelector('.border-red-500');
                        if (firstErrorField) { firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstErrorField.focus(); }
                        return;
                    }
                    if (typeof form.reportValidity === 'function') { if (!form.reportValidity()) return; } else if (!form.checkValidity()) return;

                    btn.disabled = true;
                    const prev = btn.innerHTML;
                    btn.innerHTML = 'Submitting...';
                    btn.classList.add('opacity-50', 'cursor-not-allowed');

                    if (typeof form.requestSubmit === 'function') { form.requestSubmit(); } else { form.submit(); }

                    setTimeout(() => { btn.disabled = false; btn.innerHTML = prev; btn.classList.remove('opacity-50', 'cursor-not-allowed'); }, 5000);
                });

                // disposition choice dynamic options
                const type = document.getElementById('disposition_inventory_type');
                const choice = document.getElementById('disposition_inventory_choice');
                const options = {
                    'AT CUSTOMER': [
                        ['Sorted by customer', 'Sorted by customer (客先による選別)'],
                        ['Sorted by PT.KYB', 'Sorted by PT.KYB (拠点による選別)'],
                        ['Keep to use', 'Keep to use (總統使用)']
                    ],
                    'AT PT.KYBI': [
                        ['Sorted by PT.KYB', 'Sorted by PT.KYB (PT.KYB による選別)'],
                        ['Keep to use', 'Keep to use (使用)'],
                        ['Return to KYB', 'Return to KYB (KYB 返却)'],
                        ['Other', 'Other (その他)']
                    ]
                };
                function renderChoices() { const val = type ? type.value : ''; if (!choice) return; choice.innerHTML = '<option value="">--Choose --</option>'; if (options[val]) options[val].forEach(o => { const el = document.createElement('option'); el.value = o[0]; el.textContent = o[1]; if ("<?php echo e(old('disposition_inventory_choice')); ?>" === o[0]) el.selected = true; choice.appendChild(el); }); }
                if (type) { type.addEventListener('change', renderChoices); renderChoices(); }



            } catch (err) { console && console.error('Form validation script error', err); }
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
                supRoot.setAttribute('data-suppliers', '<?php echo json_encode($suppliers, 15, 512) ?>');
            }
            const partRoot = document.getElementById('part_dropdown');
            if (partRoot) {
                partRoot.setAttribute('data-items', '<?php echo json_encode($items->map(function ($i) {
                    return ["kode" => $i->kode, "desc" => $i->description];
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
<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/qc/cmr/create.blade.php ENDPATH**/ ?>