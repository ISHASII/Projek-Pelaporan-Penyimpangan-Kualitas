@extends('layouts.navbar')

@section('content')
    <div class="w-full m-0 p-0 -mt-0">
        <div class="m-0">
            <div
                class="bg-white rounded-none shadow-none overflow-hidden border-t-0 border-l-0 border-r-0 border-gray-100 w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">

                    </div>

                    <form method="GET" action="{{ route('qc.nqr.index') }}" class="mb-4">
                        <div class="rounded-md border border-gray-200 p-3 sm:p-4 bg-white shadow-sm">
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
                                        <input type="text" id="date-picker-qc-mobile" name="date"
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
                                            <option value="Complaint (Informasi)" {{ request('status_nqr') == 'Complaint (Informasi)' ? 'selected' : '' }}>Complaint (Informasi)</option>
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

                                <div class="grid grid-cols-3 gap-2 pt-1">
                                    <button type="submit"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">Terapkan</button>
                                    <a href="{{ route('qc.nqr.index') }}"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">Reset</a>
                                    <a href="{{ route('qc.nqr.create') }}"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                                        <span class="text-lg leading-none">+</span>
                                    </a>
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
                                    <input type="text" id="date-picker-qc" name="date" value="{{ request('date') }}"
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
                                        <option value="Complaint (Informasi)" {{ request('status_nqr') == 'Complaint (Informasi)' ? 'selected' : '' }}>Complaint (Informasi)</option>
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
                                    <a href="{{ route('qc.nqr.index') }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-md whitespace-nowrap transition-colors">Reset</a>
                                    <a href="{{ route('qc.nqr.create') }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md whitespace-nowrap transition-colors">
                                        <img src="{{ asset('icon/add.ico') }}" alt="add" class="w-4 h-4 mr-1.5"
                                            style="filter: brightness(0) invert(1);" />
                                        <span>Create</span>
                                    </a>
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
                                                                            {{-- Request Approval (hanya jika Menunggu Request dikirimkan) --}}
                                                                            @if($nqr->status_approval === 'Menunggu Request dikirimkan')
                                                                                <div class="flex flex-col items-center">
                                                                                    <button type="button"
                                                                                        data-url="{{ route('qc.nqr.requestApproval', $nqr->id) }}"
                                                                                        data-noreg="{{ $nqr->no_reg_nqr }}"
                                                                                        data-tgl-terbit="{{ $nqr->tgl_terbit_nqr ? \Carbon\Carbon::parse($nqr->tgl_terbit_nqr)->format('d/m/Y') : '-' }}"
                                                                                        data-supplier="{{ $nqr->nama_supplier ?? '-' }}"
                                                                                        data-nama-part="{{ $nqr->nama_part ?? '-' }}"
                                                                                        data-no-part="{{ $nqr->nomor_part ?? '-' }}"
                                                                                        data-status="{{ $nqr->status_nqr ?? '-' }}"
                                                                                        class="open-request-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-blue-50 transition"
                                                                                        title="Request Approval for {{ $nqr->no_reg_nqr }}">
                                                                                        <img src="{{ asset('icon/send.ico') }}" alt="Request" class="w-4 h-4" />
                                                                                    </button>
                                                                                    <span class="text-xs text-gray-500 mt-1">Request</span>
                                                                                </div>
                                                                            @endif

                                                                            @if($nqr->status_approval === 'Menunggu Approval Foreman')
                                                                                <div class="flex flex-col items-center">
                                                                                    <button type="button" data-url="{{ route('qc.nqr.approve', $nqr->id) }}"
                                                                                        data-noreg="{{ $nqr->no_reg_nqr }}"
                                                                                        class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                                                                                        title="Approve">
                                                                                        <img src="{{ asset('icon/approve.ico') }}" alt="Approve"
                                                                                            class="w-4 h-4" />
                                                                                    </button>
                                                                                    <span class="text-xs text-gray-500 mt-1">Approve</span>
                                                                                </div>
                                                                            @endif

                                                                            @if($nqr->status_approval === 'Menunggu Approval Foreman')
                                                                                <div class="flex flex-col items-center">
                                                                                    <button type="button" data-url="{{ route('qc.nqr.reject', $nqr->id) }}"
                                                                                        data-noreg="{{ $nqr->no_reg_nqr }}"
                                                                                        class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                                                                                        title="Reject">
                                                                                        <img src="{{ asset('icon/cancel.ico') }}" alt="Reject"
                                                                                            class="w-4 h-4" />
                                                                                    </button>
                                                                                    <span class="text-xs text-gray-500 mt-1">Reject</span>
                                                                                </div>
                                                                            @endif

                                                                            @if(!Str::startsWith($nqr->status_approval, 'Ditolak'))
                                                                                @if(in_array($nqr->status_approval, ['Menunggu Request dikirimkan', 'Menunggu Approval Foreman', 'Menunggu Approval Sect Head', 'Menunggu Approval Dept Head', 'Menunggu Approval PPC Head']))
                                                                                    <div class="flex flex-col items-center">
                                                                                        <a href="{{ route('qc.nqr.edit', $nqr->id) }}"
                                                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition"
                                                                                            title="Edit NQR">
                                                                                            <img src="{{ asset('icon/edit.ico') }}" alt="Edit" class="w-4 h-4" />
                                                                                        </a>
                                                                                        <span class="text-xs text-gray-500 mt-1">Edit</span>
                                                                                    </div>
                                                                                @endif
                                                                            @endif

                                                                            @if(!Str::startsWith($nqr->status_approval, 'Ditolak'))
                                                                                @if(in_array($nqr->status_approval, ['Menunggu Request dikirimkan', 'Menunggu Approval Foreman', 'Menunggu Approval Sect Head']))
                                                                                    <div class="flex flex-col items-center">
                                                                                        <button type="button"
                                                                                            class="open-delete-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                                                                                            data-url="{{ route('qc.nqr.destroy', $nqr->id) }}" title="Hapus">
                                                                                            <img src="{{ asset('icon/trash.ico') }}" alt="Delete" class="w-4 h-4" />
                                                                                        </button>
                                                                                        <span class="text-xs text-gray-500 mt-1">Hapus</span>
                                                                                    </div>
                                                                                @endif
                                                                            @endif

                                                                            @if($nqr->status_approval !== 'Menunggu Request dikirimkan')
                                                                                <div class="flex flex-col items-center">
                                                                                    <a href="{{ route('qc.nqr.previewFpdf', $nqr->id) }}" target="_blank"
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
                                <p class="text-gray-500 text-sm">Belum ada data NQR.</p>
                                <a href="{{ route('qc.nqr.create') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm">
                                    <img src="{{ asset('icon/add.ico') }}" alt="add" class="w-4 h-4"
                                        style="filter: brightness(0) invert(1);" />
                                    <span>Create NQR</span>
                                </a>
                            </div>
                        @endif
                    </div>

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

                                <a href="{{ $prev ?: '#' }}"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border {{ $nqrs->onFirstPage() ? 'text-gray-400 border-gray-200 pointer-events-none bg-white' : 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' }}"
                                    aria-disabled="{{ $nqrs->onFirstPage() ? 'true' : 'false' }}">
                                    <span class="text-sm">
                                        < Sebelumnya</span>
                                </a>

                                <div
                                    class="hidden sm:inline-flex items-center px-3 py-2 bg-white border border-gray-100 rounded-full shadow-sm text-sm text-gray-700">
                                    Halaman <span class="mx-2 font-semibold">{{ $nqrs->currentPage() }}</span> dari <span
                                        class="mx-2 font-medium">{{ $nqrs->lastPage() }}</span>
                                </div>

                                <a href="{{ $next ?: '#' }}"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border {{ $nqrs->hasMorePages() ? 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' : 'text-gray-400 border-gray-200 pointer-events-none bg-white' }}"
                                    aria-disabled="{{ $nqrs->hasMorePages() ? 'false' : 'true' }}">
                                    <span class="text-sm">Berikutnya ></span>
                                </a>

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

    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin menghapus data NQR ini? Aksi ini tidak dapat
                dibatalkan.</p>
            <div class="flex justify-end gap-3">
                <button id="delete-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <div id="request-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-3">Konfirmasi Request Persetujuan</h3>

            <p class="text-sm text-gray-600 mb-4">Anda akan mengirim request approval untuk NQR berikut:</p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-3">
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <div class="font-medium text-gray-700">No. Reg:</div>
                    <div class="col-span-2 text-gray-900" id="modal-noreg">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">Tgl Terbit:</div>
                    <div class="col-span-2 text-gray-900" id="modal-tgl-terbit">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">Supplier:</div>
                    <div class="col-span-2 text-gray-900" id="modal-supplier">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">Nama Part:</div>
                    <div class="col-span-2 text-gray-900" id="modal-nama-part">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">No. Part:</div>
                    <div class="col-span-2 text-gray-900" id="modal-no-part">-</div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-t pt-2">
                    <div class="font-medium text-gray-700">Status NQR:</div>
                    <div class="col-span-2">
                        <span id="modal-status-badge"
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium">-</span>
                    </div>
                </div>
            </div>

            <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin mengirim request persetujuan?</p>

            <div class="flex justify-end gap-3 border-t pt-4">
                <button id="request-cancel" type="button"
                    class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition">Batal</button>
                <form id="request-form" method="POST" action="">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2 rounded bg-yellow-600 text-white hover:bg-yellow-700 font-medium transition">Kirim
                        Request</button>
                </form>
            </div>
        </div>
    </div>

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
                // Rebind request modal buttons
                document.querySelectorAll('.open-request-modal').forEach(btn => {
                    btn.removeEventListener('click', handleRequestClick);
                    btn.addEventListener('click', handleRequestClick);
                });

                // Rebind approve modal buttons
                document.querySelectorAll('.open-approve-modal').forEach(btn => {
                    btn.removeEventListener('click', handleApproveClick);
                    btn.addEventListener('click', handleApproveClick);
                });

                // Rebind reject modal buttons
                document.querySelectorAll('.open-reject-modal').forEach(btn => {
                    btn.removeEventListener('click', handleRejectClick);
                    btn.addEventListener('click', handleRejectClick);
                });

                // Rebind delete modal buttons
                document.querySelectorAll('.open-delete-modal').forEach(btn => {
                    btn.removeEventListener('click', handleDeleteClick);
                    btn.addEventListener('click', handleDeleteClick);
                });
            }

            // Delete modal
            const modal = document.getElementById('delete-modal');
            const deleteForm = document.getElementById('delete-form');
            const cancelBtn = document.getElementById('delete-cancel');

            function handleDeleteClick() {
                const url = this.getAttribute('data-url');
                deleteForm.setAttribute('action', url);
                openModal(modal);
            }

            document.querySelectorAll('.open-delete-modal').forEach(btn => {
                btn.addEventListener('click', handleDeleteClick);
            });

            cancelBtn.addEventListener('click', function () {
                closeModal(modal);
            });

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal(modal);
                }
            });

            // Request modal with AJAX
            const requestModal = document.getElementById('request-modal');
            const requestForm = document.getElementById('request-form');
            const requestCancel = document.getElementById('request-cancel');
            let currentRequestUrl = '';
            let currentRequestNqrId = '';

            function handleRequestClick() {
                currentRequestUrl = this.getAttribute('data-url');
                const noreg = this.getAttribute('data-noreg');
                const tglTerbit = this.getAttribute('data-tgl-terbit');
                const supplier = this.getAttribute('data-supplier');
                const namaPart = this.getAttribute('data-nama-part');
                const noPart = this.getAttribute('data-no-part');
                const status = this.getAttribute('data-status');

                // Get nqr ID from closest tr
                const row = this.closest('tr[data-nqr-id]');
                currentRequestNqrId = row ? row.getAttribute('data-nqr-id') : '';

                document.getElementById('modal-noreg').textContent = noreg || '-';
                document.getElementById('modal-tgl-terbit').textContent = tglTerbit || '-';
                document.getElementById('modal-supplier').textContent = supplier || '-';
                document.getElementById('modal-nama-part').textContent = namaPart || '-';
                document.getElementById('modal-no-part').textContent = noPart || '-';

                const statusBadge = document.getElementById('modal-status-badge');
                statusBadge.textContent = status || '-';

                if (status === 'Claim') {
                    statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
                } else if (status && status !== '-') {
                    statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                } else {
                    statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                }

                openModal(requestModal);
            }

            document.querySelectorAll('.open-request-modal').forEach(btn => {
                btn.addEventListener('click', handleRequestClick);
            });

            requestCancel.addEventListener('click', function () {
                closeModal(requestModal);
            });

            requestModal.addEventListener('click', function (e) {
                if (e.target === requestModal) {
                    closeModal(requestModal);
                }
            });

            // AJAX submit for request form
            requestForm.addEventListener('submit', function (e) {
                e.preventDefault();

                fetch(currentRequestUrl, {
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
                        closeModal(requestModal);
                        if (data.success) {
                            showToast(data.message, 'success');
                            // Update status cell
                            const row = document.querySelector(`tr[data-nqr-id="${currentRequestNqrId}"]`);
                            if (row) {
                                const statusCell = row.querySelector('.status-approval-cell');
                                if (statusCell) {
                                    statusCell.innerHTML = `<div class="font-medium">${data.newStatusText}</div>`;
                                }
                                // Update action buttons
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
                        closeModal(requestModal);
                        showToast('Terjadi kesalahan: ' + error.message, 'error');
                    });
            });

            // Approve modal with AJAX
            const approveModal = document.getElementById('approve-modal');
            const approveForm = document.getElementById('approve-form');
            const approveCancel = document.getElementById('approve-cancel');
            const approveMsg = document.getElementById('approve-modal-msg');
            let currentApproveUrl = '';
            let currentApproveNqrId = '';

            function handleApproveClick() {
                currentApproveUrl = this.getAttribute('data-url');
                const noreg = this.getAttribute('data-noreg');

                const row = this.closest('tr[data-nqr-id]');
                currentApproveNqrId = row ? row.getAttribute('data-nqr-id') : '';

                approveMsg.textContent = 'Apakah Anda yakin ingin menyetujui NQR ' + noreg + '?';
                openModal(approveModal);
            }

            document.querySelectorAll('.open-approve-modal').forEach(btn => {
                btn.addEventListener('click', handleApproveClick);
            });

            approveCancel.addEventListener('click', function () {
                closeModal(approveModal);
            });

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

                rejectMsg.textContent = 'Apakah Anda yakin ingin menolak NQR ' + noreg + '?';
                openModal(rejectModal);
            }

            document.querySelectorAll('.open-reject-modal').forEach(btn => {
                btn.addEventListener('click', handleRejectClick);
            });

            rejectCancel.addEventListener('click', function () {
                closeModal(rejectModal);
            });

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

        });
    </script>

    <!-- Flatpickr: load local (offline) asset and initialize pickers like other index views -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                (function attachCalendar() {
                    function init(fp) {
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

                        ['#date-picker-qc', '#date-picker-qc-mobile'].forEach(function (selector) {
                            var el = document.querySelector(selector);
                            if (!el) return;
                            try {
                                fp(el, {
                                    dateFormat: 'd-m-Y',
                                    allowInput: true,
                                    defaultDate: el.value ? el.value : undefined,
                                    locale: locale
                                });
                            } catch (err) {
                                console && console.error('flatpickr init error', err);
                            }
                        });
                    }

                    if (window.flatpickr) {
                        init(window.flatpickr);
                        return;
                    }

                    var link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = '{{ asset("vendor/flatpickr/flatpickr.min.css") }}';
                    document.head.appendChild(link);

                    var s = document.createElement('script');
                    s.src = '{{ asset("vendor/flatpickr/flatpickr.min.js") }}';
                    s.onload = function () {
                        if (window.flatpickr) {
                            init(window.flatpickr);
                        } else {
                            console && console.error('flatpickr failed to initialize from local asset.');
                        }
                    };
                    document.body.appendChild(s);
                })();
            });
        </script>
    @endpush

@endsection
