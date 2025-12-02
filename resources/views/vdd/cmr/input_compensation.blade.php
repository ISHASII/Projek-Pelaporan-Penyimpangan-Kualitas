@extends('layouts.navbar')

@section('content')
    @php
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
    @endphp

    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="max-w-screen-md mx-auto px-6 py-6">
                <form action="{{ $formAction ?? route('vdd.cmr.approve', $cmr->id) }}" method="POST" id="compensation-form">
                    @csrf
                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-red-600 px-6 py-4">
                            <h1 class="text-white text-lg font-semibold">Input Pay Compensation
                                ({{ $roleLabel ?? 'VDD' }})</h1>
                        </div>

                        <div class="px-6 pt-6 flex items-center gap-3">
                            <a href="{{ $backRoute ?? route('vdd.cmr.index') }}"
                                class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                                <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                                <span>Back</span>
                            </a>

                            <a href="{{ $previewRoute ?? route('vdd.cmr.previewFpdf', $cmr->id) }}" target="_blank"
                                rel="noopener"
                                class="inline-flex items-center justify-center gap-2 text-sm px-4 py-2 rounded shadow-md bg-red-700 hover:bg-red-800 text-white">
                                <span>Download PDF</span>
                            </a>
                        </div>

                        {{-- Compact Detail CMR (copied from PPC form) --}}
                        @if(isset($cmr))
                            <div class="px-6 pt-4">
                                <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm p-4">
                                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Detail CMR</h2>
                                    <div class="grid grid-cols-1 gap-2 text-sm text-gray-700 sm:grid-cols-2 lg:grid-cols-3">
                                        <div>
                                            <div class="text-xs text-gray-500">CMR ISSUE DATE (発行日)</div>
                                            <div class="font-medium">
                                                {{ $cmr->tgl_terbit_cmr ? (is_string($cmr->tgl_terbit_cmr) ? (strtotime($cmr->tgl_terbit_cmr) ? date('d-m-Y', strtotime($cmr->tgl_terbit_cmr)) : $cmr->tgl_terbit_cmr) : $cmr->tgl_terbit_cmr->format('d-m-Y')) : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DELIVERY DATE (配達日)</div>
                                            <div class="font-medium">
                                                {{ $cmr->tgl_delivery ? (is_string($cmr->tgl_delivery) ? (strtotime($cmr->tgl_delivery) ? date('d-m-Y', strtotime($cmr->tgl_delivery)) : $cmr->tgl_delivery) : $cmr->tgl_delivery->format('d-m-Y')) : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">CMR TYPE</div>
                                            <div class="font-medium">{{ $cmr->cmr_type ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">B/L date (船積日)</div>
                                            <div class="font-medium">
                                                {{ $cmr->bl_date ? (is_string($cmr->bl_date) ? (strtotime($cmr->bl_date) ? date('d-m-Y', strtotime($cmr->bl_date)) : $cmr->bl_date) : $cmr->bl_date->format('d-m-Y')) : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">A/R Date (到着日)</div>
                                            <div class="font-medium">
                                                {{ $cmr->ar_date ? (is_string($cmr->ar_date) ? (strtotime($cmr->ar_date) ? date('d-m-Y', strtotime($cmr->ar_date)) : $cmr->ar_date) : $cmr->ar_date->format('d-m-Y')) : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Found Date (発見日)</div>
                                            <div class="font-medium">
                                                {{ $cmr->found_date ? (is_string($cmr->found_date) ? (strtotime($cmr->found_date) ? date('d-m-Y', strtotime($cmr->found_date)) : $cmr->found_date) : $cmr->found_date->format('d-m-Y')) : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">SUPPLIER NAME (サプライヤ名)</div>
                                            <div class="font-medium">{{ $cmr->nama_supplier ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">PART NAME (部品名)</div>
                                            <div class="font-medium">{{ $cmr->nama_part ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">PO NUMBER (注文番号)</div>
                                            <div class="font-medium">{{ $cmr->nomor_po ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">PART NUMBER (部品番号)</div>
                                            <div class="font-medium">{{ $cmr->nomor_part ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">INVOICE NO (請求書番号)</div>
                                            <div class="font-medium">{{ $cmr->invoice_no ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">ORDER NO (オーダーNo.)</div>
                                            <div class="font-medium">{{ $cmr->order_no ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">PRODUCT (製品)</div>
                                            <div class="font-medium">{{ $cmr->product ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">MODEL (模型)</div>
                                            <div class="font-medium">{{ $cmr->model ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">CRATE NUMBER (箱番号)</div>
                                            <div class="font-medium">{{ $cmr->crate_number ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">LOCATION CLAIM OCCUR (クレーム発生場所)</div>
                                            <div class="font-medium">{{ $cmr->location_claim_occurrence ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DISPOSITION OF INVENTORY TYPE (在庫品処理)</div>
                                            <div class="font-medium">{{ $cmr->disposition_inventory_type ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DISPOSITION INVENTORY CHOICE (在庫品処理)</div>
                                            <div class="font-medium">{{ $cmr->disposition_inventory_choice ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">CLAIM OCCURRENCE FREQUENCY (請求発生頻度)</div>
                                            <div class="font-medium">{{ $cmr->claim_occurrence_frequency ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DISPATCH OF DEFECTIVE PARTS (不良部品の発送)</div>
                                            <div class="font-medium">{{ $cmr->dispatch_defective_parts ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">SEND REPLACEMENT (送替)</div>
                                            <div class="font-medium">{{ $ppc_shipping_val ? $ppc_shipping_val : '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">DISPOSITION OF DEFECT PARTS (不良部品の処分)</div>
                                            <div class="font-medium">{{ $cmr->disposition_defect_parts ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">QTY ORDER (注文数量)</div>
                                            <div class="font-medium">
                                                {{ $cmr->qty_order ? number_format($cmr->qty_order) : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">QTY DELIVERED (納品数量)</div>
                                            <div class="font-medium">
                                                {{ $cmr->qty_deliv ? number_format($cmr->qty_deliv) : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">QTY PROBLEM (数量の問題)</div>
                                            <div class="font-medium">
                                                {{ $cmr->qty_problem ? number_format($cmr->qty_problem) : '-' }}
                                            </div>
                                        </div>

                                        <div class="md:col-span-2 lg:col-span-3">
                                            <div class="text-xs text-gray-500">PROBLEM</div>
                                            @php
                                                $problemText = $cmr->input_problem ?? null;
                                            @endphp
                                            <div
                                                class="mt-1 text-sm text-gray-800 leading-relaxed max-h-40 overflow-auto border border-transparent">
                                                {!! $problemText ? nl2br(e($problemText)) : '-' !!}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">IMAGE</div>
                                            <div class="mt-1">
                                                @if(!empty($cmr->gambar))
                                                    @php
                                                        $imgSrc = $cmr->gambar;
                                                        if ($imgSrc && strpos($imgSrc, '/storage/') !== 0) {
                                                            $imgSrc = asset('storage/' . ltrim($imgSrc, '/'));
                                                        }
                                                    @endphp
                                                    <a href="{{ $imgSrc }}" target="_blank" class="inline-block">
                                                        <img src="{{ $imgSrc }}" alt="cmr-image"
                                                            class="w-28 h-16 object-cover rounded border" />
                                                    </a>
                                                @else
                                                    <div class="text-sm text-gray-500">-</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                <select name="ppc_currency" id="ppc_currency"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Choose Currency --</option>
                                    <option value="IDR" data-symbol="Rp" {{ $ppc_currency_val === 'IDR' ? 'selected' : '' }}>
                                        Rupiah (Rp)</option>
                                    <option value="JPY" data-symbol="¥" {{ $ppc_currency_val === 'JPY' ? 'selected' : '' }}>
                                        Japanese Yen (¥)</option>
                                    <option value="USD" data-symbol="$" {{ $ppc_currency_val === 'USD' ? 'selected' : '' }}>US
                                        Dollar ($)</option>
                                    <option value="MYR" data-symbol="RM" {{ $ppc_currency_val === 'MYR' ? 'selected' : '' }}>
                                        Malaysian Ringgit (RM)</option>
                                    <option value="VND" data-symbol="₫" {{ $ppc_currency_val === 'VND' ? 'selected' : '' }}>
                                        Vietnamese Dong (₫)</option>
                                    <option value="THB" data-symbol="฿" {{ $ppc_currency_val === 'THB' ? 'selected' : '' }}>
                                        Thai Baht (฿)</option>
                                    <option value="KRW" data-symbol="₩" {{ $ppc_currency_val === 'KRW' ? 'selected' : '' }}>
                                        Korean Won (₩)</option>
                                    <option value="INR" data-symbol="₹" {{ $ppc_currency_val === 'INR' ? 'selected' : '' }}>
                                        Indian Rupee (₹)</option>
                                    <option value="CNY" data-symbol="¥" {{ $ppc_currency_val === 'CNY' ? 'selected' : '' }}>
                                        Chinese Yuan (¥)</option>
                                    <option value="CUSTOM" {{ $ppc_currency_val === 'CUSTOM' ? 'selected' : '' }}>Custom /
                                        Manual Input</option>
                                </select>
                                @error('ppc_currency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div id="currency_symbol_field" style="display: none;" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Currency Symbol (Manual)</label>
                                <input type="text" name="ppc_currency_symbol" id="ppc_currency_symbol"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="e.g., €, £, ₽, etc." value="{{ $ppc_currency_symbol_val }}" maxlength="10">
                                <p class="text-xs text-gray-500 mt-1">Enter custom currency symbol (max 10 characters)</p>
                                @error('ppc_currency_symbol') <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pay Compensation Amount</label>
                                <input type="text" id="ppc_nominal_display"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="0" value="{{ $ppc_nominal_display }}">
                                <input type="hidden" name="ppc_nominal" id="ppc_nominal" value="{{ $ppc_nominal_val }}">
                                @error('ppc_nominal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-6">
                                <p id="comp_amount_error" class="text-red-500 text-sm mb-2" style="display:none;"></p>
                                <input type="hidden" name="skip_input_compensation" value="1" />
                                <button type="submit" id="approve-btn"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded text-sm">Approve</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const currencySelect = document.getElementById('ppc_currency');
                const currencySymbolField = document.getElementById('currency_symbol_field');
                const currencySymbolInput = document.getElementById('ppc_currency_symbol');
                const payCompensationDisplay = document.getElementById('ppc_nominal_display');
                const payCompensationInput = document.getElementById('ppc_nominal');

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

                // Client-side validation: ensure amount > 0 before allowing form submit
                const compForm = document.getElementById('compensation-form');
                const compAmountError = document.getElementById('comp_amount_error');

                if (compForm) {
                    compForm.addEventListener('submit', function (e) {
                        compAmountError.style.display = 'none';
                        compAmountError.textContent = '';
                        let rawVal = payCompensationInput.value;
                        // allow empty (optional). only validate if value provided
                        if (rawVal !== '' && rawVal !== null) {
                            let num = parseFloat(rawVal);
                            if (isNaN(num) || num <= 0) {
                                e.preventDefault();
                                compAmountError.textContent = 'Amount must be greater than 0.';
                                compAmountError.style.display = 'block';
                                payCompensationInput.focus();
                                return false;
                            }
                        }
                        // allow submit — server will save and auto-approve
                        return true;
                    });
                }
            });
        </script>
    @endpush

@endsection