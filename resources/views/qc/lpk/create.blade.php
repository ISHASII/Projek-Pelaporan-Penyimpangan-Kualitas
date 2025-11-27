@extends('layouts.navbar')

@section('content')
    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="px-6 py-4 rounded-none">
                <div class="max-w-screen-xl mx-auto flex items-center">
                    <h1 class="text-red-600 text-lg font-semibold">Create LPK</h1>
                </div>
            </div>

            <div class="max-w-screen-xl mx-auto px-6 py-6">
                <form action="{{ route('qc.lpk.store') }}" method="POST" enctype="multipart/form-data" id="lpk-form">
                    @csrf

                    <div class="flex items-center mb-8">
                        <a href="{{ route('qc.lpk.index') }}"
                            class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                            <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                            <span>Kembali</span>
                        </a>
                    </div>

                    <div class="form-compact">
                        <!-- Responsive Grid: 1 column on mobile, 2 columns on md+ -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">REFERENSI LKA <span
                                        class="text-red-500">*</span></label>
                                <input name="referensi_lka" value="{{ old('referensi_lka') }}" type="text" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Referensi LKA">
                                @error('referensi_lka')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="referensi_lka">
                                    Referensi LKA wajib diisi</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">TGL TERBIT LKA <span
                                        class="text-red-500">*</span></label>
                                <!-- Hidden ISO value for submit -->
                                <input type="hidden" name="tgl_terbit_lka" id="tgl_terbit_lka"
                                    value="{{ old('tgl_terbit_lka') ? (is_string(old('tgl_terbit_lka')) && strtotime(old('tgl_terbit_lka')) ? date('Y-m-d', strtotime(old('tgl_terbit_lka'))) : old('tgl_terbit_lka')) : '' }}"
                                    required>
                                <!-- Visible formatted input -->
                                <input type="text" id="tgl_terbit_lka_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="{{ old('tgl_terbit_lka') ? ((strpos(old('tgl_terbit_lka'), '-') !== false && strtotime(old('tgl_terbit_lka'))) ? date('d-m-Y', strtotime(old('tgl_terbit_lka'))) : str_replace('/', '-', old('tgl_terbit_lka'))) : '' }}">
                                @error('tgl_terbit_lka')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_terbit_lka">TGL
                                    TERBIT LKA wajib diisi</div>
                            </div>
                        </div>

                        <!-- Responsive Grid for dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TGL TERBIT LPK <span class="text-red-500">*</span>
                                </label>
                                <!-- Hidden ISO value for submit -->
                                <input type="hidden" name="tgl_terbit" id="tgl_terbit"
                                    value="{{ old('tgl_terbit') ? (is_string(old('tgl_terbit')) && strtotime(old('tgl_terbit')) ? date('Y-m-d', strtotime(old('tgl_terbit'))) : old('tgl_terbit')) : '' }}"
                                    required>
                                <!-- Visible formatted input -->
                                <input type="text" id="tgl_terbit_display" inputmode="numeric" pattern="\d{2}-\d{2}-\d{4}"
                                    placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="{{ old('tgl_terbit') ? ((strpos(old('tgl_terbit'), '-') !== false && strtotime(old('tgl_terbit'))) ? date('d-m-Y', strtotime(old('tgl_terbit'))) : str_replace('/', '-', old('tgl_terbit'))) : '' }}">
                                @error('tgl_terbit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_terbit">TGL
                                    TERBIT wajib diisi</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TGL DELIVERY <span class="text-red-500">*</span>
                                </label>

                                <input type="hidden" name="tgl_delivery" id="tgl_delivery"
                                    value="{{ old('tgl_delivery') ? (is_string(old('tgl_delivery')) && strtotime(old('tgl_delivery')) ? date('Y-m-d', strtotime(old('tgl_delivery'))) : old('tgl_delivery')) : '' }}"
                                    required>

                                <input type="text" id="tgl_delivery_display" inputmode="numeric" pattern="\d{2}-\d{2}-\d{4}"
                                    placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="{{ old('tgl_delivery') ? ((strpos(old('tgl_delivery'), '-') !== false && strtotime(old('tgl_delivery'))) ? date('d-m-Y', strtotime(old('tgl_delivery'))) : str_replace('/', '-', old('tgl_delivery'))) : '' }}">
                                @error('tgl_delivery')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_delivery">TGL
                                    DELIVERY wajib diisi</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    NAMA SUPPLIER <span class="text-red-500">*</span>
                                </label>

                                <div id="supplier_dropdown" class="relative"
                                    data-suppliers='@json(isset($suppliers) ? $suppliers->pluck("por_nama") : [])'>
                                    <input type="hidden" name="nama_supply" id="nama_supply"
                                        value="{{ old('nama_supply') }}">

                                    <button type="button" id="nama_supply_btn" aria-haspopup="listbox"
                                        class="w-full text-left border rounded-lg px-3 py-2 text-sm flex items-center justify-between bg-white">
                                        <span id="nama_supply_label"
                                            class="truncate text-sm text-gray-700">{{ old('nama_supply') ?: '-- Pilih Supplier --' }}</span>
                                        <img src="{{ asset('icon/dropdown.ico') }}" alt="dropdown" class="w-4 h-4">
                                    </button>

                                    <div id="nama_supply_panel"
                                        class="hidden absolute left-0 right-0 z-50 mt-1 bg-white border rounded shadow max-h-60 overflow-auto">
                                        <div class="p-2">
                                            <input type="text" id="nama_supply_panel_search"
                                                placeholder="Ketik untuk mencari supplier..."
                                                class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        </div>
                                        <ul id="nama_supply_list" role="listbox" tabindex="-1" class="divide-y"></ul>
                                    </div>
                                </div>

                                @error('nama_supply')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nama_supply">NAMA
                                    SUPPLY wajib diisi
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">NOMOR PART <span
                                        class="text-red-500">*</span></label>

                                <div id="part_dropdown" class="relative"
                                    data-items='@json(isset($items) ? $items->map(fn($i) => ["kode" => $i->kode, "desc" => $i->description]) : [])'>
                                    <input type="hidden" name="nomor_part" id="nomor_part" value="{{ old('nomor_part') }}">
                                    <input type="hidden" name="nama_part" id="nama_part" value="{{ old('nama_part') }}">

                                    <button type="button" id="nomor_part_btn" aria-haspopup="listbox"
                                        class="w-full text-left border rounded-lg px-3 py-2 text-sm flex items-center justify-between bg-white">
                                        <span id="nomor_part_label"
                                            class="truncate text-sm text-gray-700">{{ old('nomor_part') ? (old('nomor_part') . ' - ' . old('nama_part')) : '-- Pilih Nomor Part --' }}</span>
                                        <img src="{{ asset('icon/dropdown.ico') }}" alt="dropdown" class="w-4 h-4">
                                    </button>

                                    <div id="nomor_part_panel"
                                        class="hidden absolute left-0 right-0 z-50 mt-1 bg-white border rounded shadow max-h-60 overflow-auto">
                                        <div class="p-2">
                                            <input type="text" id="nomor_part_panel_search"
                                                placeholder="Ketik untuk mencari part (kode atau deskripsi)..."
                                                class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        </div>
                                        <ul id="nomor_part_list" role="listbox" tabindex="-1" class="divide-y"></ul>
                                    </div>
                                </div>

                                @error('nomor_part')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nomor_part">NOMOR
                                    PART wajib diisi</div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                NOMOR PO <span class="text-red-500">*</span>
                            </label>
                            <input name="nomor_po" value="{{ old('nomor_po') }}" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="NOMOR PO">

                            @error('nomor_po')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nomor_po">NOMOR PO wajib
                                diisi</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    STATUS LPK <span class="text-red-500">*</span>
                                </label>
                                <select name="status" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Claim" {{ old('status') == 'Claim' ? 'selected' : '' }}>Claim</option>
                                    <option value="Complaint (Informasi)" {{ in_array(old('status'), ['Complaint (Informasi)', 'Informasi']) ? 'selected' : '' }}>Complaint (Informasi)</option>
                                </select>

                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

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
                                    <option value="Quality" {{ old('jenis_ng') == 'Quality' ? 'selected' : '' }}>Quality
                                    </option>
                                    <option value="Delivery" {{ old('jenis_ng') == 'Delivery' ? 'selected' : '' }}>Delivery
                                    </option>
                                </select>

                                @error('jenis_ng')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="jenis_ng">JENIS LPK
                                    wajib dipilih</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:gap-4 mb-6">
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">KATEGORI <span
                                        class="text-red-500">*</span></label>
                                <select name="kategori" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Qty Kurang" {{ old('kategori') == 'Qty Kurang' ? 'selected' : '' }}>Qty
                                        Kurang</option>
                                    <option value="Subcont Prod" {{ old('kategori') == 'Subcont Prod' ? 'selected' : '' }}>
                                        Subcont Prod</option>
                                    <option value="Part Repair" {{ old('kategori') == 'Part Repair' ? 'selected' : '' }}>
                                        Part Repair</option>
                                    <option value="Reject Process" {{ old('kategori') == 'Reject Process' ? 'selected' : '' }}>Reject Process</option>
                                    <option value="Salah Barang/Label" {{ old('kategori') == 'Salah Barang/Label' ? 'selected' : '' }}>Salah Barang/Label
                                    </option>
                                </select>

                                @error('kategori')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">PERLAKUAN TERHADAP PART <span
                                        class="text-red-500">*</span></label>
                                <select name="perlakuan_terhadap_part" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Sortir Oleh Customer" {{ old('perlakuan_terhadap_part') == 'Sortir Oleh Customer' ? 'selected' : '' }}>
                                        Sortir Oleh Customer</option>
                                    <option value="Sortir Oleh Supplier" {{ old('perlakuan_terhadap_part') == 'Sortir Oleh Supplier' ? 'selected' : '' }}>
                                        Sortir Oleh Supplier</option>
                                    <option value="Sortir PT KYBI" {{ old('perlakuan_terhadap_part') == 'Sortir PT KYBI' ? 'selected' : '' }}>Sortir PT
                                        KYBI</option>
                                    <option value="Part Tetap Dipakai" {{ old('perlakuan_terhadap_part') == 'Part Tetap Dipakai' ? 'selected' : '' }}>Part
                                        Tetap Dipakai</option>
                                </select>

                                @error('perlakuan_terhadap_part')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="perlakuan_terhadap_part">Perlakuan Terhadap Part wajib dipilih</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">FREKUENSI CLAIM <span
                                        class="text-red-500">*</span></label>
                                <select name="frekuensi_claim" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Pertama Kali" {{ old('frekuensi_claim') == 'Pertama Kali' ? 'selected' : '' }}>Pertama Kali
                                    </option>
                                    <option value="Berulang Kali atau Rutin" {{ old('frekuensi_claim') == 'Berulang Kali atau Rutin' ? 'selected' : '' }}>Berulang
                                        Kali atau Rutin</option>
                                </select>

                                @error('frekuensi_claim')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

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
                                    <option value="Direpair Supplier" {{ old('perlakuan_part_defect') == 'Direpair Supplier' ? 'selected' : '' }}>Direpair
                                        Supplier</option>
                                    <option value="Replace" {{ old('perlakuan_part_defect') == 'Replace' ? 'selected' : '' }}>
                                        Replace</option>
                                    <option value="Dikembalikan ke Supplier" {{ old('perlakuan_part_defect') == 'Dikembalikan ke Supplier' ? 'selected' : '' }}>
                                        Dikembalikan ke Supplier</option>
                                    <option value="Discrap di PT KYBI" {{ old('perlakuan_part_defect') == 'Discrap di PT KYBI' ? 'selected' : '' }}>Discrap
                                        di PT KYBI</option>
                                </select>

                                @error('perlakuan_part_defect')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="perlakuan_part_defect">Perlakuan Part Defect wajib dipilih</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">LOKASI PENEMUAN CLAIM <span
                                        class="text-red-500">*</span></label>
                                <select name="lokasi_penemuan_claim" id="lokasi_penemuan_claim" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Receiving Insp" {{ old('lokasi_penemuan_claim') == 'Receiving Insp' ? 'selected' : '' }}>Receiving
                                        Insp</option>
                                    <option value="In-Proses" {{ old('lokasi_penemuan_claim') == 'In-Proses' ? 'selected' : '' }}>In-Proses
                                    </option>
                                    <option value="Customer PT" {{ old('lokasi_penemuan_claim') == 'Customer PT' ? 'selected' : '' }}>Customer PT
                                    </option>
                                </select>

                                @error('lokasi_penemuan_claim')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="lokasi_penemuan_claim">Lokasi Penemuan Claim wajib dipilih</div>
                            </div>

                            <!-- Input untuk Nama Customer PT (muncul jika Customer PT dipilih) -->
                            <div id="customer_pt_name_wrapper" style="display: none;">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">NAMA CUSTOMER PT <span
                                        class="text-red-500">*</span></label>
                                <input name="customer_pt_name" id="customer_pt_name" value="{{ old('customer_pt_name') }}"
                                    type="text"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Masukkan Nama Customer PT">

                                @error('customer_pt_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

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
                                <option value="Bisa Repair" {{ old('status_repair') == 'Bisa Repair' ? 'selected' : '' }}>
                                    Bisa Repair</option>
                                <option value="Tidak Repair" {{ old('status_repair') == 'Tidak Repair' ? 'selected' : '' }}>
                                    Tidak Repair</option>
                            </select>

                            @error('status_repair')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="status_repair">Status
                                Part Claim wajib dipilih</div>
                        </div>

                        <div class="mt-4 mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                INPUT GAMBAR <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="gambar" id="gambar-input" required
                                class="w-full text-sm border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
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

                            @error('gambar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="gambar">GAMBAR wajib
                                diupload</div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-3">
                                DETAIL GAMBAR
                            </label>

                            <div>
                                <input name="detail_gambar" value="{{ old('detail_gambar') }}" type="text" maxlength="270"
                                    id="detail_gambar_input"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Keterangan detail gambar (opsional)">
                                <div class="flex justify-between items-center mt-1">
                                    <div class="text-xs text-gray-500">
                                        <span id="detail_gambar_count">0</span>/270 karakter
                                    </div>
                                </div>
                                @error('detail_gambar')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                PROBLEM <span class="text-red-500">*</span>
                            </label>
                            <input name="problem" value="{{ old('problem') }}" required type="text" maxlength="150"
                                id="problem_input"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Deskripsikan problem singkat">
                            <div class="flex justify-between items-center mt-1">
                                <div class="text-xs text-gray-500">
                                    <span id="problem_count">0</span>/150 karakter
                                </div>
                            </div>

                            @error('problem')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="problem">PROBLEM wajib
                                diisi
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-1 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TOTAL CHECK <span class="text-red-500">*</span>
                                </label>
                                <input name="total_check" value="{{ old('total_check') }}" required type="number" min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="TOTAL CHECK">
                                @error('total_check')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_check">TOTAL
                                    CHECK wajib diisi</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TOTAL NG <span class="text-red-500">*</span>
                                </label>
                                <input name="total_ng" value="{{ old('total_ng') }}" required type="number" min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="TOTAL NG">
                                @error('total_ng')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_ng">TOTAL NG
                                    wajib diisi</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TOTAL DELIVERY <span class="text-red-500">*</span>
                                </label>
                                <input name="total_delivery" value="{{ old('total_delivery') }}" required type="number"
                                    min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="TOTAL DELIVERY">
                                @error('total_delivery')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_delivery">
                                    TOTAL DELIVERY wajib diisi</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-1 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    TOTAL CLAIM <span class="text-red-500">*</span>
                                </label>
                                <input name="total_claim" value="{{ old('total_claim') }}" required type="number" min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="TOTAL CLAIM">
                                @error('total_claim')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="total_claim">TOTAL
                                    CLAIM wajib diisi</div>
                            </div>

                        </div>


                        <div class="pt-4 border-t flex justify-end items-center">
                            <button type="button" id="force-submit-btn"
                                class="bg-red-600 text-white px-5 py-3 rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">Create
                                LPK</button>
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
                    function bind(fp) {
                        fields.forEach(({ hidden, display }) => {
                            const h = document.getElementById(hidden);
                            const d = document.getElementById(display);
                            if (!h || !d) return;
                            fp(d, {
                                dateFormat: 'd-m-Y',
                                allowInput: true,
                                defaultDate: h.value ? h.value : undefined,
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
                    link.href = '{{ asset("vendor/flatpickr/flatpickr.min.css") }}';
                    document.head.appendChild(link);
                    const s = document.createElement('script');
                    s.src = '{{ asset("vendor/flatpickr/flatpickr.min.js") }}';
                    s.onload = function () {
                        if (window.flatpickr) {
                            bind(window.flatpickr);
                        } else {
                            console && console.error('flatpickr failed to initialize from local asset.');
                        }
                    };
                    document.body.appendChild(s);
                })();

                const form = document.getElementById('lpk-form');
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
                    { hidden: 'tgl_terbit_lka', display: 'tgl_terbit_lka_display' },
                    { hidden: 'tgl_terbit', display: 'tgl_terbit_display' },
                    { hidden: 'tgl_delivery', display: 'tgl_delivery_display' },
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

                    const removeMainImageBtn = document.getElementById('remove-main-image');
                    if (removeMainImageBtn) {
                        removeMainImageBtn.addEventListener('click', function () {
                            try {

                                gambarInput.value = '';
                            } catch (e) {

                            }

                            if (gambarPreview) gambarPreview.classList.add('hidden');
                            if (gambarPreviewImg) gambarPreviewImg.src = '#';

                            try { gambarInput.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) { }
                        });
                    }
                }

                const detailGambarInput = document.getElementById('detail_gambar_input');
                const detailGambarCount = document.getElementById('detail_gambar_count');
                if (detailGambarInput && detailGambarCount) {

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
                            customerPtInput.value = ''; // Clear value when hidden
                        }
                    }
                }

                // Initialize on page load (untuk handle old value)
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
                try {
                    suppliersData = JSON.parse(root.getAttribute('data-suppliers') || '[]');
                } catch (err) { suppliersData = []; }

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

                function openPanel() {
                    panel.classList.remove('hidden');
                    search.focus();
                    filterList(search.value || '');
                }

                function closePanel() {
                    panel.classList.add('hidden');
                }

                function selectItem(text) {
                    hidden.value = text;
                    label.textContent = text;
                    var ev = new Event('change', { bubbles: true });
                    hidden.dispatchEvent(ev);
                }

                function filterList(q) {
                    q = (q || '').toLowerCase();
                    var matches = suppliersData.filter(function (s) { return s.toLowerCase().indexOf(q) !== -1; });
                    buildList(matches);
                }

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (panel.classList.contains('hidden')) openPanel(); else closePanel();
                });

                // Click outside to close
                document.addEventListener('click', function (e) {
                    if (!root.contains(e.target)) closePanel();
                });

                // Search input
                search.addEventListener('input', function () { filterList(this.value); });

                // Keyboard support inside panel
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

                // Initialize list
                buildList(suppliersData);

                // Pre-select existing value if present
                if (hidden.value) {
                    label.textContent = hidden.value;
                }
            }

            document.addEventListener('DOMContentLoaded', function () { createDropdown('supplier_dropdown'); });
        })();
    </script>
    <script>
        (function () {
            function createPartDropdown(rootId) {
                var root = document.getElementById(rootId);
                if (!root) return;

                var items = [];
                try {
                    items = JSON.parse(root.getAttribute('data-items') || '[]');
                } catch (err) { items = []; }

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
                        li.addEventListener('click', function () {
                            selectItem(it);
                            closePanel();
                            btn.focus();
                        });
                        list.appendChild(li);
                    });
                }

                function openPanel() { panel.classList.remove('hidden'); search.focus(); filterList(search.value || ''); }
                function closePanel() { panel.classList.add('hidden'); }

                function selectItem(it) {
                    if (!it) return;
                    if (hiddenKode) hiddenKode.value = it.kode;
                    if (hiddenNama) hiddenNama.value = it.desc;
                    if (label) label.textContent = it.kode + ' - ' + it.desc;
                    // dispatch change events for potential listeners
                    try { hiddenKode && hiddenKode.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) { }
                    try { hiddenNama && hiddenNama.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) { }
                }

                function filterList(q) {
                    q = (q || '').toLowerCase().trim();
                    var matches = items.filter(function (i) {
                        return i.kode.toLowerCase().indexOf(q) !== -1 || (i.desc && i.desc.toLowerCase().indexOf(q) !== -1);
                    });
                    buildList(matches);
                }

                btn.addEventListener('click', function (e) { e.preventDefault(); if (panel.classList.contains('hidden')) openPanel(); else closePanel(); });

                document.addEventListener('click', function (e) { if (!root.contains(e.target)) closePanel(); });

                search.addEventListener('input', function () { filterList(this.value); });

                // Keyboard navigation
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

                // Pre-select existing values
                if (hiddenKode && hiddenKode.value) {
                    var found = items.find(function (it) { return it.kode === hiddenKode.value; });
                    if (found) {
                        if (hiddenNama) hiddenNama.value = found.desc;
                        if (label) label.textContent = found.kode + ' - ' + found.desc;
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () { createPartDropdown('part_dropdown'); });
        })();
    </script>
    <script>
        // Submit handler: enable the Create button to validate and submit the form
        document.addEventListener('DOMContentLoaded', function () {
            try {
                const form = document.getElementById('lpk-form');
                const btn = document.getElementById('force-submit-btn');
                if (!form || !btn) return;

                function toISO(dmy) {
                    if (!dmy) return '';
                    const m = dmy.match(/^(\d{2})\-(\d{2})\-(\d{4})$/);
                    if (!m) return '';
                    const [, dd, mm, yyyy] = m;
                    return `${yyyy}-${mm}-${dd}`;
                }

                function validateField(field) {
                    let isValid = true;
                    const errorDiv = document.querySelector(`.error-message[data-field="${field.name}"]`);
                    field.classList.remove('border-red-500');
                    if (errorDiv) errorDiv.classList.add('hidden');

                    if (field.hasAttribute('required')) {
                        if (field.type === 'file') {
                            isValid = field.files && field.files.length > 0;
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

                // Check nama_supply matches supplier list
                function namaSupplyIsValid() {
                    const supplierRoot = document.getElementById('supplier_dropdown');
                    const hidden = document.getElementById('nama_supply');
                    if (!hidden) return false;
                    const val = (hidden.value || '').trim();
                    if (!val) return false;
                    try {
                        const list = JSON.parse(supplierRoot.getAttribute('data-suppliers') || '[]');
                        return list.some(s => s.toLowerCase() === val.toLowerCase());
                    } catch (e) { return true; }
                }

                function syncHiddenDates() {
                    const pairs = [
                        { hidden: 'tgl_terbit_lka', display: 'tgl_terbit_lka_display' },
                        { hidden: 'tgl_terbit', display: 'tgl_terbit_display' },
                        { hidden: 'tgl_delivery', display: 'tgl_delivery_display' },
                    ];
                    pairs.forEach(({ hidden, display }) => {
                        const h = document.getElementById(hidden);
                        const d = document.getElementById(display);
                        if (!h || !d) return;
                        const iso = toISO(d.value.trim());
                        if (iso) h.value = iso;
                    });
                }

                btn.addEventListener('click', function () {
                    try {
                        // sync date fields
                        syncHiddenDates();

                        // basic validation
                        if (!validateAllFields()) {
                            const firstInvalid = form.querySelector('.border-red-500');
                            if (firstInvalid && typeof firstInvalid.focus === 'function') firstInvalid.focus();
                            return;
                        }

                        // ensure supplier chosen
                        if (!namaSupplyIsValid()) {
                            const supplierBtn = document.getElementById('nama_supply_btn');
                            const err = document.querySelector('.error-message[data-field="nama_supply"]');
                            if (err) err.classList.remove('hidden');
                            if (supplierBtn && typeof supplierBtn.focus === 'function') supplierBtn.focus();
                            return;
                        }

                        // final HTML5 validation
                        if (typeof form.reportValidity === 'function') {
                            if (!form.reportValidity()) return;
                        } else if (!form.checkValidity()) {
                            return;
                        }

                        btn.disabled = true;
                        const prev = btn.innerHTML;
                        btn.innerHTML = 'Submitting...';
                        btn.classList.add('opacity-50', 'cursor-not-allowed');

                        if (typeof form.requestSubmit === 'function') form.requestSubmit(); else form.submit();

                        setTimeout(() => {
                            btn.disabled = false;
                            btn.innerHTML = prev;
                            btn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }, 5000);
                    } catch (err) {
                        console && console.error('submit handler error', err);
                    }
                });
            } catch (err) {
                console && console.error('Create page submit script error', err);
            }
        });
    </script>
@endsection