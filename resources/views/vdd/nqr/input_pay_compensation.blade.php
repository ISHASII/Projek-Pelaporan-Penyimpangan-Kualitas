@extends('layouts.navbar')

@section('content')
    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="max-w-screen-lg mx-auto px-6 py-6">
                <form action="{{ $formAction }}" method="POST" id="vdd-paycomp-form">
                    @csrf

                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-red-600 px-6 py-4">
                            <h1 class="text-white text-lg font-semibold">Input Pay Compensation (VDD)</h1>
                        </div>

                        <div class="px-6 pt-6">
                            <div class="flex items-center gap-3">
                                <a href="{{ $backRoute }}"
                                    class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                                    <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                                    <span>Kembali</span>
                                </a>

                                @if(!empty($nqr->id))
                                    <a href="{{ $previewRoute }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-red-600 hover:bg-red-700 text-white">
                                        <span>Download PDF</span>
                                    </a>
                                @endif
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
                                                {{ $nqr->tgl_terbit_nqr ? $nqr->tgl_terbit_nqr->format('d-m-Y') : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Tgl Delivery</div>
                                            <div class="font-medium text-gray-900">
                                                {{ $nqr->tgl_delivery ? $nqr->tgl_delivery->format('d-m-Y') : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Nomor PO</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->nomor_po ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Status NQR</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->status_nqr ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Claim occurance freq.</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->claim_occurence_freq ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4 text-center">
                                        <div>
                                            <div class="text-xs text-gray-500">Nama Supplier</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->nama_supplier ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Nama Part</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->nama_part ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Nomor Part</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->nomor_part ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Disposition Inventory</div>
                                            @php
                                                $dispLoc = $nqr->disposition_inventory_location ?? '';
                                                $dispAct = $nqr->disposition_inventory_action ?? '';
                                                $dispText = trim($dispLoc . ($dispLoc && $dispAct ? ' / ' : '') . $dispAct);
                                            @endphp
                                            <div class="font-medium text-gray-900">{{ $dispText !== '' ? $dispText : '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500 mt-2">Gambar</div>
                                            <div class="mt-2">
                                                @if(!empty($nqr->gambar))
                                                    <a href="{{ asset('storage/' . $nqr->gambar) }}" target="_blank"
                                                        title="Lihat gambar">
                                                        <img src="{{ asset('storage/' . $nqr->gambar) }}" alt="gambar-nqr"
                                                            class="mx-auto w-28 h-20 object-cover rounded border border-gray-200 shadow-sm" />
                                                    </a>
                                                    @if(!empty($nqr->detail_gambar))
                                                        <div class="text-xs text-gray-500 mt-1">{{ $nqr->detail_gambar }}</div>
                                                    @endif
                                                @else
                                                    <div class="text-xs text-gray-400">-</div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <div class="text-xs text-gray-500">Status NQR (Approval)</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->status_approval ?? '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Location Claim Occur</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->location_claim_occur ?? '-' }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-xs text-gray-500">Disposition Defect Part</div>
                                            <div class="font-medium text-gray-900">
                                                {{ $nqr->disposition_defect_part ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="pt-2">
                                            <div class="text-xs text-gray-500">Problem / Deskripsi</div>
                                            @php
                                                $problemText = $nqr->detail_gambar ?? $nqr->note ?? $nqr->problem ?? null;
                                            @endphp
                                            <div
                                                class="mt-1 text-sm text-gray-800 leading-relaxed max-h-24 overflow-auto border border-transparent">
                                                {!! $problemText ? nl2br(e($problemText)) : '-' !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 border-t pt-4">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                                        <div>
                                            <div class="text-xs text-gray-500">Invoice</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->invoice ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Total Delivered</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->total_del ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Total Claim</div>
                                            <div class="font-medium text-gray-900">{{ $nqr->total_claim ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">-</div>
                                            <div class="font-medium text-gray-900">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Uang</label>
                                <select name="pay_compensation_currency" id="pay_compensation_currency"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
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
                                @error('pay_compensation_currency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Pay Compensation</label>
                                <input type="text" id="pay_compensation_display" placeholder="0"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <input type="hidden" name="pay_compensation_value" id="pay_compensation_value">
                                @error('pay_compensation_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-6">
                                <input type="hidden" name="skip_input_compensation" id="skip_input_compensation"
                                    value="1" />
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
            const form = document.getElementById('vdd-paycomp-form');
            const approveBtn = document.getElementById('approve-btn');
            const skipInput = document.getElementById('skip_input_compensation');

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
                    // If no amount & currency selected, allow skipping; keep skip_input_compensation=1
                    if (!amountVal && !currencyVal) {
                        // we allow VDD to approve without entering pay compensation
                        skipInput && (skipInput.value = 1);
                    } else {
                        skipInput && (skipInput.value = 0);
                        // Now optional: only validate amount if present (must be numeric & positive)
                        if (amountVal && (isNaN(amountVal) || Number(amountVal) <= 0)) {
                            e.preventDefault();
                            alert('Silakan masukkan Nilai Pay Compensation yang valid.');
                            payCompensationDisplay && payCompensationDisplay.focus();
                            return false;
                        }
                    }

                    // let server handle storage and approval; show loading state
                    if (approveBtn) {
                        approveBtn.disabled = true;
                        approveBtn.innerText = 'Memproses...';
                    }
                    return true;
                });
            }
        });
    </script>
@endsection