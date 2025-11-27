@extends('layouts.navbar')

@section(section: 'content')
    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="px-6 py-4 rounded-none">
                <div class="max-w-screen-xl mx-auto flex items-center">
                    <h1 class="text-red-600 text-lg font-semibold">Edit CMR</h1>
                </div>
            </div>

            <div class="max-w-screen-xl mx-auto px-6 py-6">
                <form action="{{ route('qc.cmr.update', $cmr->id) }}" method="POST" enctype="multipart/form-data"
                    id="cmr-form">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center mb-8">
                        <a href="{{ route('qc.cmr.index') }}"
                            class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                            <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                            <span>Back</span>
                        </a>
                    </div>

                    <div class="form-compact">
                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">NO REG</label>
                            <input type="text" id="no_reg_display" value="{{ $cmr->no_reg ?? '-' }}" readonly
                                class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100 text-gray-600 cursor-not-allowed">
                            <input type="hidden" name="no_reg" value="{{ $cmr->no_reg }}">
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">CMR ISSUE DATE (発行日)<span
                                        class="text-red-500">*</span></label>
                                <input type="hidden" name="tgl_terbit_cmr" id="tgl_terbit_cmr"
                                    value="{{ $cmr->tgl_terbit_cmr ? \Carbon\Carbon::parse($cmr->tgl_terbit_cmr)->format('Y-m-d') : '' }}"
                                    required>
                                <input type="text" name="tgl_terbit_cmr_display" id="tgl_terbit_cmr_display"
                                    inputmode="numeric" pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="{{ $cmr->tgl_terbit_cmr ? \Carbon\Carbon::parse($cmr->tgl_terbit_cmr)->format('d-m-Y') : '' }}">
                                @error('tgl_terbit_cmr')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_terbit_cmr">CMR
                                    ISSUE DATE is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">DELIVERY DATE (配達日)<span
                                        class="text-red-500">*</span></label>
                                <input type="hidden" name="tgl_delivery" id="tgl_delivery"
                                    value="{{ $cmr->tgl_delivery ? \Carbon\Carbon::parse($cmr->tgl_delivery)->format('Y-m-d') : '' }}"
                                    required>
                                <input type="text" name="tgl_delivery_display" id="tgl_delivery_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="{{ $cmr->tgl_delivery ? \Carbon\Carbon::parse($cmr->tgl_delivery)->format('d-m-Y') : '' }}">
                                @error('tgl_delivery')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="tgl_delivery">
                                    DELIVERY DATE is required</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">B/L date (船積日)</label>
                                <input type="hidden" name="bl_date" id="bl_date"
                                    value="{{ $cmr->bl_date ? \Carbon\Carbon::parse($cmr->bl_date)->format('Y-m-d') : '' }}">
                                <input type="text" name="bl_date_display" id="bl_date_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="{{ $cmr->bl_date ? \Carbon\Carbon::parse($cmr->bl_date)->format('d-m-Y') : '' }}">
                                @error('bl_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">A/R Date (到着日)</label>
                                <input type="hidden" name="ar_date" id="ar_date"
                                    value="{{ $cmr->ar_date ? \Carbon\Carbon::parse($cmr->ar_date)->format('Y-m-d') : '' }}">
                                <input type="text" name="ar_date_display" id="ar_date_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="{{ $cmr->ar_date ? \Carbon\Carbon::parse($cmr->ar_date)->format('d-m-Y') : '' }}">
                                @error('ar_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Found Date (発見日)</label>
                                <input type="hidden" name="found_date" id="found_date"
                                    value="{{ $cmr->found_date ? \Carbon\Carbon::parse($cmr->found_date)->format('Y-m-d') : '' }}">
                                <input type="text" name="found_date_display" id="found_date_display" inputmode="numeric"
                                    pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    value="{{ $cmr->found_date ? \Carbon\Carbon::parse($cmr->found_date)->format('d-m-Y') : '' }}">
                                @error('found_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">SUPPLIER NAME (サプライヤ名) <span
                                        class="text-red-500">*</span></label>
                                <div id="supplier_dropdown" class="relative"
                                    data-suppliers='@json($suppliers->pluck("por_nama"))'>
                                    <input type="hidden" name="nama_supplier"
                                        value="{{ old('nama_supplier', $cmr->nama_supplier) }}" required>
                                    <button type="button" id="nama_supplier_btn"
                                        class="w-full text-left border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <span>{{ old('nama_supplier', $cmr->nama_supplier) ? old('nama_supplier', $cmr->nama_supplier) : 'Pilih Supplier' }}</span>
                                    </button>
                                    <div id="nama_supplier_panel"
                                        class="hidden absolute z-10 bg-white border rounded mt-1 w-full shadow-lg p-2">
                                        <input type="text" placeholder="Cari supplier..."
                                            class="w-full border rounded px-3 py-2 text-sm mb-2" />
                                        <div id="nama_supplier_list" class="max-h-40 overflow-auto"></div>
                                    </div>
                                </div>
                                @error('nama_supplier')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nama_supplier">
                                    SUPPLIER NAME is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">PART (部品)<span
                                        class="text-red-500">*</span></label>
                                <div id="part_dropdown" class="relative" data-items='@json($items->map(function ($i) {
                                    return ["kode" => $i->kode, "desc" => $i->description];
                                }))'>
                                    <input type="hidden" name="nomor_part"
                                        value="{{ old('nomor_part', $cmr->nomor_part) }}">
                                    <input type="hidden" name="nama_part" value="{{ old('nama_part', $cmr->nama_part) }}"
                                        required>
                                    <button type="button" id="nomor_part_btn"
                                        class="w-full text-left border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <span>{{ old('nomor_part', $cmr->nomor_part) ? (old('nomor_part', $cmr->nomor_part) . ' — ' . old('nama_part', $cmr->nama_part)) : 'Pilih Part' }}</span>
                                    </button>
                                    <div id="nomor_part_panel"
                                        class="hidden absolute z-10 bg-white border rounded mt-1 w-full shadow-lg p-2">
                                        <input type="text" placeholder="Cari nomor part atau nama part..."
                                            class="w-full border rounded px-3 py-2 text-sm mb-2" />
                                        <div id="nomor_part_list" class="max-h-40 overflow-auto"></div>
                                    </div>
                                </div>
                                @error('nama_part')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nama_part">PART NAME
                                    is required</div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">PO NUMBER (注文番号)<span
                                    class="text-red-500">*</span></label>
                            <input name="nomor_po" value="{{ $cmr->order_no }}" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="PO NUMBER">
                            @error('nomor_po')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="nomor_po">PO NUMBER is
                                required</div>
                        </div>

                        <!-- PART NUMBER is handled by the searchable part dropdown above -->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">INVOICE NO (請求書番号)<span
                                        class="text-red-500">*</span></label>
                                <input name="invoice_no" value="{{ $cmr->invoice_no }}" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="INVOICE NO">
                                @error('invoice_no')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="invoice_no">INVOICE
                                    NO is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">ORDER NO (|オーダーNo.) <span
                                        class="text-red-500">*</span></label>
                                <input name="order_no" value="{{ $cmr->order_no }}" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="ORDER NO">
                                @error('order_no')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
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
                                    <option value="SKA" {{ $cmr->product == 'SKA' ? 'selected' : '' }}>SKA</option>
                                    <option value="FF" {{ $cmr->product == 'FF' ? 'selected' : '' }}>FF</option>
                                    <option value="OCU" {{ $cmr->product == 'OCU' ? 'selected' : '' }}>OCU</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="product">PRODUCT is
                                    required</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">MODEL (模型)</label>
                                <input name="model" value="{{ old('model', $cmr->model) }}"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Model">
                                @error('model')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>

                                <div class="mb-6">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">CRATE NUMBER (箱番号)</label>
                                    <input name="crate_number" value="{{ old('crate_number', $cmr->crate_number) }}"
                                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        placeholder="CRATE NUMBER">
                                    @error('crate_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">LOCATION CLAIM OCCUR
                                    (クレーム発生場所) <span class="text-red-500">*</span></label>
                                <select name="location_claim_occurrence" id="lokasi_penemuan_claim" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">--Choose --</option>
                                    <option value="Receiving Inspect" {{ $cmr->location_claim_occurrence == 'Receiving Inspect' ? 'selected' : '' }}>Receiving Inspect (受入检查)</option>
                                    <option value="In-Process" {{ $cmr->location_claim_occurrence == 'In-Process' ? 'selected' : '' }}>In-Process (工程内)</option>
                                    <option value="Customer" {{ $cmr->location_claim_occurrence == 'Customer' ? 'selected' : '' }}>Customer (客先)</option>
                                </select>
                                @error('location_claim_occurrence')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="location_claim_occurrence">LOCATION CLAIM OCCUR is required</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">DISPOSITION OF INVENTORY TYPE
                                    (在庫品处理) <span class="text-red-500">*</span></label>
                                <select name="disposition_inventory_type" id="disposition_inventory_type" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">--Choose --</option>
                                    <option value="AT CUSTOMER" {{ $cmr->disposition_inventory_type == 'AT CUSTOMER' ? 'selected' : '' }}>AT CUSTOMER (客先にて)</option>
                                    <option value="AT PT.KYBI" {{ $cmr->disposition_inventory_type == 'AT PT.KYBI' ? 'selected' : '' }}>AT PT.KYBI (PT KYB にて)</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="disposition_inventory_type">DISPOSITION OF INVENTORY TYPE is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">DISPOSITION INVENTORY CHOICE
                                    (在庫品处理) <span class="text-red-500">*</span></label>
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
                                    <option value="First time" {{ $cmr->claim_occurrence_frequency == 'First time' ? 'selected' : '' }}>First time (初回)</option>
                                    <option value="Reoccurred" {{ $cmr->claim_occurrence_frequency == 'Reoccurred' ? 'selected' : '' }}>Reoccurred (再発)</option>
                                    <option value="Intermittently" {{ $cmr->claim_occurrence_frequency == 'Intermittently' ? 'selected' : '' }}>Intermittently (断統的)</option>
                                    <option value="Continuously" {{ $cmr->claim_occurrence_frequency == 'Continuously' ? 'selected' : '' }}>Continuously (總統的)</option>
                                    <option value="Other" {{ $cmr->claim_occurrence_frequency == 'Other' ? 'selected' : '' }}>
                                        Other (その他)</option>
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
                                    <option value="Dispatch with this report" {{ $cmr->dispatch_defective_parts == 'Dispatch with this report' ? 'selected' : '' }}>Dispatch with this report (本レポートと共に送付)
                                    </option>
                                    <option value="Dispatch separately" {{ $cmr->dispatch_defective_parts == 'Dispatch separately' ? 'selected' : '' }}>Dispatch separately (別途送付)</option>
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
                                    <option value="Keep to use" {{ $cmr->disposition_defect_parts == 'Keep to use' ? 'selected' : '' }}>Keep to use (總統使用)</option>
                                    <option value="Return to KYB" {{ $cmr->disposition_defect_parts == 'Return to KYB' ? 'selected' : '' }}>Return to KYB (KYB 返却)</option>
                                    <option value="Scrapped at PT.KYB" {{ $cmr->disposition_defect_parts == 'Scrapped at PT.KYB' ? 'selected' : '' }}>Scrapped at PT.KYB (PTKYB にて廃却)</option>
                                </select>
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="disposition_defect_parts">DISPOSITION OF DEFECT PARTS is required</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">QTY ORDER (注文数量)<span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="qty_order" value="{{ old('qty_order', $cmr->qty_order) }}"
                                    required min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="QTY ORDER">
                                @error('qty_order')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="qty_order">QTY ORDER
                                    is required</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">QTY DELIVERED (納品数量)<span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="qty_deliv" value="{{ $cmr->qty_deliv }}" required min="0"
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
                                <input type="number" name="qty_problem" value="{{ $cmr->qty_problem }}" required min="0"
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="QTY PROBLEM">
                                <div class="error-message text-red-500 text-xs mt-1 hidden" data-field="qty_problem">QTY
                                    PROBLEM is required</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">IMAGE INPUT (画像入力)</label>
                                <input type="file" name="gambar" id="gambar-input" accept="image/*"
                                    class="w-full text-sm border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                @if($cmr->gambar)
                                    @php
                                        $imgSrc = $cmr->gambar;
                                        if ($imgSrc && strpos($imgSrc, '/storage/') !== 0) {
                                            $imgSrc = asset('storage/' . ltrim($imgSrc, '/'));
                                        }
                                    @endphp

                                    <div id="existing-image" class="mt-2 relative inline-block">
                                        <img src="{{ $imgSrc }}" alt="current"
                                            class="w-32 h-24 object-cover border-2 border-gray-300 rounded-lg shadow-sm">
                                        <button type="button" id="remove-existing-image"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 focus:outline-none">×</button>
                                    </div>
                                @endif

                                <input type="hidden" name="remove_gambar" id="remove_gambar" value="0">
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
                            <input name="input_problem" id="input_problem" value="{{ $cmr->input_problem }}" type="text"
                                maxlength="75"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Describe problem (max 75 characters)">
                            <div class="text-right text-xs text-gray-500 mt-1">
                                <span id="input_problem_counter">0</span>/75 karakter
                            </div>
                        </div>

                        <div class="pt-4 border-t flex justify-end items-center">
                            <button type="button" id="force-submit-btn"
                                class="bg-red-600 text-white px-5 py-3 rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">Update
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

                            // Prefer existing display value (dd-mm-yyyy) if present.
                            // Fallback to hidden ISO value (yyyy-mm-dd) converted to dd-mm-yyyy.
                            let defaultDateVal;
                            if (d.value) {
                                defaultDateVal = d.value;
                            } else if (h.value) {
                                const m = h.value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
                                defaultDateVal = m ? `${m[3]}-${m[2]}-${m[1]}` : h.value;
                            } else {
                                defaultDateVal = undefined;
                            }

                            fp(d, {
                                dateFormat: 'd-m-Y',
                                allowInput: true,
                                defaultDate: defaultDateVal,
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

                    if (h.value) d.value = toDMY(h.value);
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

                // Image preview & existing image removal
                const gambarInput = document.getElementById('gambar-input');
                const gambarPreview = document.getElementById('gambar-preview');
                const gambarPreviewImg = document.getElementById('gambar-preview-img');
                const existingImage = document.getElementById('existing-image');
                const removeExisting = document.getElementById('remove-existing-image');

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
                            if (existingImage) existingImage.style.display = 'none';
                            gambarPreviewImg.src = e.target.result;
                            gambarPreview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    });
                }

                if (removeExisting) {
                    removeExisting.addEventListener('click', function () {
                        if (existingImage) existingImage.remove();
                        const flag = document.getElementById('remove_gambar');
                        if (flag) flag.value = '1';
                    });
                }

                const removeMainImageBtn = document.getElementById('remove-main-image');
                if (removeMainImageBtn && gambarInput && gambarPreview && gambarPreviewImg) {
                    removeMainImageBtn.addEventListener('click', function () {
                        gambarInput.value = '';
                        gambarPreview.classList.add('hidden');
                        gambarPreviewImg.src = '#';
                        if (existingImage) existingImage.style.display = 'none';
                        const flag = document.getElementById('remove_gambar');
                        if (flag) flag.value = '1';
                    });
                }

                function validateField(field) {
                    let isValid = true;
                    const errorDiv = document.querySelector(`.error-message[data-field="${field.name}"]`);
                    field.classList.remove('border-red-500');
                    if (errorDiv) errorDiv.classList.add('hidden');

                    if (field.hasAttribute('required')) {
                        if (field.type === 'file') {
                            isValid = field.files.length > 0 || !!existingImage;
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

                // disposition choice dynamic options (preselect)
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
                function renderChoices() {
                    const val = type ? type.value : '';
                    if (!choice) return;
                    choice.innerHTML = '<option value="">-- Pilih --</option>';
                    if (options[val]) options[val].forEach(o => {
                        const el = document.createElement('option'); el.value = o[0]; el.textContent = o[1];
                        if (el.value === "{{ old('disposition_inventory_choice', $cmr->disposition_inventory_choice) }}") el.selected = true;
                        choice.appendChild(el);
                    });
                }
                if (type) { type.addEventListener('change', renderChoices); renderChoices(); }



            } catch (err) { console && console.error('Form validation script error', err); }
        });
    </script>

    <script>
        // Searchable dropdown helpers (supplier and part) — mirror create view
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

        (function initDropdowns() {
            // No need to re-set data attrs; they are present in markup
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

@endsection