@extends('layouts.navbar')

@section('content')
    <div class="w-full m-0 p-0 -mt-0">
        <div class="m-0">
            <div
                class="bg-white rounded-none shadow-none overflow-hidden border-t-0 border-l-0 border-r-0 border-gray-100 w-full">
                <div class="p-6">
                    {{-- Search & Filters --}}
                    <form method="GET" action="{{ route('depthead.nqr.index') }}" class="mb-4">
                        <div class="rounded-md border border-gray-200 p-3 sm:p-4 bg-white shadow-sm">
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
                                        <input type="text" id="date-picker-depthead-mobile" name="date"
                                            value="{{ request('date') }}" placeholder="dd-mm-yyyy" readonly
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
                                            <option value="Claim" {{ request('status_nqr') == 'Claim' ? 'selected' : '' }}>
                                                Claim</option>
                                            <option value="Complaint" {{ request('status_nqr') == 'Complaint' ? 'selected' : '' }}>Complaint</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                        <select name="approval_status"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Semua</option>
                                            <option value="menunggu_request" {{ request('approval_status') == 'menunggu_request' ? 'selected' : '' }}>Menunggu
                                                Request</option>
                                            <option value="menunggu_foreman" {{ request('approval_status') == 'menunggu_foreman' ? 'selected' : '' }}>Menunggu
                                                Foreman</option>
                                            <option value="menunggu_sect" {{ request('approval_status') == 'menunggu_sect' ? 'selected' : '' }}>Menunggu Sect</option>
                                            <option value="menunggu_dept" {{ request('approval_status') == 'menunggu_dept' ? 'selected' : '' }}>Menunggu Dept</option>
                                            <option value="menunggu_ppc" {{ request('approval_status') == 'menunggu_ppc' ? 'selected' : '' }}>Menunggu PPC</option>
                                            <option value="ditolak_foreman" {{ request('approval_status') == 'ditolak_foreman' ? 'selected' : '' }}>Ditolak Foreman</option>
                                            <option value="ditolak_sect" {{ request('approval_status') == 'ditolak_sect' ? 'selected' : '' }}>Ditolak Sect</option>
                                            <option value="ditolak_dept" {{ request('approval_status') == 'ditolak_dept' ? 'selected' : '' }}>Ditolak Dept</option>
                                            <option value="ditolak_ppc" {{ request('approval_status') == 'ditolak_ppc' ? 'selected' : '' }}>Ditolak PPC</option>
                                            <option value="selesai" {{ request('approval_status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 pt-1">
                                    <button type="submit"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">Terapkan</button>
                                    <a href="{{ route('depthead.nqr.index') }}"
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
                                    <input type="text" id="date-picker-depthead" name="date" value="{{ request('date') }}"
                                        placeholder="dd-mm-yyyy" readonly
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
                                        <option value="menunggu_request" {{ request('approval_status') == 'menunggu_request' ? 'selected' : '' }}>Menunggu Request</option>
                                        <option value="menunggu_foreman" {{ request('approval_status') == 'menunggu_foreman' ? 'selected' : '' }}>Menunggu Foreman</option>
                                        <option value="menunggu_sect" {{ request('approval_status') == 'menunggu_sect' ? 'selected' : '' }}>Menunggu Sect</option>
                                        <option value="menunggu_dept" {{ request('approval_status') == 'menunggu_dept' ? 'selected' : '' }}>Menunggu Dept</option>
                                        <option value="menunggu_ppc" {{ request('approval_status') == 'menunggu_ppc' ? 'selected' : '' }}>Menunggu PPC</option>
                                        <option value="ditolak_foreman" {{ request('approval_status') == 'ditolak_foreman' ? 'selected' : '' }}>Ditolak Foreman</option>
                                        <option value="ditolak_sect" {{ request('approval_status') == 'ditolak_sect' ? 'selected' : '' }}>Ditolak Sect</option>
                                        <option value="ditolak_dept" {{ request('approval_status') == 'ditolak_dept' ? 'selected' : '' }}>Ditolak Dept</option>
                                        <option value="ditolak_ppc" {{ request('approval_status') == 'ditolak_ppc' ? 'selected' : '' }}>Ditolak PPC</option>
                                        <option value="selesai" {{ request('approval_status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>

                                <div class="flex gap-2 items-center flex-shrink-0">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md whitespace-nowrap transition-colors">Terapkan</button>
                                    <a href="{{ route('depthead.nqr.index') }}"
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
                                                                    {{ $nqr->status_approval }}
                                                                </td>
                                                                <td class="px-3 py-3 text-center text-sm hidden sm:table-cell">
                                                                    <div class="flex items-center justify-center gap-1 action-buttons-container">
                                                                        @if($nqr->status_approval === 'Menunggu Approval Dept Head')
                                                                            <div class="flex flex-col items-center">
                                                                                <button type="button" data-id="{{ $nqr->id }}"
                                                                                    data-noreg="{{ $nqr->no_reg_nqr }}"
                                                                                    class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                                                                                    title="Approve">
                                                                                    <img src="{{ asset('icon/approve.ico') }}" alt="Approve"
                                                                                        class="w-4 h-4" />
                                                                                </button>
                                                                                <span class="text-xs text-gray-500 mt-1">Approve</span>
                                                                            </div>
                                                                            <div class="flex flex-col items-center">
                                                                                <button type="button" data-id="{{ $nqr->id }}"
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
                                                                                    'Menunggu Approval Foreman',
                                                                                    'Menunggu Approval Sect Head',
                                                                                    'Menunggu Approval Dept Head',
                                                                                    'Menunggu Approval PPC Head',
                                                                                    'Ditolak Foreman',
                                                                                    'Ditolak Sect Head',
                                                                                    'Ditolak Dept Head',
                                                                                    'Ditolak PPC Head',
                                                                                    'Selesai',
                                                                                ])
                                                                            )
                                                                            <div class="flex flex-col items-center">
                                                                                <a href="{{ route('depthead.nqr.previewFpdf', $nqr->id) }}" target="_blank"
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
            // Toast Notification System
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-[9999] px-6 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
                toast.textContent = message;
                document.body.appendChild(toast);

                setTimeout(() => toast.classList.remove('translate-x-full'), 100);
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Update row after AJAX action
            function updateRowAfterAction(nqrId, newStatus, newStatusText, action) {
                const row = document.querySelector(`tr[data-nqr-id="${nqrId}"]`);
                if (!row) return;

                // Update status cell
                const statusCell = row.querySelector('.status-approval-cell');
                if (statusCell) {
                    statusCell.textContent = newStatusText;
                }

                // Update action buttons
                const actionContainer = row.querySelector('.action-buttons-container');
                if (actionContainer) {
                    // After approve/reject by dept head, hide action buttons
                    const approveBtn = actionContainer.querySelector('.open-approve-modal');
                    const rejectBtn = actionContainer.querySelector('.open-reject-modal');

                    if (approveBtn) approveBtn.closest('.flex.flex-col').style.display = 'none';
                    if (rejectBtn) rejectBtn.closest('.flex.flex-col').style.display = 'none';

                    // Show PDF button if approved (status changes to Menunggu Approval PPC Head or beyond)
                    if (action === 'approve' && !actionContainer.querySelector('a[href*="previewFpdf"]')) {
                        const pdfDiv = document.createElement('div');
                        pdfDiv.className = 'flex flex-col items-center';
                        pdfDiv.innerHTML = `
                        <a href="/depthead/nqr/${nqrId}/preview-fpdf" target="_blank"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                            title="Preview PDF (FPDF) - Print Preview">
                            <img src="{{ asset('icon/pdf.ico') }}" alt="Preview PDF" class="w-4 h-4" />
                        </a>
                        <span class="text-xs mt-1">PDF</span>
                    `;
                        actionContainer.appendChild(pdfDiv);
                    }
                }
            }

            // Approve modal
            const approveModal = document.getElementById('approve-modal');
            const approveForm = document.getElementById('approve-form');
            const approveCancel = document.getElementById('approve-cancel');
            const approveMsg = document.getElementById('approve-modal-msg');
            let currentApproveId = null;
            let currentApproveNoreg = null;

            document.querySelectorAll('.open-approve-modal').forEach(btn => {
                btn.addEventListener('click', function () {
                    currentApproveId = this.getAttribute('data-id');
                    currentApproveNoreg = this.getAttribute('data-noreg');
                    approveForm.setAttribute('action', `/depthead/nqr/${currentApproveId}/approve`);
                    approveMsg.textContent = 'Apakah Anda yakin ingin menyetujui NQR ' + currentApproveNoreg + '?';
                    approveModal.classList.remove('hidden');
                    approveModal.classList.add('flex');
                });
            });

            approveCancel.addEventListener('click', function () {
                approveModal.classList.add('hidden');
                approveModal.classList.remove('flex');
            });

            approveModal.addEventListener('click', function (e) {
                if (e.target === approveModal) {
                    approveModal.classList.add('hidden');
                    approveModal.classList.remove('flex');
                }
            });

            // AJAX Approve
            approveForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const url = this.getAttribute('action');
                const formData = new FormData(this);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        approveModal.classList.add('hidden');
                        approveModal.classList.remove('flex');

                        if (data.success) {
                            showToast(data.message, 'success');
                            updateRowAfterAction(currentApproveId, data.newStatus, data.newStatusText, 'approve');
                        } else {
                            showToast(data.message || 'Gagal menyetujui NQR', 'error');
                        }
                    })
                    .catch(error => {
                        approveModal.classList.add('hidden');
                        approveModal.classList.remove('flex');
                        showToast('Terjadi kesalahan', 'error');
                    });
            });

            // Reject modal
            const rejectModal = document.getElementById('reject-modal');
            const rejectForm = document.getElementById('reject-form');
            const rejectCancel = document.getElementById('reject-cancel');
            const rejectMsg = document.getElementById('reject-modal-msg');
            let currentRejectId = null;
            let currentRejectNoreg = null;

            document.querySelectorAll('.open-reject-modal').forEach(btn => {
                btn.addEventListener('click', function () {
                    currentRejectId = this.getAttribute('data-id');
                    currentRejectNoreg = this.getAttribute('data-noreg');
                    rejectForm.setAttribute('action', `/depthead/nqr/${currentRejectId}/reject`);
                    rejectMsg.textContent = 'Apakah Anda yakin ingin menolak NQR ' + currentRejectNoreg + '?';
                    rejectModal.classList.remove('hidden');
                    rejectModal.classList.add('flex');
                });
            });

            rejectCancel.addEventListener('click', function () {
                rejectModal.classList.add('hidden');
                rejectModal.classList.remove('flex');
            });

            rejectModal.addEventListener('click', function (e) {
                if (e.target === rejectModal) {
                    rejectModal.classList.add('hidden');
                    rejectModal.classList.remove('flex');
                }
            });

            // AJAX Reject
            rejectForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const url = this.getAttribute('action');
                const formData = new FormData(this);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        rejectModal.classList.add('hidden');
                        rejectModal.classList.remove('flex');

                        if (data.success) {
                            showToast(data.message, 'success');
                            updateRowAfterAction(currentRejectId, data.newStatus, data.newStatusText, 'reject');
                        } else {
                            showToast(data.message || 'Gagal menolak NQR', 'error');
                        }
                    })
                    .catch(error => {
                        rejectModal.classList.add('hidden');
                        rejectModal.classList.remove('flex');
                        showToast('Terjadi kesalahan', 'error');
                    });
            });

            // Initialize Flatpickr for date picker
            if (document.getElementById('date-picker-depthead')) {
                flatpickr("#date-picker-depthead", {
                    dateFormat: "d-m-Y",
                    allowInput: true,
                    locale: {
                        firstDayOfWeek: 1,
                        weekdays: {
                            shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                            longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                        },
                        months: {
                            shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                            longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                        },
                    }
                });
            }
        });
    </script>


    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">

    <script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}"></script>
@endsection
