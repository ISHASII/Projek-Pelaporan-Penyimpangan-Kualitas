@extends('layouts.navbar')

@section('content')
    <div class="w-full m-0 p-0 -mt-0">
        <div class="m-0">
            <div
                class="bg-white rounded-none shadow-none overflow-hidden border-t-0 border-l-0 border-r-0 border-gray-100 w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">

                    </div>

                    <form id="filter-form" method="GET" action="{{ route('procurement.nqr.index') }}" class="mb-4">
                        @php
                            $dateValue = '';
                            if (request('date')) {
                                try {
                                    $dateValue = \Carbon\Carbon::parse(request('date'))->format('d-m-Y');
                                } catch (\Exception $e) {
                                    $dateValue = request('date');
                                }
                            }
                        @endphp
                        <div class="rounded-md border border-gray-200 p-3 sm:p-4 bg-white shadow-sm">
                            {{-- Hidden canonical date (ISO) synced before submit --}}
                            <input type="hidden" name="date" id="date-hidden" value="{{ request('date') }}" />
                            {{-- Mobile Layout: Stacked --}}
                            <div class="block lg:hidden space-y-2">
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Pencarian</label>
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari no reg, supplier, part..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Tanggal</label>
                                        <input type="text" id="date-picker-procurement-mobile" name="date_display"
                                            value="{{ $dateValue }}" placeholder="dd-mm-yyyy" readonly
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Tahun</label>
                                        <select name="year"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Status NQR</label>
                                        <select name="status_nqr"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            <option value="Claim" {{ request('status_nqr') == 'Claim' ? 'selected' : '' }}>Claim</option>
                                            <option value="Complaint" {{ request('status_nqr') == 'Complaint' ? 'selected' : '' }}>Complaint</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                        <select name="approval_status"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            <option value="menunggu_foreman" {{ request('approval_status') == 'menunggu_foreman' ? 'selected' : '' }}>Menunggu Foreman</option>
                                            <option value="menunggu_sect" {{ request('approval_status') == 'menunggu_sect' ? 'selected' : '' }}>Menunggu Sect</option>
                                            <option value="menunggu_dept" {{ request('approval_status') == 'menunggu_dept' ? 'selected' : '' }}>Menunggu Dept</option>
                                            <option value="menunggu_ppc" {{ request('approval_status') == 'menunggu_ppc' ? 'selected' : '' }}>Menunggu PPC</option>
                                            <option value="menunggu_vdd" {{ request('approval_status') == 'menunggu_vdd' ? 'selected' : '' }}>Menunggu VDD</option>
                                            <option value="menunggu_procurement" {{ request('approval_status') == 'menunggu_procurement' ? 'selected' : '' }}>Menunggu Procurement</option>
                                            <option value="ditolak_foreman" {{ request('approval_status') == 'ditolak_foreman' ? 'selected' : '' }}>Ditolak Foreman</option>
                                            <option value="ditolak_sect" {{ request('approval_status') == 'ditolak_sect' ? 'selected' : '' }}>Ditolak Sect</option>
                                            <option value="ditolak_dept" {{ request('approval_status') == 'ditolak_dept' ? 'selected' : '' }}>Ditolak Dept</option>
                                            <option value="ditolak_ppc" {{ request('approval_status') == 'ditolak_ppc' ? 'selected' : '' }}>Ditolak PPC</option>
                                            <option value="ditolak_vdd" {{ request('approval_status') == 'ditolak_vdd' ? 'selected' : '' }}>Ditolak VDD</option>
                                            <option value="ditolak_procurement" {{ request('approval_status') == 'ditolak_procurement' ? 'selected' : '' }}>Ditolak Procurement</option>
                                            <option value="selesai" {{ request('approval_status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 pt-1">
                                    <button type="submit"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">Terapkan</button>
                                    <a href="{{ route('procurement.nqr.index') }}"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">Reset</a>
                                </div>
                            </div>

                            {{-- Desktop Layout: Horizontal --}}
                            <div class="hidden lg:flex gap-2 items-end">
                                <div class="flex-1 min-w-0">
                                    <label class="text-xs text-gray-600 font-medium">Pencarian</label>
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari no reg, supplier, part, PO..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Tanggal</label>
                                    <input type="text" id="date-picker-procurement" name="date_display"
                                        value="{{ $dateValue }}" placeholder="dd-mm-yyyy" readonly
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" />
                                </div>

                                <div class="w-28">
                                    <label class="text-xs text-gray-600 font-medium">Tahun</label>
                                    <select name="year"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Status NQR</label>
                                    <select name="status_nqr"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                        <option value="Claim" {{ request('status_nqr') == 'Claim' ? 'selected' : '' }}>Claim
                                        </option>
                                        <option value="Complaint" {{ request('status_nqr') == 'Complaint' ? 'selected' : '' }}>Complaint</option>
                                    </select>
                                </div>

                                <div class="w-40">
                                    <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                        <select name="approval_status"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">Semua</option>
                                            <option value="menunggu_foreman" {{ request('approval_status') == 'menunggu_foreman' ? 'selected' : '' }}>Menunggu Foreman</option>
                                            <option value="menunggu_sect" {{ request('approval_status') == 'menunggu_sect' ? 'selected' : '' }}>Menunggu Sect</option>
                                            <option value="menunggu_dept" {{ request('approval_status') == 'menunggu_dept' ? 'selected' : '' }}>Menunggu Dept</option>
                                            <option value="menunggu_ppc" {{ request('approval_status') == 'menunggu_ppc' ? 'selected' : '' }}>Menunggu PPC</option>
                                            <option value="menunggu_vdd" {{ request('approval_status') == 'menunggu_vdd' ? 'selected' : '' }}>Menunggu VDD</option>
                                            <option value="menunggu_procurement" {{ request('approval_status') == 'menunggu_procurement' ? 'selected' : '' }}>Menunggu Procurement</option>
                                            <option value="ditolak_foreman" {{ request('approval_status') == 'ditolak_foreman' ? 'selected' : '' }}>Ditolak Foreman</option>
                                            <option value="ditolak_sect" {{ request('approval_status') == 'ditolak_sect' ? 'selected' : '' }}>Ditolak Sect</option>
                                            <option value="ditolak_dept" {{ request('approval_status') == 'ditolak_dept' ? 'selected' : '' }}>Ditolak Dept</option>
                                            <option value="ditolak_ppc" {{ request('approval_status') == 'ditolak_ppc' ? 'selected' : '' }}>Ditolak PPC</option>
                                            <option value="ditolak_vdd" {{ request('approval_status') == 'ditolak_vdd' ? 'selected' : '' }}>Ditolak VDD</option>
                                            <option value="ditolak_procurement" {{ request('approval_status') == 'ditolak_procurement' ? 'selected' : '' }}>Ditolak Procurement</option>
                                        <option value="selesai" {{ request('approval_status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>

                                <div class="flex gap-2 items-center flex-shrink-0">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md whitespace-nowrap transition-colors">Terapkan</button>
                                    <a href="{{ route('procurement.nqr.index') }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md whitespace-nowrap transition-colors">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="responsive-table overflow-x-auto rounded-md ring-1 ring-gray-50">
                        @if($nqrs->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-red-600 text-white">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-44">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tl-lg">No
                                                Reg</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Tanggal
                                                Terbit</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Supplier</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Nama</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">No
                                                Part</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Status
                                                NQR</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-36">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Status
                                                Approval</span>
                                        </th>
                                        <th class="px-3 py-2 text-center hidden sm:table-cell w-28">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tr-lg">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($nqrs as $nqr)
                                                            <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition-colors"
                                                                data-nqr-id="{{ $nqr->id }}">
                                                                <td class="px-3 py-3 text-sm text-gray-900">{{ $nqr->no_reg_nqr }}</td>
                                                                <td class="px-3 py-3 text-sm text-gray-900">
                                                                    {{ $nqr->tgl_terbit_nqr ? $nqr->tgl_terbit_nqr->format('d-m-Y') : '-' }}
                                                                </td>
                                                                <td class="px-3 py-3 text-sm text-gray-900">{{ $nqr->nama_supplier }}</td>
                                                                <td class="px-3 py-3 text-sm text-gray-900">{{ $nqr->nama_part }}</td>
                                                                <td class="px-3 py-3 text-sm text-gray-900">{{ $nqr->nomor_part }}</td>
                                                                <td class="px-3 py-3 text-sm text-gray-900 text-center">
                                                                    @php
                                                                        $statusNqr = $nqr->status_nqr;
                                                                        $badgeClass = $statusNqr === 'Claim' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800';
                                                                    @endphp
                                         <span
                                                                        class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">
                                                                        {{ $statusNqr }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-3 py-3 text-sm text-gray-900 status-approval-cell">
                                                                    <div class="font-medium">
                                                                        {{ $nqr->status_approval ?? 'Menunggu Request dikirimkan' }}</div>
                                                                </td>

                                                                <!-- Desktop actions cell -->
                                                                <td class="px-3 py-3 text-center text-sm hidden sm:table-cell">
                                                                    <div class="flex items-center justify-center gap-1 action-buttons-container">
                                                                        @if($nqr->status_approval === 'Menunggu Approval Procurement')
                                                                            <div class="flex flex-col items-center">
                                                                                <button type="button"
                                                                                    data-url="{{ route('procurement.nqr.approve', $nqr->id) }}"
                                                                                    data-input-url="{{ route('procurement.nqr.inputPayCompensation', $nqr->id) }}"
                                                                                    data-noreg="{{ $nqr->no_reg_nqr }}"
                                                                                    class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                                                                                    title="Approve">
                                                                                    <img src="{{ asset('icon/approve.ico') }}" alt="Approve"
                                                                                        class="w-4 h-4" />
                                                                                </button>
                                                                                <span class="text-xs text-gray-500 mt-1">Approve</span>
                                                                            </div>
                                                                            <div class="flex flex-col items-center">
                                                                                <button type="button"
                                                                                    data-url="{{ route('procurement.nqr.reject', $nqr->id) }}"
                                                                                    data-noreg="{{ $nqr->no_reg_nqr }}"
                                                                                    class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                                                                                    title="Reject">
                                                                                    <img src="{{ asset('icon/cancel.ico') }}" alt="Reject"
                                                                                        class="w-4 h-4" />
                                                                                </button>
                                                                                <span class="text-xs text-gray-500 mt-1">Reject</span>
                                                                            </div>
                                                                        @endif
                                                                        @if(
                                                                                in_array($nqr->status_approval, [
                                                                                    'Menunggu Approval Procurement',
                                                                                    'Ditolak Procurement',
                                                                                    'Selesai',
                                                                                ])
                                                                            )
                                                                            <div class="flex flex-col items-center">
                                                                                <a href="{{ route('procurement.nqr.previewFpdf', $nqr->id) }}" target="_blank"
                                                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                                                                                    title="Preview PDF (FPDF) - Print Preview">
                                                                                    <img src="{{ asset('icon/pdf.ico') }}" alt="Preview PDF"
                                                                                        class="w-4 h-4" />
                                                                                </a>
                                                                                <span class="text-xs mt-1">PDF</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-12 bg-white">
                                <p class="text-gray-500 text-sm">Tidak ada NQR yang perlu di-approve.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Pagination moved inside card to add spacing from card border --}}
                    <div class="mt-6 border-t border-gray-100 pt-4">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-medium">{{ $nqrs->firstItem() ?? 0 }}</span> - <span
                                    class="font-medium">{{ $nqrs->lastItem() ?? 0 }}</span> dari <span
                                    class="font-medium">{{ $nqrs->total() }}</span> data
                            </div>

                            <nav class="flex items-center gap-3" aria-label="Pagination">
                                @php $prev = $nqrs->previousPageUrl();
                                $next = $nqrs->nextPageUrl(); @endphp

                                {{-- Previous with chevron --}}
                                <a href="{{ $prev ?: '#' }}"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border {{ $nqrs->onFirstPage() ? 'text-gray-400 border-gray-200 pointer-events-none bg-white' : 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' }}"
                                    aria-disabled="{{ $nqrs->onFirstPage() ? 'true' : 'false' }}">
                                    <span class="text-sm">
                                        < Sebelumnya</span>
                                </a>

                                {{-- Page pill --}}
                                <div
                                    class="hidden sm:inline-flex items-center px-3 py-2 bg-white border border-gray-100 rounded-full shadow-sm text-sm text-gray-700">
                                    Halaman <span class="mx-2 font-semibold">{{ $nqrs->currentPage() }}</span> dari <span
                                        class="mx-2 font-medium">{{ $nqrs->lastPage() }}</span>
                                </div>

                                {{-- Next with chevron --}}
                                <a href="{{ $next ?: '#' }}"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border {{ $nqrs->hasMorePages() ? 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' : 'text-gray-400 border-gray-200 pointer-events-none bg-white' }}"
                                    aria-disabled="{{ $nqrs->hasMorePages() ? 'false' : 'true' }}">
                                    <span class="text-sm">Berikutnya ></span>
                                </a>

                                {{-- Compact mobile page indicator --}}
                                <div class="sm:hidden px-3 py-1 text-xs text-gray-600">Hal. <span
                                        class="font-medium">{{ $nqrs->currentPage() }}</span>/<span
                                        class="font-medium">{{ $nqrs->lastPage() }}</span></div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div id="approve-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Approve</h3>
            <p id="approve-modal-msg" class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin Approve NQR ini?</p>
            <div class="flex justify-end gap-3">
                <button id="approve-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <form id="approve-form" method="POST" action="">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Approve</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Reject</h3>
            <p id="reject-modal-msg" class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin Reject NQR ini?</p>
            <div class="flex justify-end gap-3">
                <button id="reject-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <form id="reject-form" method="POST" action="">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Reject</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toast notification helper
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-[9999] px-6 py-3 rounded-lg shadow-lg text-white font-medium transition-all duration-300 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Helper to close modal
            function closeModal(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            // Helper to open modal
            function openModal(modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            // Helper to rebind event listeners after dynamic content update
            function rebindEventListeners() {
                document.querySelectorAll('.open-approve-modal').forEach(btn => {
                    btn.removeEventListener('click', handleApproveClick);
                    btn.addEventListener('click', handleApproveClick);
                });

                document.querySelectorAll('.open-reject-modal').forEach(btn => {
                    btn.removeEventListener('click', handleRejectClick);
                    btn.addEventListener('click', handleRejectClick);
                });
            }

            // Approve modal with AJAX
            const approveModal = document.getElementById('approve-modal');
            const approveForm = document.getElementById('approve-form');
            const approveCancel = document.getElementById('approve-cancel');
            const approveMsg = document.getElementById('approve-modal-msg');
            let currentApproveUrl = '';
            let currentApproveNqrId = '';

            function handleApproveClick() {
                // If a procurement-specific input page is provided, redirect the user there
                const inputUrl = this.getAttribute('data-input-url');
                if (inputUrl) {
                    window.location.href = inputUrl;
                    return;
                }

                currentApproveUrl = this.getAttribute('data-url');
                const noreg = this.getAttribute('data-noreg');

                const row = this.closest('tr[data-nqr-id]');
                currentApproveNqrId = row ? row.getAttribute('data-nqr-id') : '';

                if (approveMsg) approveMsg.textContent = 'Apakah Anda yakin ingin menyetujui NQR ' + noreg + '?';
                openModal(approveModal);
            }

            if (approveModal && approveForm) {
                document.querySelectorAll('.open-approve-modal').forEach(btn => {
                    btn.addEventListener('click', handleApproveClick);
                });

                if (approveCancel) {
                    approveCancel.addEventListener('click', function () {
                        closeModal(approveModal);
                    });
                }

                approveModal.addEventListener('click', function (e) {
                    if (e.target === approveModal) {
                        closeModal(approveModal);
                    }
                });

                // AJAX submit for approve form
                approveForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    fetch(currentApproveUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({})
                    })
                        .then(response => response.json())
                        .then(data => {
                            closeModal(approveModal);
                            if (data.success) {
                                showToast(data.message, 'success');
                                const row = document.querySelector(`tr[data-nqr-id="${currentApproveNqrId}"]`);
                                if (row) {
                                    const statusCell = row.querySelector('.status-approval-cell');
                                    if (statusCell) {
                                        statusCell.innerHTML = `<div class="font-medium">${data.newStatusText}</div>`;
                                    }
                                    const actionsContainer = row.querySelector('.action-buttons-container');
                                    if (actionsContainer && data.actionButtonsHtml) {
                                        actionsContainer.innerHTML = data.actionButtonsHtml;
                                        rebindEventListeners();
                                    }
                                }
                            } else {
                                showToast(data.message || 'Terjadi kesalahan', 'error');
                            }
                        })
                        .catch(error => {
                            closeModal(approveModal);
                            showToast('Terjadi kesalahan: ' + error.message, 'error');
                        });
                });
            }

            // Reject modal with AJAX
            const rejectModal = document.getElementById('reject-modal');
            const rejectForm = document.getElementById('reject-form');
            const rejectCancel = document.getElementById('reject-cancel');
            const rejectMsg = document.getElementById('reject-modal-msg');
            let currentRejectUrl = '';
            let currentRejectNqrId = '';

            function handleRejectClick() {
                currentRejectUrl = this.getAttribute('data-url');
                const noreg = this.getAttribute('data-noreg');

                const row = this.closest('tr[data-nqr-id]');
                currentRejectNqrId = row ? row.getAttribute('data-nqr-id') : '';

                if (rejectMsg) rejectMsg.textContent = 'Apakah Anda yakin ingin menolak NQR ' + noreg + '?';
                openModal(rejectModal);
            }

            if (rejectModal && rejectForm) {
                document.querySelectorAll('.open-reject-modal').forEach(btn => {
                    btn.addEventListener('click', handleRejectClick);
                });

                if (rejectCancel) {
                    rejectCancel.addEventListener('click', function () {
                        closeModal(rejectModal);
                    });
                }

                rejectModal.addEventListener('click', function (e) {
                    if (e.target === rejectModal) {
                        closeModal(rejectModal);
                    }
                });

                // AJAX submit for reject form
                rejectForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    fetch(currentRejectUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({})
                    })
                        .then(response => response.json())
                        .then(data => {
                            closeModal(rejectModal);
                            if (data.success) {
                                showToast(data.message, 'success');
                                const row = document.querySelector(`tr[data-nqr-id="${currentRejectNqrId}"]`);
                                if (row) {
                                    const statusCell = row.querySelector('.status-approval-cell');
                                    if (statusCell) {
                                        statusCell.innerHTML = `<div class="font-medium">${data.newStatusText}</div>`;
                                    }
                                    const actionsContainer = row.querySelector('.action-buttons-container');
                                    if (actionsContainer && data.actionButtonsHtml) {
                                        actionsContainer.innerHTML = data.actionButtonsHtml;
                                        rebindEventListeners();
                                    }
                                }
                            } else {
                                showToast(data.message || 'Terjadi kesalahan', 'error');
                            }
                        })
                        .catch(error => {
                            closeModal(rejectModal);
                            showToast('Terjadi kesalahan: ' + error.message, 'error');
                        });
                });
            }

            // Flatpickr init helper
            function initFlatpickr(fp) {
                var locale = {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    },
                };

                ['#date-picker-procurement', '#date-picker-procurement-mobile'].forEach(function (selector) {
                    var el = document.querySelector(selector);
                    if (!el) return;
                    try {
                        fp(el, {
                            dateFormat: 'd-m-Y',
                            allowInput: true,
                            defaultDate: el.value ? el.value : undefined,
                            locale: locale,
                            onOpen: function(selectedDates, dateStr, instance) { if (!instance.input.value) instance.jumpToDate(new Date()); },
                            onChange: function (selectedDates, dateStr) {
                                // update hidden ISO input
                                var hidden = document.getElementById('date-hidden');
                                if (!hidden) return;
                                if (!dateStr) { hidden.value = ''; return; }
                                var m = dateStr.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
                                if (m) {
                                    var dd = m[1].padStart(2, '0'), mm = m[2].padStart(2, '0'), yy = m[3];
                                    hidden.value = yy + '-' + mm + '-' + dd;
                                } else {
                                    hidden.value = dateStr;
                                }
                            }
                        });
                    } catch (err) {
                        console && console.error('flatpickr init error', err);
                    }
                });

                // Submit-time sync fallback: ensure hidden date is set before submit
                var filterForm = document.getElementById('filter-form');
                if (filterForm) {
                    filterForm.addEventListener('submit', function () {
                        try {
                            var desktop = document.getElementById('date-picker-procurement');
                            var mobile = document.getElementById('date-picker-procurement-mobile');
                            var visible = desktop && desktop.value ? desktop : (mobile && mobile.value ? mobile : null);
                            var hidden = document.getElementById('date-hidden');
                            if (!hidden) return;
                            var val = visible ? visible.value.trim() : '';
                            if (!val) { hidden.value = ''; return; }
                            var m = val.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
                            if (m) {
                                var dd = m[1].padStart(2, '0'), mm = m[2].padStart(2, '0'), yy = m[3];
                                hidden.value = yy + '-' + mm + '-' + dd;
                            } else {
                                var m2 = val.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
                                if (m2) {
                                    hidden.value = m2[1] + '-' + m2[2].padStart(2, '0') + '-' + m2[3].padStart(2, '0');
                                } else {
                                    hidden.value = val;
                                }
                            }
                        } catch (err) {
                            console && console.error('date sync error', err);
                        }
                    });
                }
            }

            // If flatpickr already present, initialize immediately
            if (window.flatpickr) {
                initFlatpickr(window.flatpickr);
                return;
            }

            // Load local flatpickr CSS + JS
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = '{{ asset("vendor/flatpickr/flatpickr.min.css") }}';
            document.head.appendChild(link);

            var s = document.createElement('script');
            s.src = '{{ asset("vendor/flatpickr/flatpickr.min.js") }}';
            s.onload = function () {
                if (window.flatpickr) {
                    initFlatpickr(window.flatpickr);
                } else {
                    console && console.error('flatpickr failed to initialize from local asset.');
                }
            };
            document.body.appendChild(s);
        });
    </script>
@endsection
