<?php $__env->startSection('content'); ?>
    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="px-6 py-4 rounded-none">
                <div class="max-w-screen-xl mx-auto flex items-center">
                    <h1 class="text-red-600 text-lg font-semibold">Edit LPK</h1>
                </div>
            </div>

            <div class="max-w-screen-xl mx-auto px-6 py-6">
                <form action="<?php echo e(route('qc.lpk.update', $lpk->id)); ?>" method="POST" enctype="multipart/form-data"
                    id="lpk-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="flex items-center mb-8">
                        <a href="<?php echo e(route('qc.lpk.index')); ?>"
                            class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                            <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                            <span>Kembali</span>
                        </a>
                    </div>

                    <?php if(session('status')): ?>
                        <div class="mb-4 text-sm text-red-600"><?php echo e(session('status')); ?></div>
                    <?php endif; ?>
                    <div class="form-compact">
                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                NO REG
                            </label>
                            <input type="text" value="<?php echo e($lpk->no_reg); ?>" readonly
                                class="w-full bg-gray-100 border rounded-lg px-3 py-2 text-sm" />
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">REFERENSI LKA <span
                                        class="text-red-500">*</span></label>
                                <input name="referensi_lka" value="<?php echo e(old('referensi_lka', $lpk->referensi_lka)); ?>"
                                    type="text" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Referensi LKA">
                                <?php $__errorArgs = ['referensi_lka'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="referensi_lka">
                                    Referensi LKA wajib diisi</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">TGL TERBIT LKA <span
                                        class="text-red-500">*</span></label>
                                <!-- Hidden ISO value for submit -->
                                <input type="hidden" name="tgl_terbit_lka" id="tgl_terbit_lka"
                                    value="<?php echo e(old('tgl_terbit_lka', $lpk->tgl_terbit_lka ? (is_string($lpk->tgl_terbit_lka) ? (strtotime($lpk->tgl_terbit_lka) ? date('Y-m-d', strtotime($lpk->tgl_terbit_lka)) : '') : $lpk->tgl_terbit_lka->format('Y-m-d')) : '')); ?>"
                                    required>
                                <!-- Visible formatted input -->
                                <input type="text" id="tgl_terbit_lka_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('tgl_terbit_lka', $lpk->tgl_terbit_lka ? (is_string($lpk->tgl_terbit_lka) ? (strtotime($lpk->tgl_terbit_lka) ? date('d-m-Y', strtotime($lpk->tgl_terbit_lka)) : '') : $lpk->tgl_terbit_lka->format('d-m-Y')) : '')); ?>">
                                <?php $__errorArgs = ['tgl_terbit_lka'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_terbit_lka">TGL
                                    TERBIT LKA wajib diisi</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TGL TERBIT LPK <span class="text-red-500">*</span>
                                </label>
                                <!-- Hidden ISO value for submit -->
                                <input type="hidden" name="tgl_terbit" id="tgl_terbit"
                                    value="<?php echo e(old('tgl_terbit', $lpk->tgl_terbit ? (is_string($lpk->tgl_terbit) ? (strtotime($lpk->tgl_terbit) ? date('Y-m-d', strtotime($lpk->tgl_terbit)) : '') : $lpk->tgl_terbit->format('Y-m-d')) : '')); ?>"
                                    required>
                                <!-- Visible formatted input -->
                                <input type="text" id="tgl_terbit_display" inputmode="numeric" pattern="\d{2}-\d{2}-\d{4}"
                                    placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('tgl_terbit', $lpk->tgl_terbit ? (is_string($lpk->tgl_terbit) ? (strtotime($lpk->tgl_terbit) ? date('d-m-Y', strtotime($lpk->tgl_terbit)) : '') : $lpk->tgl_terbit->format('d-m-Y')) : '')); ?>">
                                <?php $__errorArgs = ['tgl_terbit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_terbit">TGL
                                    TERBIT wajib diisi</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TGL DELIVERY <span class="text-red-500">*</span>
                                </label>
                                <!-- Hidden ISO value for submit -->
                                <input type="hidden" name="tgl_delivery" id="tgl_delivery"
                                    value="<?php echo e(old('tgl_delivery', $lpk->tgl_delivery ? (is_string($lpk->tgl_delivery) ? (strtotime($lpk->tgl_delivery) ? date('Y-m-d', strtotime($lpk->tgl_delivery)) : '') : $lpk->tgl_delivery->format('Y-m-d')) : '')); ?>"
                                    required>
                                <!-- Visible formatted input -->
                                <input type="text" id="tgl_delivery_display" inputmode="numeric" pattern="\d{2}-\d{2}-\d{4}"
                                    placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="<?php echo e(old('tgl_delivery', $lpk->tgl_delivery ? (is_string($lpk->tgl_delivery) ? (strtotime($lpk->tgl_delivery) ? date('d-m-Y', strtotime($lpk->tgl_delivery)) : '') : $lpk->tgl_delivery->format('d-m-Y')) : '')); ?>">
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
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_delivery">TGL
                                    DELIVERY wajib diisi</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    NAMA SUPPLY <span class="text-red-500">*</span>
                                </label>

                                <div id="supplier_dropdown"
                                    data-suppliers='<?php echo json_encode(isset($suppliers) ? $suppliers->pluck("por_nama") : [], 15, 512) ?>'
                                    class="relative">
                                    <input type="hidden" name="nama_supply" id="nama_supply"
                                        value="<?php echo e(old('nama_supply', $lpk->nama_supply)); ?>">

                                    <button type="button" id="nama_supply_btn" aria-haspopup="listbox" aria-expanded="false"
                                        class="w-full text-left border rounded-lg px-3 py-2 text-sm flex items-center justify-between focus:outline-none">
                                        <span id="nama_supply_label"
                                            class="truncate"><?php echo e(old('nama_supply', $lpk->nama_supply) ?: '-- Pilih Supplier --'); ?></span>
                                        <img src="<?php echo e(asset('icon/dropdown.ico')); ?>" alt="dropdown" class="w-4 h-4">
                                    </button>

                                    <div id="nama_supply_panel"
                                        class="absolute z-50 mt-1 w-full bg-white border rounded shadow-lg hidden">
                                        <div class="p-2">
                                            <input id="nama_supply_panel_search" type="search" autocomplete="off"
                                                placeholder="Ketik untuk mencari..."
                                                class="w-full border rounded px-3 py-2 text-sm">
                                        </div>
                                        <ul id="nama_supply_list" role="listbox" class="max-h-48 overflow-auto"></ul>
                                    </div>
                                </div>

                                <?php $__errorArgs = ['nama_supply'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nama_supply">NAMA
                                    SUPPLY wajib diisi</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">NOMOR PART <span
                                        class="text-red-500">*</span></label>

                                <div id="part_dropdown" class="relative"
                                    data-items='<?php echo json_encode(isset($items) ? $items->map(fn($i) => ["kode" => $i->kode, "desc" => $i->description]) : [], 512) ?>'>
                                    <input type="hidden" name="nomor_part" id="nomor_part"
                                        value="<?php echo e(old('nomor_part', $lpk->nomor_part)); ?>">
                                    <input type="hidden" name="nama_part" id="nama_part"
                                        value="<?php echo e(old('nama_part', $lpk->nama_part)); ?>">

                                    <button type="button" id="nomor_part_btn" aria-haspopup="listbox"
                                        class="w-full text-left border rounded-lg px-3 py-2 text-sm flex items-center justify-between focus:outline-none">
                                        <span id="nomor_part_label"
                                            class="truncate"><?php echo e(old('nomor_part', $lpk->nomor_part) ? (old('nomor_part', $lpk->nomor_part) . ' - ' . (old('nama_part', $lpk->nama_part) ?? '')) : '-- Pilih Nomor Part --'); ?></span>
                                        <img src="<?php echo e(asset('icon/dropdown.ico')); ?>" alt="dropdown" class="w-4 h-4">
                                    </button>

                                    <div id="nomor_part_panel"
                                        class="absolute z-50 mt-1 w-full bg-white border rounded shadow-lg hidden">
                                        <div class="p-2">
                                            <input id="nomor_part_panel_search" type="search" autocomplete="off"
                                                placeholder="Ketik untuk mencari part (kode atau deskripsi)..."
                                                class="w-full border rounded px-3 py-2 text-sm">
                                        </div>
                                        <ul id="nomor_part_list" role="listbox" class="max-h-48 overflow-auto"></ul>
                                    </div>
                                </div>
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
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nomor_part">NOMOR
                                    PART wajib diisi</div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                NOMOR PO <span class="text-red-500">*</span>
                            </label>
                            <input name="nomor_po" value="<?php echo e(old('nomor_po', $lpk->nomor_po)); ?>" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="NOMOR PO">
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
                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nomor_po">NOMOR PO
                                wajib diisi</div>
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    STATUS LPK <span class="text-red-500">*</span>
                                </label>
                                <select name="status" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Claim" <?php echo e(old('status', $lpk->status) == 'Claim' ? 'selected' : ''); ?>>Claim
                                    </option>
                                    <option value="Complaint (Informasi)" <?php echo e(in_array(old('status', $lpk->status), ['Complaint (Informasi)', 'Informasi']) ? 'selected' : ''); ?>>Complaint (Informasi)
                                    </option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="status">Status LPK
                                    wajib dipilih</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    JENIS LPK <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_ng" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Quality" <?php echo e(old('jenis_ng', $lpk->jenis_ng) == 'Quality' ? 'selected' : ''); ?>>Quality
                                    </option>
                                    <option value="Delivery" <?php echo e(old('jenis_ng', $lpk->jenis_ng) == 'Delivery' ? 'selected' : ''); ?>>Delivery
                                    </option>
                                </select>
                                <?php $__errorArgs = ['jenis_ng'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="jenis_ng">JENIS LPK
                                    wajib dipilih</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">KATEGORI <span
                                        class="text-red-500">*</span></label>
                                <select name="kategori" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Qty Kurang" <?php echo e(old('kategori', $lpk->kategori) == 'Qty Kurang' ? 'selected' : ''); ?>>Qty Kurang
                                    </option>
                                    <option value="Subcont Prod" <?php echo e(old('kategori', $lpk->kategori) == 'Subcont Prod' ? 'selected' : ''); ?>>Subcont
                                        Prod</option>
                                    <option value="Part Repair" <?php echo e(old('kategori', $lpk->kategori) == 'Part Repair' ? 'selected' : ''); ?>>Part
                                        Repair
                                    </option>
                                    <option value="Reject Process" <?php echo e(old('kategori', $lpk->kategori) == 'Reject Process' ? 'selected' : ''); ?>>Reject
                                        Process</option>
                                    <option value="Salah Barang/Label" <?php echo e(old('kategori', $lpk->kategori) == 'Salah Barang/Label' ? 'selected' : ''); ?>>
                                        Salah
                                        Barang/Label</option>
                                </select>
                                <?php $__errorArgs = ['kategori'];
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
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">PERLAKUAN TERHADAP PART <span
                                        class="text-red-500">*</span></label>
                                <select name="perlakuan_terhadap_part" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Sortir Oleh Customer" <?php echo e(old('perlakuan_terhadap_part', $lpk->perlakuan_terhadap_part) == 'Sortir Oleh Customer' ? 'selected' : ''); ?>>
                                        Sortir Oleh Customer</option>
                                    <option value="Sortir Oleh Supplier" <?php echo e(old('perlakuan_terhadap_part', $lpk->perlakuan_terhadap_part) == 'Sortir Oleh Supplier' ? 'selected' : ''); ?>>
                                        Sortir Oleh Supplier</option>
                                    <option value="Sortir PT KYBI" <?php echo e(old('perlakuan_terhadap_part', $lpk->perlakuan_terhadap_part) == 'Sortir PT KYBI' ? 'selected' : ''); ?>>
                                        Sortir PT KYBI</option>
                                    <option value="Part Tetap Dipakai" <?php echo e(old('perlakuan_terhadap_part', $lpk->perlakuan_terhadap_part) == 'Part Tetap Dipakai' ? 'selected' : ''); ?>>
                                        Part Tetap Dipakai</option>
                                </select>
                                <?php $__errorArgs = ['perlakuan_terhadap_part'];
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
                                    data-field="perlakuan_terhadap_part">Perlakuan Terhadap Part wajib dipilih</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">FREKUENSI CLAIM <span
                                        class="text-red-500">*</span></label>
                                <select name="frekuensi_claim" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Pertama Kali" <?php echo e(old('frekuensi_claim', $lpk->frekuensi_claim) == 'Pertama Kali' ? 'selected' : ''); ?>>
                                        Pertama Kali</option>
                                    <option value="Berulang Kali atau Rutin" <?php echo e(old('frekuensi_claim', $lpk->frekuensi_claim) == 'Berulang Kali atau Rutin' ? 'selected' : ''); ?>>
                                        Berulang Kali atau Rutin</option>
                                </select>
                                <?php $__errorArgs = ['frekuensi_claim'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="frekuensi_claim">
                                    Frekuensi Claim wajib dipilih</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">PERLAKUAN PART DEFECT <span
                                        class="text-red-500">*</span></label>
                                <select name="perlakuan_part_defect" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Direpair Supplier" <?php echo e(old('perlakuan_part_defect', $lpk->perlakuan_part_defect) == 'Direpair Supplier' ? 'selected' : ''); ?>>
                                        Direpair Supplier</option>
                                    <option value="Replace" <?php echo e(old('perlakuan_part_defect', $lpk->perlakuan_part_defect) == 'Replace' ? 'selected' : ''); ?>>
                                        Replace</option>
                                    <option value="Dikembalikan ke Supplier" <?php echo e(old('perlakuan_part_defect', $lpk->perlakuan_part_defect) == 'Dikembalikan ke Supplier' ? 'selected' : ''); ?>>
                                        Dikembalikan ke Supplier</option>
                                    <option value="Discrap di PT KYBI" <?php echo e(old('perlakuan_part_defect', $lpk->perlakuan_part_defect) == 'Discrap di PT KYBI' ? 'selected' : ''); ?>>
                                        Discrap di PT KYBI</option>
                                </select>
                                <?php $__errorArgs = ['perlakuan_part_defect'];
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
                                    data-field="perlakuan_part_defect">Perlakuan Part Defect wajib dipilih</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">LOKASI PENEMUAN CLAIM <span
                                        class="text-red-500">*</span></label>
                                <select name="lokasi_penemuan_claim" id="lokasi_penemuan_claim" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Receiving Insp" <?php echo e(old('lokasi_penemuan_claim', $lpk->lokasi_penemuan_claim) == 'Receiving Insp' ? 'selected' : ''); ?>>
                                        Receiving Insp</option>
                                    <option value="In-Proses" <?php echo e(old('lokasi_penemuan_claim', $lpk->lokasi_penemuan_claim) == 'In-Proses' ? 'selected' : ''); ?>>
                                        In-Proses</option>
                                    <option value="Customer PT" <?php echo e(old('lokasi_penemuan_claim', $lpk->lokasi_penemuan_claim) == 'Customer PT' ? 'selected' : ''); ?>>
                                        Customer PT</option>
                                </select>
                                <?php $__errorArgs = ['lokasi_penemuan_claim'];
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
                                    data-field="lokasi_penemuan_claim">Lokasi Penemuan Claim wajib dipilih</div>
                            </div>

                            <!-- Input untuk Nama Customer PT (muncul jika Customer PT dipilih) -->
                            <div id="customer_pt_name_wrapper" style="display: none;">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">NAMA CUSTOMER PT <span
                                        class="text-red-500">*</span></label>
                                <input name="customer_pt_name" id="customer_pt_name"
                                    value="<?php echo e(old('customer_pt_name', $lpk->customer_pt_name)); ?>" type="text"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Masukkan Nama Customer PT">
                                <?php $__errorArgs = ['customer_pt_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="customer_pt_name">
                                    Nama Customer PT wajib diisi</div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">STATUS PART CLAIM <span
                                    class="text-red-500">*</span></label>
                            <select name="status_repair" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">-- Pilih --</option>
                                <option value="Bisa Repair" <?php echo e(old('status_repair', $lpk->status_repair) == 'Bisa Repair' ? 'selected' : ''); ?>>Bisa
                                    Repair</option>
                                <option value="Tidak Repair" <?php echo e(old('status_repair', $lpk->status_repair) == 'Tidak Repair' ? 'selected' : ''); ?>>
                                    Tidak
                                    Repair</option>
                            </select>
                            <?php $__errorArgs = ['status_repair'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="status_repair">Status
                                Part Claim wajib dipilih</div>
                        </div>

                        <div class="mt-4 mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                INPUT GAMBAR
                            </label>
                            <input type="file" name="gambar" id="gambar-input"
                                class="w-full text-sm border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <div id="gambar-preview" class="mt-3 <?php echo e($lpk->gambar ? '' : 'hidden'); ?>">
                                <div class="relative inline-block">
                                    <img id="gambar-preview-img"
                                        src="<?php echo e($lpk->gambar ? asset('storage/' . ltrim($lpk->gambar, 'storage/')) : '#'); ?>"
                                        alt="preview"
                                        class="w-32 h-24 object-cover border-2 border-gray-300 rounded-lg shadow-sm">
                                    <button type="button" id="remove-main-image"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 focus:outline-none">
                                        ×
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600 mt-2">Preview gambar utama</p>
                            </div>
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
                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="gambar">GAMBAR wajib
                                diupload</div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                DETAIL GAMBAR
                            </label>
                            <input name="detail_gambar" value="<?php echo e(old('detail_gambar', $lpk->detail_gambar)); ?>" type="text"
                                maxlength="270" id="detail_gambar_input"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Keterangan detail gambar (opsional)">
                            <div class="flex justify-between items-center mt-1">
                                <div class="text-xs text-gray-500">
                                    <span id="detail_gambar_count">0</span>/270 karakter
                                </div>
                            </div>
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

                        <?php $__env->startPush('scripts'); ?>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const input = document.getElementById('gambar-input');
                                    const previewContainer = document.getElementById('gambar-preview');
                                    const previewImg = document.getElementById('gambar-preview-img');

                                    if (!input) return;

                                    input.addEventListener('change', function (e) {
                                        const file = this.files && this.files[0];
                                        if (!file) {
                                            if (previewContainer) previewContainer.classList.add('hidden');
                                            if (previewImg) previewImg.src = '#';
                                            return;
                                        }
                                        const reader = new FileReader();
                                        reader.onload = function (ev) {
                                            if (previewImg) previewImg.src = ev.target.result;
                                            if (previewContainer) previewContainer.classList.remove('hidden');
                                        }
                                        reader.readAsDataURL(file);
                                    });

                                    // Tombol hapus gambar utama
                                    const removeMainImageBtn = document.getElementById('remove-main-image');
                                    if (removeMainImageBtn && input && previewContainer && previewImg) {
                                        removeMainImageBtn.addEventListener('click', function () {
                                            input.value = '';
                                            previewContainer.classList.add('hidden');
                                            previewImg.src = '#';
                                        });
                                    }

                                    // Character counter for detail gambar (270 chars)
                                    const detailGambarInput = document.getElementById('detail_gambar_input');
                                    const detailGambarCount = document.getElementById('detail_gambar_count');
                                    if (detailGambarInput && detailGambarCount) {
                                        // Update initial count
                                        detailGambarCount.textContent = detailGambarInput.value.length;

                                        detailGambarInput.addEventListener('input', function () {
                                            detailGambarCount.textContent = this.value.length;
                                            if (this.value.length >= 250) {
                                                detailGambarCount.parentElement.classList.add('text-orange-500');
                                            } else {
                                                detailGambarCount.parentElement.classList.remove('text-orange-500');
                                            }
                                            if (this.value.length >= 270) {
                                                detailGambarCount.parentElement.classList.add('text-red-500');
                                                detailGambarCount.parentElement.classList.remove('text-orange-500');
                                            } else {
                                                detailGambarCount.parentElement.classList.remove('text-red-500');
                                            }
                                        });
                                    }

                                    // Character counter for problem (150 chars)
                                    const problemInput = document.getElementById('problem_input');
                                    const problemCount = document.getElementById('problem_count');
                                    if (problemInput && problemCount) {
                                        // Update initial count
                                        problemCount.textContent = problemInput.value.length;

                                        problemInput.addEventListener('input', function () {
                                            problemCount.textContent = this.value.length;
                                            if (this.value.length >= 130) {
                                                problemCount.parentElement.classList.add('text-orange-500');
                                            } else {
                                                problemCount.parentElement.classList.remove('text-orange-500');
                                            }
                                            if (this.value.length >= 150) {
                                                problemCount.parentElement.classList.add('text-red-500');
                                                problemCount.parentElement.classList.remove('text-orange-500');
                                            } else {
                                                problemCount.parentElement.classList.remove('text-red-500');
                                            }
                                        });
                                    }
                                });
                            </script>
                        <?php $__env->stopPush(); ?>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                PROBLEM <span class="text-red-500">*</span>
                            </label>
                            <input name="problem" value="<?php echo e(old('problem', $lpk->problem)); ?>" required type="text"
                                maxlength="150" id="problem_input"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Deskripsikan problem singkat">
                            <div class="flex justify-between items-center mt-1">
                                <div class="text-xs text-gray-500">
                                    <span id="problem_count">0</span>/150 karakter
                                </div>
                            </div>
                            <?php $__errorArgs = ['problem'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="problem">PROBLEM wajib
                                diisi</div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-1 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TOTAL CHECK <span class="text-red-500">*</span>
                                </label>
                                <input name="total_check" value="<?php echo e(old('total_check', $lpk->total_check)); ?>" required
                                    type="number" min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="TOTAL CHECK">
                                <?php $__errorArgs = ['total_check'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_check">TOTAL
                                    CHECK wajib diisi</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TOTAL NG <span class="text-red-500">*</span>
                                </label>
                                <input name="total_ng" value="<?php echo e(old('total_ng', $lpk->total_ng)); ?>" required type="number"
                                    min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="TOTAL NG">
                                <?php $__errorArgs = ['total_ng'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_ng">TOTAL NG
                                    wajib diisi</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TOTAL DELIVERY <span class="text-red-500">*</span>
                                </label>
                                <input name="total_delivery" value="<?php echo e(old('total_delivery', $lpk->total_delivery)); ?>"
                                    required type="number" min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="TOTAL DELIVERY">
                                <?php $__errorArgs = ['total_delivery'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_delivery">
                                    TOTAL DELIVERY wajib diisi</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-1 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TOTAL CLAIM <span class="text-red-500">*</span>
                                </label>
                                <input name="total_claim" value="<?php echo e(old('total_claim', $lpk->total_claim)); ?>" required
                                    type="number" min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="TOTAL CLAIM">
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
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_claim">TOTAL
                                    CLAIM wajib diisi</div>
                            </div>
                            <div>
                                <!-- Kolom percentage dihapus, auto-generate di JS -->
                            </div>
                        </div>

                        <div class="pt-4 border-t flex justify-end items-center">
                            <button type="button" id="submit-btn"
                                class="bg-red-600 text-white px-5 py-3 rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">SIMPAN
                                PERUBAHAN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                (function attachCalendar() {
                    const fields = [
                        { hidden: 'tgl_terbit_lka', display: 'tgl_terbit_lka_display' },
                        { hidden: 'tgl_terbit', display: 'tgl_terbit_display' },
                        { hidden: 'tgl_delivery', display: 'tgl_delivery_display' },
                    ];

                    function toDMY(iso) {
                        if (!iso) return '';
                        const m = iso.match(/^(\d{4})-(\d{2})-(\d{2})$/);
                        if (!m) return '';
                        return `${m[3]}-${m[2]}-${m[1]}`;
                    }

                    function bind(fp) {
                        fields.forEach(({ hidden, display }) => {
                            const h = document.getElementById(hidden);
                            const d = document.getElementById(display);
                            if (!h || !d) return;

                            // Ensure display shows dd-mm-yyyy if hidden ISO exists
                            if (!d.value && h.value) {
                                d.value = toDMY(h.value);
                            }

                            fp(d, {
                                dateFormat: 'd-m-Y',
                                allowInput: true,
                                // prefer to pass a display-friendly defaultDate
                                defaultDate: h.value ? toDMY(h.value) : undefined,
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

                    // Load local flatpickr assets from public/vendor/flatpickr if bundle isn't available
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.css")); ?>';
                    document.head.appendChild(link);
                    const s = document.createElement('script');
                    s.src = '<?php echo e(asset("vendor/flatpickr/flatpickr.min.js")); ?>';
                    s.onload = function () { bind(window.flatpickr); };
                    document.body.appendChild(s);
                })();

                const form = document.getElementById('lpk-form');
                const btn = document.getElementById('submit-btn');
                if (!form || !btn) return;

                // Date helpers for dd-mm-yyyy <-> yyyy-mm-dd
                function toISO(dmy) {
                    if (!dmy) return '';
                    const m = dmy.match(/^(\d{2})\-(\d{2})\-(\d{4})$/);
                    if (!m) return '';
                    const [_, dd, mm, yyyy] = m; // eslint-disable-line no-unused-vars
                    return `${yyyy}-${mm}-${dd}`;
                }
                function toDMY(iso) {
                    if (!iso) return '';
                    const m = iso.match(/^(\d{4})-(\d{2})-(\d{2})$/);
                    if (!m) return '';
                    const [_, yyyy, mm, dd] = m; // eslint-disable-line no-unused-vars
                    return `${dd}-${mm}-${yyyy}`;
                }

                // Live mask: auto-insert hyphens as dd-mm-yyyy while typing
                function maskDMY(value) {
                    const digits = (value || '').replace(/\D/g, '').slice(0, 8); // ddmmyyyy max
                    if (!digits) return '';
                    if (digits.length <= 2) return digits;
                    if (digits.length <= 4) return `${digits.slice(0, 2)}-${digits.slice(2)}`;
                    return `${digits.slice(0, 2)}-${digits.slice(2, 4)}-${digits.slice(4)}`;
                }

                // Wire visible display inputs with hidden ISO inputs
                const datePairs = [
                    { hidden: 'tgl_terbit_lka', display: 'tgl_terbit_lka_display' },
                    { hidden: 'tgl_terbit', display: 'tgl_terbit_display' },
                    { hidden: 'tgl_delivery', display: 'tgl_delivery_display' },
                ];

                // Ensure hidden values are synced from display before validation/submit
                function syncHiddenFromDisplay() {
                    datePairs.forEach(({ hidden, display }) => {
                        const h = document.getElementById(hidden);
                        const d = document.getElementById(display);
                        if (!h || !d) return;
                        const iso = toISO(d.value.trim());
                        if (iso) h.value = iso;
                    });
                }

                datePairs.forEach(({ hidden, display }) => {
                    const h = document.getElementById(hidden);
                    const d = document.getElementById(display);
                    if (!h || !d) return;
                    if (!d.value && h.value) d.value = toDMY(h.value);
                    // live input mask + hidden sync when complete
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

                // Image preview (edit form already has elements)
                const gambarInput = document.getElementById('gambar-input');
                const gambarPreview = document.getElementById('gambar-preview');
                const gambarPreviewImg = document.getElementById('gambar-preview-img');
                if (gambarInput) {
                    gambarInput.addEventListener('change', function () {
                        const file = this.files && this.files[0];
                        if (!file) {
                            if (gambarPreview) gambarPreview.classList.add('hidden');
                            if (gambarPreviewImg) gambarPreviewImg.src = '#';
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            if (gambarPreviewImg) gambarPreviewImg.src = e.target.result;
                            if (gambarPreview) gambarPreview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    });
                }

                // Function to validate individual field
                function validateField(field) {
                    let isValid = true;
                    const errorDiv = document.querySelector(`.error-message[data-field="${field.name}"]`);

                    // Remove existing error styling
                    field.classList.remove('border-red-500');
                    if (errorDiv) {
                        errorDiv.classList.add('hidden');
                    }

                    // Check if field is required and empty
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

                // Real-time validation and submit handling
                (function () {
                    const fields = form.querySelectorAll('input, select');
                    fields.forEach(field => {
                        field.addEventListener('input', function () { validateField(this); });
                        field.addEventListener('change', function () { validateField(this); });
                    });

                    btn.addEventListener('click', function () {
                        // sync date displays to hidden ISO inputs
                        syncHiddenFromDisplay();

                        // validate required fields
                        if (!validateAllFields()) {
                            // focus first invalid field
                            const firstInvalid = form.querySelector('.border-red-500');
                            if (firstInvalid && typeof firstInvalid.focus === 'function') firstInvalid.focus();
                            return;
                        }

                        // Use HTML5 validation as backup
                        if (typeof form.reportValidity === 'function') {
                            if (!form.reportValidity()) return;
                        } else if (!form.checkValidity()) {
                            return;
                        }

                        // Visual feedback and submit
                        btn.disabled = true;
                        const prev = btn.innerHTML;
                        btn.innerHTML = 'Submitting...';
                        btn.classList.add('opacity-50', 'cursor-not-allowed');

                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit();
                        } else {
                            form.submit();
                        }

                        setTimeout(() => {
                            btn.disabled = false;
                            btn.innerHTML = prev;
                            btn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }, 5000);
                    });
                })();

                // Toggle Customer PT Name input visibility
                const lokasiSelect = document.getElementById('lokasi_penemuan_claim');
                const customerPtWrapper = document.getElementById('customer_pt_name_wrapper');
                const customerPtInput = document.getElementById('customer_pt_name');

                function toggleCustomerPtField() {
                    if (lokasiSelect && customerPtWrapper && customerPtInput) {
                        if (lokasiSelect.value === 'Customer PT') {
                            customerPtWrapper.style.display = 'block';
                            customerPtInput.setAttribute('required', 'required');
                        } else {
                            customerPtWrapper.style.display = 'none';
                            customerPtInput.removeAttribute('required');
                            // Don't clear value on edit page, keep existing value
                        }
                    }
                }

                // Initialize on page load (untuk handle old value dan existing data)
                if (lokasiSelect) {
                    toggleCustomerPtField();
                    lokasiSelect.addEventListener('change', toggleCustomerPtField);
                }

            } catch (err) {
                console && console.error('Form validation script error', err);
            }
        });

        (function () {
            function createDropdown(rootId) {
                var root = document.getElementById(rootId);
                if (!root) return;

                var suppliersData = [];
                try { suppliersData = JSON.parse(root.getAttribute('data-suppliers') || '[]'); } catch (err) { suppliersData = []; }

                var hidden = root.querySelector('#nama_supply');
                var btn = root.querySelector('#nama_supply_btn');
                var label = root.querySelector('#nama_supply_label');
                var panel = root.querySelector('#nama_supply_panel');
                var search = root.querySelector('#nama_supply_panel_search');
                var list = root.querySelector('#nama_supply_list');

                function buildList(items, highlightIndex) {
                    list.innerHTML = '';
                    if (!items.length) {
                        var li = document.createElement('li');
                        li.className = 'px-3 py-2 text-sm text-gray-500';
                        li.textContent = 'Tidak ada supplier';
                        list.appendChild(li);
                        return;
                    }
                    items.forEach(function (txt, idx) {
                        var li = document.createElement('li');
                        li.setAttribute('role', 'option');
                        li.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100 text-sm';
                        li.textContent = txt;
                        li.dataset.index = idx;
                        if (idx === highlightIndex) li.classList.add('bg-gray-100');
                        li.addEventListener('click', function () {
                            selectItem(txt);
                            closePanel();
                            btn.focus();
                        });
                        list.appendChild(li);
                    });
                }

                function openPanel() { panel.classList.remove('hidden'); search.focus(); filterList(search.value || ''); }
                function closePanel() { panel.classList.add('hidden'); }
                function selectItem(text) { hidden.value = text; label.textContent = text; hidden.dispatchEvent(new Event('change', { bubbles: true })); }
                function filterList(q) { q = (q || '').toLowerCase(); var matches = suppliersData.filter(function (s) { return s.toLowerCase().indexOf(q) !== -1; }); buildList(matches); }

                btn.addEventListener('click', function (e) { e.preventDefault(); if (panel.classList.contains('hidden')) openPanel(); else closePanel(); });
                document.addEventListener('click', function (e) { if (!root.contains(e.target)) closePanel(); });
                search.addEventListener('input', function () { filterList(this.value); });

                var highlighted = -1;
                root.addEventListener('keydown', function (e) {
                    if (panel.classList.contains('hidden')) return;
                    var items = Array.from(list.querySelectorAll('li[role="option"]'));
                    if (!items.length) return;
                    if (e.key === 'ArrowDown') { highlighted = Math.min(highlighted + 1, items.length - 1); items.forEach(function (it, i) { it.classList.toggle('bg-gray-100', i === highlighted); }); items[highlighted].scrollIntoView({ block: 'nearest' }); e.preventDefault(); }
                    else if (e.key === 'ArrowUp') { highlighted = Math.max(highlighted - 1, 0); items.forEach(function (it, i) { it.classList.toggle('bg-gray-100', i === highlighted); }); items[highlighted].scrollIntoView({ block: 'nearest' }); e.preventDefault(); }
                    else if (e.key === 'Enter') { if (highlighted >= 0 && items[highlighted]) { items[highlighted].click(); } else { var v = search.value.trim(); if (v) { var m = suppliersData.find(function (s) { return s.toLowerCase() === v.toLowerCase() || s.toLowerCase().indexOf(v.toLowerCase()) !== -1; }); if (m) selectItem(m); } closePanel(); } e.preventDefault(); }
                    else if (e.key === 'Escape') { closePanel(); btn.focus(); }
                });

                buildList(suppliersData);
                if (hidden.value) { label.textContent = hidden.value; }
            }

            document.addEventListener('DOMContentLoaded', function () { createDropdown('supplier_dropdown'); });
        })();
        (function () {
            function createPartDropdown(rootId) {
                var root = document.getElementById(rootId);
                if (!root) return;

                var items = [];
                try { items = JSON.parse(root.getAttribute('data-items') || '[]'); } catch (err) { items = []; }

                var hiddenKode = root.querySelector('#nomor_part');
                var hiddenNama = root.querySelector('#nama_part');
                var btn = root.querySelector('#nomor_part_btn');
                var label = root.querySelector('#nomor_part_label');
                var panel = root.querySelector('#nomor_part_panel');
                var search = root.querySelector('#nomor_part_panel_search');
                var list = root.querySelector('#nomor_part_list');

                function buildList(filtered, highlightIndex) {
                    list.innerHTML = '';
                    if (!filtered.length) {
                        var li = document.createElement('li');
                        li.className = 'px-3 py-2 text-sm text-gray-500';
                        li.textContent = 'Tidak ada part';
                        list.appendChild(li);
                        return;
                    }
                    filtered.forEach(function (it, idx) {
                        var li = document.createElement('li');
                        li.setAttribute('role', 'option');
                        li.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100 text-sm';
                        li.textContent = it.kode + ' - ' + it.desc;
                        li.dataset.index = idx;
                        li.dataset.kode = it.kode;
                        li.dataset.desc = it.desc;
                        if (idx === highlightIndex) li.classList.add('bg-gray-100');
                        li.addEventListener('click', function () { selectItem(it); closePanel(); btn.focus(); });
                        list.appendChild(li);
                    });
                }

                function openPanel() { panel.classList.remove('hidden'); search.focus(); filterList(search.value || ''); }
                function closePanel() { panel.classList.add('hidden'); }
                function selectItem(it) { if (!it) return; if (hiddenKode) hiddenKode.value = it.kode; if (hiddenNama) hiddenNama.value = it.desc; if (label) label.textContent = it.kode + ' - ' + it.desc; try { hiddenKode && hiddenKode.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) { } try { hiddenNama && hiddenNama.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) { } }
                function filterList(q) { q = (q || '').toLowerCase().trim(); var matches = items.filter(function (i) { return i.kode.toLowerCase().indexOf(q) !== -1 || (i.desc && i.desc.toLowerCase().indexOf(q) !== -1); }); buildList(matches); }

                btn.addEventListener('click', function (e) { e.preventDefault(); if (panel.classList.contains('hidden')) openPanel(); else closePanel(); });
                document.addEventListener('click', function (e) { if (!root.contains(e.target)) closePanel(); });
                search.addEventListener('input', function () { filterList(this.value); });

                var highlighted = -1;
                root.addEventListener('keydown', function (e) {
                    if (panel.classList.contains('hidden')) return;
                    var opts = Array.from(list.querySelectorAll('li[role="option"]'));
                    if (!opts.length) return;
                    if (e.key === 'ArrowDown') { highlighted = Math.min(highlighted + 1, opts.length - 1); opts.forEach(function (it, i) { it.classList.toggle('bg-gray-100', i === highlighted); }); opts[highlighted].scrollIntoView({ block: 'nearest' }); e.preventDefault(); }
                    else if (e.key === 'ArrowUp') { highlighted = Math.max(highlighted - 1, 0); opts.forEach(function (it, i) { it.classList.toggle('bg-gray-100', i === highlighted); }); opts[highlighted].scrollIntoView({ block: 'nearest' }); e.preventDefault(); }
                    else if (e.key === 'Enter') { if (highlighted >= 0 && opts[highlighted]) { opts[highlighted].click(); } else { var v = search.value.trim(); if (v) { var m = items.find(function (s) { return s.kode.toLowerCase() === v.toLowerCase() || (s.desc && s.desc.toLowerCase().indexOf(v.toLowerCase()) !== -1); }); if (m) selectItem(m); } closePanel(); } e.preventDefault(); }
                    else if (e.key === 'Escape') { closePanel(); btn.focus(); }
                });

                buildList(items);

                if (hiddenKode && hiddenKode.value) {
                    var found = items.find(function (it) { return it.kode === hiddenKode.value; });
                    if (found) { if (hiddenNama) hiddenNama.value = found.desc; if (label) label.textContent = found.kode + ' - ' + found.desc; }
                }
            }

            document.addEventListener('DOMContentLoaded', function () { createPartDropdown('part_dropdown'); });
        })();
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/qc/lpk/edit.blade.php ENDPATH**/ ?>