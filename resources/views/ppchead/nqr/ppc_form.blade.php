@extends('layouts.navbar')

@section('content')
    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="max-w-screen-xl mx-auto px-6 py-6">
                <form action="{{ route('ppchead.nqr.update', $nqr) }}" method="POST" id="nqr-ppc-form">
                    @csrf
                    @method('PUT')

                    <!-- Card dengan Header dan Form -->
                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <!-- Header dengan warna merah -->
                        <div class="bg-red-600 px-6 py-4">
                            <h1 class="text-white text-lg font-semibold">Lengkapi Input PPC</h1>
                        </div>

                        <!-- Tombol Kembali -->
                        <div class="px-6 pt-6">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('ppchead.nqr.index') }}"
                                    class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                                    <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                                    <span>Kembali</span>
                                </a>

                                @if(!empty($nqr->id))
                                    <a href="{{ route('ppchead.nqr.previewFpdf', $nqr->id) }}" target="_blank" rel="noopener"
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

                        <!-- Form Input PPC - Style Simple -->
                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Disposition Claim <span class="text-red-500">*</span>
                                </label>
                                <select name="disposition_claim" id="disposition_claim" required
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Pay Compensation" {{ old('disposition_claim', $nqr->disposition_claim) == 'Pay Compensation' ? 'selected' : '' }}>Pay Compensation
                                    </option>
                                    <option value="Send the Replacement" {{ old('disposition_claim', $nqr->disposition_claim) == 'Send the Replacement' ? 'selected' : '' }}>Send the
                                        Replacement</option>
                                </select>
                                @error('disposition_claim')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            <!-- Currency Field (Conditional) -->
                            <div id="currency_field" style="display: none;" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Uang <span
                                        class="text-red-500">*</span></label>
                                <select name="pay_compensation_currency" id="pay_compensation_currency"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih Mata Uang --</option>
                                    <option value="IDR" data-symbol="Rp" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'IDR' ? 'selected' : '' }}>Rupiah (Rp)</option>
                                    <option value="JPY" data-symbol="¥" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'JPY' ? 'selected' : '' }}>Japanese Yen (¥)
                                    </option>
                                    <option value="USD" data-symbol="$" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                                    <option value="MYR" data-symbol="RM" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'MYR' ? 'selected' : '' }}>Malaysian Ringgit (RM)
                                    </option>
                                    <option value="VND" data-symbol="₫" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'VND' ? 'selected' : '' }}>Vietnamese Dong (₫)
                                    </option>
                                    <option value="THB" data-symbol="฿" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'THB' ? 'selected' : '' }}>Thai Baht (฿)</option>
                                    <option value="KRW" data-symbol="₩" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'KRW' ? 'selected' : '' }}>Korean Won (₩)
                                    </option>
                                    <option value="INR" data-symbol="₹" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'INR' ? 'selected' : '' }}>Indian Rupee (₹)
                                    </option>
                                    <option value="CNY" data-symbol="¥" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'CNY' ? 'selected' : '' }}>Chinese Yuan (¥)
                                    </option>
                                    <option value="CUSTOM" {{ old('pay_compensation_currency', $nqr->pay_compensation_currency) === 'CUSTOM' ? 'selected' : '' }}>Custom / Manual
                                        Input</option>
                                </select>
                                @error('pay_compensation_currency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Currency Symbol Field (Manual) -->
                            <div id="currency_symbol_field" style="display: none;" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Simbol Mata Uang (Manual) <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="pay_compensation_currency_symbol"
                                    id="pay_compensation_currency_symbol"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="cth: €, £, ₽"
                                    value="{{ old('pay_compensation_currency_symbol', $nqr->pay_compensation_currency_symbol) }}"
                                    maxlength="10">
                                <p class="text-xs text-gray-500 mt-1">Masukkan simbol mata uang khusus (maks 10 karakter)
                                </p>
                                @error('pay_compensation_currency_symbol') <p class="text-red-500 text-xs mt-1">
                                    {{ $message }}
                                </p> @enderror
                            </div>

                            <!-- Pay Compensation Field (Conditional) -->
                            <div id="pay_compensation_field" style="display: none;" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Pay Compensation <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="pay_compensation_display"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="0"
                                    value="{{ $nqr->pay_compensation_value ? number_format((float) $nqr->pay_compensation_value, 0, ',', '.') : old('pay_compensation_value') }}">
                                <input type="hidden" name="pay_compensation_value" id="pay_compensation_value"
                                    value="{{ old('pay_compensation_value', $nqr->pay_compensation_value) }}">
                                @error('pay_compensation_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Send Replacement Field (Conditional) -->
                            <div id="send_replacement_field" style="display: none;" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Perlakuan Terhadap Claim <span class="text-red-500">*</span>
                                </label>
                                <select name="send_replacement_method" id="send_replacement_method"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="By Air" {{ old('send_replacement_method', $nqr->send_replacement_method) == 'By Air' ? 'selected' : '' }}>By Air</option>
                                    <option value="By Sea" {{ old('send_replacement_method', $nqr->send_replacement_method) == 'By Sea' ? 'selected' : '' }}>By Sea</option>
                                </select>
                                @error('send_replacement_method')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tombol Approve (mengganti Simpan Input PPC) -->
                            <div class="mt-6">
                                <button type="button" id="approve-btn"
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dispositionSelect = document.getElementById('disposition_claim');
            const sendReplacementField = document.getElementById('send_replacement_field');
            const sendReplacementMethod = document.getElementById('send_replacement_method');
            const currencyField = document.getElementById('currency_field');
            const currencySelect = document.getElementById('pay_compensation_currency');
            const currencySymbolField = document.getElementById('currency_symbol_field');
            const currencySymbolInput = document.getElementById('pay_compensation_currency_symbol');
            const payCompensationField = document.getElementById('pay_compensation_field');
            const payCompensationDisplay = document.getElementById('pay_compensation_display');
            const payCompensationInput = document.getElementById('pay_compensation_value');

            function formatRupiah(angka) {
                if (!angka) return '';
                let number_string = String(angka).replace(/[^\d,]/g, '');
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

            // Sync display (formatted) and hidden numeric input
            payCompensationDisplay && payCompensationDisplay.addEventListener('input', function (e) {
                let raw = this.value.replace(/\./g, '').replace(/,/g, '.').replace(/[^0-9\.]/g, '');
                payCompensationInput.value = raw ? parseFloat(raw) : '';
                // format display with dots
                this.value = formatRupiah(this.value);
            });

            // Handle currency selection change
            currencySelect && currencySelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (this.value === 'CUSTOM') {
                    currencySymbolField.style.display = 'block';
                    currencySymbolInput.setAttribute('required', 'required');
                } else {
                    currencySymbolField.style.display = 'none';
                    currencySymbolInput.removeAttribute('required');
                    currencySymbolInput.value = '';
                    // Auto-fill symbol from selected currency
                    const symbol = selectedOption.getAttribute('data-symbol');
                    if (symbol) {
                        currencySymbolInput.value = symbol;
                    }
                }
            });

            function toggleField() {
                const value = dispositionSelect.value;

                if (value === 'Send the Replacement') {
                    sendReplacementField.style.display = 'block';
                    sendReplacementMethod.setAttribute('required', 'required');

                    currencyField.style.display = 'none';
                    currencySelect.removeAttribute('required');
                    currencySelect.value = '';
                    currencySymbolField.style.display = 'none';
                    currencySymbolInput.removeAttribute('required');
                    currencySymbolInput.value = '';

                    payCompensationField.style.display = 'none';
                    payCompensationInput.removeAttribute('required');
                    payCompensationInput.value = '';
                    payCompensationDisplay.value = '';
                } else if (value === 'Pay Compensation') {
                    currencyField.style.display = 'block';
                    currencySelect.setAttribute('required', 'required');
                    payCompensationField.style.display = 'block';
                    payCompensationInput.setAttribute('required', 'required');
                    sendReplacementField.style.display = 'none';
                    sendReplacementMethod.removeAttribute('required');
                    sendReplacementMethod.value = '';
                    // Check if custom currency is selected
                    if (currencySelect.value === 'CUSTOM') {
                        currencySymbolField.style.display = 'block';
                        currencySymbolInput.setAttribute('required', 'required');
                    }
                } else {
                    sendReplacementField.style.display = 'none';
                    sendReplacementMethod.removeAttribute('required');
                    sendReplacementMethod.value = '';
                    currencyField.style.display = 'none';
                    currencySelect.removeAttribute('required');
                    currencySelect.value = '';
                    currencySymbolField.style.display = 'none';
                    currencySymbolInput.removeAttribute('required');
                    currencySymbolInput.value = '';
                    payCompensationField.style.display = 'none';
                    payCompensationInput.removeAttribute('required');
                    payCompensationInput.value = '';
                    payCompensationDisplay.value = '';
                }
            }

            dispositionSelect.addEventListener('change', toggleField);
            if (dispositionSelect.value) {
                toggleField();
            }

            // Check if CUSTOM currency is pre-selected on page load
            if (currencySelect && currencySelect.value === 'CUSTOM') {
                currencySymbolField.style.display = 'block';
                currencySymbolInput.setAttribute('required', 'required');
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                const form = document.getElementById('nqr-ppc-form');
                const btn = document.getElementById('approve-btn');
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
                    if (!validateAllFields()) {
                        const firstErrorField = form.querySelector('.border-red-500');
                        if (firstErrorField) { firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstErrorField.focus(); }
                        return;
                    }

                    btn.disabled = true;
                    const prev = btn.innerHTML;
                    btn.innerHTML = 'Memproses...';
                    btn.classList.add('opacity-50', 'cursor-not-allowed');

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

                        // Second: call approve endpoint
                        const approveUrl = '{{ route('ppchead.nqr.approve', $nqr->id ?? 0) }}';
                        const approveRes = await fetch(approveUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value,
                                'Content-Type': 'application/json'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({})
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
                            window.location.href = '{{ route('ppchead.nqr.index') }}';
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
@endsection