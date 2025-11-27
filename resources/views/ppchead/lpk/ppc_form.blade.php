@extends('layouts.navbar')

@section('content')
    <div class="max-w-5xl mx-auto px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
            <div class="px-6 py-3 bg-red-600">
                <h1 class="text-white text-lg font-semibold">Lengkapi Input PPC</h1>
            </div>

            <div class="p-6">

                <div class="flex items-center mb-8 gap-3">
                        <a href="{{ route('ppchead.lpk.index') }}"
                            class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                            <img src="/icon/back.ico" alt="back" class="w-4 h-4" />
                            <span>Kembali</span>
                        </a>

                        @if(!empty($lpk->id))
                            <a href="{{ route('ppchead.lpk.downloadPdf', $lpk->id) }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded bg-red-600 hover:bg-red-700 text-white">
                                <span>Download PDF</span>
                            </a>
                        @endif
                    </div>

                {{-- Compact Detail LPK (requested fields only) --}}
                <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm p-4">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Detail LPK</h2>
                    <div class="grid grid-cols-1 gap-2 text-sm text-gray-700 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <div class="text-xs text-gray-500">Referensi LKA</div>
                            <div class="font-medium">{{ $lpk->referensi_lka ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Tgl Terbit LKA</div>
                            <div class="font-medium">{{ $lpk->tgl_terbit_lka ? (is_string($lpk->tgl_terbit_lka) ? (strtotime($lpk->tgl_terbit_lka) ? date('d-m-Y', strtotime($lpk->tgl_terbit_lka)) : $lpk->tgl_terbit_lka) : $lpk->tgl_terbit_lka->format('d-m-Y')) : '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Tgl Terbit LPK</div>
                            <div class="font-medium">{{ $lpk->tgl_terbit ? (is_string($lpk->tgl_terbit) ? (strtotime($lpk->tgl_terbit) ? date('d-m-Y', strtotime($lpk->tgl_terbit)) : $lpk->tgl_terbit) : $lpk->tgl_terbit->format('d-m-Y')) : (optional($lpk->created_at)->format('d-m-Y') ?? '-') }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Tgl Delivery</div>
                            <div class="font-medium">{{ $lpk->tgl_delivery ? (is_string($lpk->tgl_delivery) ? (strtotime($lpk->tgl_delivery) ? date('d-m-Y', strtotime($lpk->tgl_delivery)) : $lpk->tgl_delivery) : $lpk->tgl_delivery->format('d-m-Y')) : '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Nama Supplier</div>
                            <div class="font-medium">{{ $lpk->nama_supply ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Nama Part</div>
                            <div class="font-medium">{{ $lpk->nama_part ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Nomor PO</div>
                            <div class="font-medium">{{ $lpk->nomor_po ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Nomor Part</div>
                            <div class="font-medium">{{ $lpk->nomor_part ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Status LPK</div>
                            <div class="font-medium">{{ $lpk->status ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Jenis LPK</div>
                            <div class="font-medium">{{ $lpk->jenis_ng ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Kategori</div>
                            <div class="font-medium">{{ $lpk->kategori ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Perlakuan Terhadap Part</div>
                            <div class="font-medium">{{ $lpk->perlakuan_terhadap_part ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Frekuensi Claim</div>
                            <div class="font-medium">{{ $lpk->frekuensi_claim ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Perlakuan Part Defect</div>
                            <div class="font-medium">{{ $lpk->perlakuan_part_defect ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Lokasi Penemuan Claim</div>
                            <div class="font-medium">{{ $lpk->lokasi_penemuan_claim ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Status Part Claim</div>
                            <div class="font-medium">{{ $lpk->status_repair ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Gambar</div>
                            <div class="mt-1">
                                @if(!empty($lpk->gambar))
                                    <a href="{{ $lpk->gambar }}" target="_blank" class="inline-block">
                                        <img src="{{ $lpk->gambar }}" alt="LPK image" class="w-28 h-16 object-cover rounded border" />
                                    </a>
                                @else
                                    <div class="text-sm text-gray-500">-</div>
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-2 lg:col-span-3">
                            <div class="text-xs text-gray-500">Problem / Deskripsi</div>
                            <div class="mt-1 text-sm text-gray-700 max-h-20 overflow-hidden">{!! $lpk->problem ? nl2br(e($lpk->problem)) : '-' !!}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Total Check</div>
                            <div class="font-medium">{{ $lpk->total_check ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Total NG</div>
                            <div class="font-medium">{{ $lpk->total_ng ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Total Delivery</div>
                            <div class="font-medium">{{ $lpk->total_delivery ?? '-' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Total Claim</div>
                            <div class="font-medium">{{ $lpk->total_claim ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('ppchead.lpk.ppcForm.store', $lpk->id ?? 0) }}" method="POST" id="ppc-form">
                    @csrf

                    <div class="form-compact">
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Perlakuan Terhadap Part <span
                                        class="text-red-500">*</span></label>
                                <select name="ppc_perlakuan_terhadap_part" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Sortir oleh Supplier" {{ (old('ppc_perlakuan_terhadap_part', $lpk->ppc_perlakuan_terhadap_part ?? '') == 'Sortir oleh Supplier') ? 'selected' : '' }}>Sortir oleh Supplier</option>
                                    <option value="Sortir Oleh PT KYBI" {{ (old('ppc_perlakuan_terhadap_part', $lpk->ppc_perlakuan_terhadap_part ?? '') == 'Sortir Oleh PT KYBI') ? 'selected' : '' }}>Sortir Oleh PT KYBI</option>
                                    <option value="Part Tetap Dipakai" {{ (old('ppc_perlakuan_terhadap_part', $lpk->ppc_perlakuan_terhadap_part ?? '') == 'Part Tetap Dipakai') ? 'selected' : '' }}>Part Tetap Dipakai</option>
                                </select>
                                @error('ppc_perlakuan_terhadap_part')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="ppc_perlakuan_terhadap_part">Perlakuan Terhadap Part wajib dipilih</div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Perlakuan Terhadap Claim <span
                                        class="text-red-500">*</span></label>
                                <select name="ppc_perlakuan_terhadap_claim" required
                                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="pemotongan pembayaran" {{ (old('ppc_perlakuan_terhadap_claim', $lpk->ppc_perlakuan_terhadap_claim ?? '') == 'pemotongan pembayaran') ? 'selected' : '' }}>pemotongan pembayaran</option>
                                    <option value="kirim pengganti" {{ (old('ppc_perlakuan_terhadap_claim', $lpk->ppc_perlakuan_terhadap_claim ?? '') == 'kirim pengganti') ? 'selected' : '' }}>
                                        kirim pengganti</option>
                                </select>
                                @error('ppc_perlakuan_terhadap_claim')<p class="text-red-500 text-xs mt-1">{{ $message }}
                                </p>@enderror
                                <div class="error-message text-red-500 text-xs mt-1 hidden"
                                    data-field="ppc_perlakuan_terhadap_claim">Perlakuan Terhadap Claim wajib dipilih</div>
                            </div>
                        </div>
                        <div class="pt-4 border-t flex justify-between items-center">
                            <button type="button" id="approve-btn"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded text-sm">
                                Approve
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                const form = document.getElementById('ppc-form');
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
                        // First: save PPC inputs via the existing store route
                        const storeUrl = form.getAttribute('action');
                        const formData = new FormData(form);
                        const storeRes = await postFormData(storeUrl, formData);

                        if (!storeRes.ok) {
                            // If store failed, try to parse JSON message or fallback to redirect
                            let msg = 'Gagal menyimpan input PPC.';
                            try { const j = await storeRes.json(); if (j.message) msg = j.message; } catch(e) {}
                            alert(msg);
                            btn.disabled = false; btn.innerHTML = prev; btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            return;
                        }

                        // Second: call approve endpoint
                        const approveUrl = '{{ route('ppchead.lpk.approve', $lpk->id ?? 0) }}';
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
                            try { const j = await approveRes.json(); if (j.message) msg = j.message; } catch(e) {}
                            alert(msg);
                            btn.disabled = false; btn.innerHTML = prev; btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            return;
                        }

                        const js = await approveRes.json();
                        if (js.success) {
                            // redirect to index or show message
                            window.location.href = '{{ route('ppchead.lpk.index') }}';
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
                console && console.error('Form script error', err);
            }
        });
    </script>

@endsection
