@extends('layouts.navbar')

@section('content')
    @php
        // attempt to decode existing ppchead_note to prefill the form when editing
        $ppc_prefill = null;
        try {
            if (!empty($cmr->ppchead_note)) {
                $decoded = is_string($cmr->ppchead_note) ? json_decode($cmr->ppchead_note, true) : $cmr->ppchead_note;
                if (is_array($decoded)) {
                    if (array_key_exists('ppc', $decoded) && is_array($decoded['ppc'])) {
                        $ppc_prefill = $decoded['ppc'];
                    } else {
                        // maybe stored directly as ppc data
                        $ppc_prefill = $decoded;
                    }
                }
            }
        } catch (\Throwable $e) {
            $ppc_prefill = null;
        }
        $ppc_disposition_val = old('ppc_disposition', $ppc_prefill['disposition'] ?? '');
        $ppc_nominal_val = old('ppc_nominal', isset($ppc_prefill['nominal']) ? $ppc_prefill['nominal'] : '');
        $ppc_currency_val = old('ppc_currency', $ppc_prefill['currency'] ?? $cmr->ppc_currency ?? '');
        $ppc_currency_symbol_val = old('ppc_currency_symbol', $ppc_prefill['currency_symbol'] ?? $cmr->ppc_currency_symbol ?? '');
        $ppc_shipping_val = old('ppc_shipping', $ppc_prefill['shipping'] ?? '');
        $ppc_shipping_detail_val = old('ppc_shipping_detail', $ppc_prefill['shipping_detail'] ?? '');
        // format display value for nominal
        $ppc_nominal_display = $ppc_nominal_val !== '' ? number_format((float) $ppc_nominal_val, 0, ',', '.') : '';
    @endphp

    <div class="w-full m-0 p-0">
        <div class="bg-white rounded-b-lg shadow-sm overflow-hidden w-full">
            <div class="max-w-screen-xl mx-auto px-6 py-6">
                <form action="{{ route('ppchead.cmr.ppc.store', $cmr->id) }}" method="POST" id="cmr-ppc-form">
                    @csrf
                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-red-600 px-6 py-4">
                            <h1 class="text-white text-lg font-semibold">Input PPC</h1>
                        </div>

                        <div class="px-6 pt-6">
                            <a href="{{ route('ppchead.cmr.index') }}"
                                class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                                <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                                <span>Back</span>
                            </a>

                            <a href="{{ route('ppchead.cmr.previewFpdf', $cmr->id) }}" target="_blank"
                                class="inline-flex items-center justify-center gap-2 text-sm px-4 py-2 rounded shadow-md bg-red-700 hover:bg-red-800 text-white ml-3">
                                <span>Download PDF</span>
                            </a>
                        </div>

                        {{-- Compact Detail CMR --}}
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Disposition Of This Claim <span
                                        class="text-red-500">*</span></label>
                                <select name="ppc_disposition" id="ppc-disposition" required
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Choose --</option>
                                    <option value="send_replacement" {{ $ppc_disposition_val === 'send_replacement' ? 'selected' : '' }}>Send the Replacement</option>
                                </select>
                                @error('ppc_disposition') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Note: Pay Compensation fields removed from PPC form. Procurement will set compensation. -->

                            <!-- Send Replacement Field (Conditional) -->
                            <div id="send_replacement_field" style="display: none;" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Send The Replacement Value <span
                                        class="text-red-500">*</span></label>
                                <select name="ppc_shipping" id="ppc_shipping"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Choose --</option>
                                    <option value="AIR" {{ $ppc_shipping_val === 'AIR' ? 'selected' : '' }}>AIR (航空便)</option>
                                    <option value="SEA" {{ $ppc_shipping_val === 'SEA' ? 'selected' : '' }}>SEA (船便)</option>
                                </select>
                                @error('ppc_shipping') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-6">
                                <p id="ppc-disposition-error" class="text-red-500 text-xs mt-1 hidden">Please select
                                    Disposition Of This Claim before approving.</p>
                                <button type="button" id="open-approve-modal-btn"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded text-sm">Approve</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Approve dengan Recipient Selection -->
        <div id="approve-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
            style="display: none;">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-semibold mb-4">Konfirmasi Approve</h3>
                <p class="text-sm text-gray-700 mb-4">Apakah Anda yakin ingin Approve CMR {{ $cmr->no_reg ?? '' }}?</p>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Pilih VDD yang akan menerima request approval (opsional):</p>
                    <div class="mb-2 flex items-center justify-between">
                        <div class="text-xs text-gray-500">Pilih penerima VDD:</div>
                        <div class="text-xs text-gray-500"><label class="inline-flex items-center gap-2"><input
                                    type="checkbox" id="approve-select-all-recipients"> Pilih semua</label></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded p-2 bg-white">
                        @forelse($vddApprovers ?? [] as $va)
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="approve_recipients[]" value="{{ $va->npk }}"
                                    class="approve-recipient-checkbox">
                                <span class="truncate">{{ $va->name }} @if($va->email) &lt;{{ $va->email }}&gt; @endif</span>
                            </label>
                        @empty
                            <div class="col-span-2 text-sm text-gray-500 italic">Tidak ada approver VDD yang tersedia.</div>
                        @endforelse
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin meneruskan ke VDD secara spesifik.
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button id="approve-cancel" type="button"
                        class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                    <button type="button" id="approve-btn"
                        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Approve</button>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const dispositionSelect = document.getElementById('ppc-disposition');
                    const sendReplacementField = document.getElementById('send_replacement_field');
                    const sendReplacementMethod = document.getElementById('ppc_shipping');
                    const sendReplacementDetailWrap = document.getElementById('ppc_shipping_detail_wrap');
                    const sendReplacementDetail = document.getElementById('ppc_shipping_detail');
                    const dispositionError = document.getElementById('ppc-disposition-error');

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

                    function toggleField() {
                        const value = dispositionSelect.value;

                        if (value === 'send_replacement') {
                            sendReplacementField.style.display = 'block';
                            sendReplacementMethod.setAttribute('required', 'required');
                            if (sendReplacementDetailWrap) sendReplacementDetailWrap.style.display = 'block';
                            if (sendReplacementDetail) sendReplacementDetail.setAttribute('required', 'required');
                        } else {
                            sendReplacementField.style.display = 'none';
                            sendReplacementMethod.removeAttribute('required');
                            sendReplacementMethod.value = '';
                            if (sendReplacementDetailWrap) sendReplacementDetailWrap.style.display = 'none';
                            if (sendReplacementDetail) {
                                sendReplacementDetail.removeAttribute('required');
                                sendReplacementDetail.value = '';
                            }
                        }
                    }

                    dispositionSelect.addEventListener('change', toggleField);
                    if (dispositionSelect.value) toggleField();

                    // show shipping detail if prefilled
                    if (sendReplacementMethod && sendReplacementMethod.value) {
                        if (sendReplacementDetailWrap) sendReplacementDetailWrap.style.display = 'block';
                        if (sendReplacementDetail && sendReplacementDetail.value) sendReplacementDetail.setAttribute('required', 'required');
                    }

                    // Modal handling
                    const openModalBtn = document.getElementById('open-approve-modal-btn');
                    const approveModal = document.getElementById('approve-modal');
                    const cancelBtn = document.getElementById('approve-cancel');
                    const selectAllCheckbox = document.getElementById('approve-select-all-recipients');
                    const recipientCheckboxes = document.querySelectorAll('.approve-recipient-checkbox');

                    if (dispositionSelect) {
                        dispositionSelect.addEventListener('change', function () {
                            if (dispositionError && this.value) {
                                dispositionError.classList.add('hidden');
                            }
                        });
                    }

                    openModalBtn && openModalBtn.addEventListener('click', function () {
                        // Validate disposition
                        const val = dispositionSelect ? dispositionSelect.value : '';
                        if (!val) {
                            if (dispositionError) {
                                dispositionError.classList.remove('hidden');
                                dispositionError.textContent = 'Please select Disposition Of This Claim before approving.';
                            } else {
                                alert('Please select Disposition Of This Claim before approving.');
                            }
                            dispositionSelect && dispositionSelect.focus();
                            return;
                        }
                        if (dispositionError) dispositionError.classList.add('hidden');
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

                    // Approve button handler
                    const approveBtn = document.getElementById('approve-btn');
                    const ppcForm = document.getElementById('cmr-ppc-form');

                    if (approveBtn) {
                        const approveUrl = "{{ route('ppchead.cmr.approve', $cmr->id) }}";
                        const redirectUrl = "{{ route('ppchead.cmr.index') }}";
                        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

                        approveBtn.addEventListener('click', async function () {
                            approveBtn.disabled = true;
                            const prev = approveBtn.innerHTML;
                            approveBtn.innerHTML = 'Memproses...';
                            approveBtn.classList.add('opacity-50', 'cursor-not-allowed');

                            // Collect selected recipients
                            const selectedRecipients = [];
                            document.querySelectorAll('.approve-recipient-checkbox:checked').forEach(cb => {
                                selectedRecipients.push(cb.value);
                            });

                            try {
                                // First: save PPC data via form POST (AJAX)
                                const formData = new FormData(ppcForm);
                                const storeRes = await fetch(ppcForm.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    credentials: 'same-origin'
                                });

                                if (!storeRes.ok) {
                                    let msg = 'Gagal menyimpan input PPC.';
                                    try { const j = await storeRes.json(); if (j.message) msg = j.message; } catch (e) { }
                                    alert(msg);
                                    approveBtn.disabled = false;
                                    approveBtn.innerHTML = prev;
                                    approveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                    return;
                                }

                                // Second: call approve endpoint with recipients
                                const approveRes = await fetch(approveUrl, {
                                    method: 'POST',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    credentials: 'same-origin',
                                    body: JSON.stringify({ recipients: selectedRecipients })
                                });

                                const js = await approveRes.json();
                                if (js.success) {
                                    approveModal && (approveModal.style.display = 'none');
                                    window.location.href = redirectUrl;
                                    return;
                                } else {
                                    alert(js.message || 'Approve gagal');
                                }
                            } catch (err) {
                                console && console.error('Approve flow error', err);
                                alert('Terjadi kesalahan saat memproses.');
                            } finally {
                                approveBtn.disabled = false;
                                approveBtn.innerHTML = prev;
                                approveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                            }
                        });
                    }
                });
            </script>
        @endpush

@endsection