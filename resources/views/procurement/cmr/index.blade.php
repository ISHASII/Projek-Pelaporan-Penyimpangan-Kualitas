@extends('layouts.navbar')

@section('content')
    <div class="w-full m-0 p-0 -mt-0">
        <div class="m-0">
            <div
                class="bg-white rounded-none shadow-none overflow-hidden border-t-0 border-l-0 border-r-0 border-gray-100 w-full">
                <div class="p-6">
                    <form id="filter-form" method="GET" action="{{ route('procurement.cmr.index') }}" class="mb-4">
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
                            <input type="hidden" name="date" id="date-hidden" value="{{ request('date') }}" />

                            <div class="block lg:hidden space-y-2">
                                <div>
                                    <label class="text-xs text-gray-600 font-medium">Search</label>
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Search no reg, supplier, part..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5" />
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Date</label>
                                        <input type="text" id="date-picker-cmr-mobile" name="date_display"
                                            value="{{ $dateValue }}" placeholder="dd-mm-yyyy" readonly
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5" />
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Year</label>
                                        <select name="year"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                            <option value="">Semua</option>
                                            @if(!empty($years) && count($years))
                                                @foreach($years as $y)
                                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}
                                                    </option>
                                                @endforeach
                                            @else
                                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}
                                                    </option>
                                                @endfor
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Product</label>
                                        <select name="product"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                            <option value="">Semua</option>
                                            <option value="SKA" {{ request('product') == 'SKA' ? 'selected' : '' }}>SKA
                                            </option>
                                            <option value="OCU" {{ request('product') == 'OCU' ? 'selected' : '' }}>OCU
                                            </option>
                                            <option value="FF" {{ request('product') == 'FF' ? 'selected' : '' }}>FF</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                        <select name="approval_status"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                            <option value="">All</option>
                                            <option value="pending_request" {{ request('approval_status') == 'pending_request' ? 'selected' : '' }}>Pending Request</option>
                                            <option value="waiting_sect" {{ request('approval_status') == 'waiting_sect' ? 'selected' : '' }}>Waiting For Sect Head</option>
                                            <option value="waiting_dept" {{ request('approval_status') == 'waiting_dept' ? 'selected' : '' }}>Waiting For Dept Head</option>
                                            <option value="waiting_agm" {{ request('approval_status') == 'waiting_agm' ? 'selected' : '' }}>Waiting For AGM</option>
                                            <option value="waiting_ppc" {{ request('approval_status') == 'waiting_ppc' ? 'selected' : '' }}>Waiting For PPC Head</option>
                                            <option value="waiting_procurement" {{ request('approval_status') == 'waiting_procurement' ? 'selected' : '' }}>
                                                Waiting For Procurement</option>
                                            <option value="rejected_sect" {{ request('approval_status') == 'rejected_sect' ? 'selected' : '' }}>Rejected By Sect Head</option>
                                            <option value="rejected_dept" {{ request('approval_status') == 'rejected_dept' ? 'selected' : '' }}>Rejected By Dept Head</option>
                                            <option value="rejected_agm" {{ request('approval_status') == 'rejected_agm' ? 'selected' : '' }}>Rejected By AGM</option>
                                            <option value="rejected_ppc" {{ request('approval_status') == 'rejected_ppc' ? 'selected' : '' }}>Rejected By PPC Head</option>
                                            <option value="rejected_procurement" {{ request('approval_status') == 'rejected_procurement' ? 'selected' : '' }}>
                                                Rejected By Procurement</option>
                                            <option value="completed" {{ request('approval_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 pt-1">
                                    <button type="submit"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-yellow-600 text-white text-sm font-medium rounded-md">Apply</button>
                                    <a href="{{ route('procurement.cmr.index') }}"
                                        class="inline-flex justify-center items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md">Reset</a>
                                </div>
                            </div>

                            <div class="hidden lg:flex gap-2 items-end">
                                <div class="flex-1 min-w-0">
                                    <label class="text-xs text-gray-600 font-medium">Search</label>
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Search no reg, supplier, part, PO..."
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5" />
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Date</label>
                                    <input type="text" id="date-picker-cmr" name="date_display" value="{{ $dateValue }}"
                                        placeholder="dd-mm-yyyy" readonly
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5" />
                                </div>

                                <div class="w-28">
                                    <label class="text-xs text-gray-600 font-medium">Year</label>
                                    <select name="year"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                        <option value="">Semua</option>
                                        @if(!empty($years) && count($years))
                                            @foreach($years as $y)
                                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}
                                                </option>
                                            @endforeach
                                        @else
                                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}
                                                </option>
                                            @endfor
                                        @endif
                                    </select>
                                </div>

                                <div class="w-36">
                                    <label class="text-xs text-gray-600 font-medium">Product</label>
                                    <select name="product"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                        <option value="">Semua</option>
                                        <option value="SKA" {{ request('product') == 'SKA' ? 'selected' : '' }}>SKA</option>
                                        <option value="OCU" {{ request('product') == 'OCU' ? 'selected' : '' }}>OCU</option>
                                        <option value="FF" {{ request('product') == 'FF' ? 'selected' : '' }}>FF</option>
                                    </select>
                                </div>

                                <div class="w-48">
                                    <label class="text-xs text-gray-600 font-medium">Status Approval</label>
                                    <select name="approval_status"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm px-2.5 py-1.5">
                                        <option value="">All</option>
                                        <option value="pending_request" {{ request('approval_status') == 'pending_request' ? 'selected' : '' }}>Pending Request</option>
                                        <option value="waiting_sect" {{ request('approval_status') == 'waiting_sect' ? 'selected' : '' }}>Waiting For Sect Head</option>
                                        <option value="waiting_dept" {{ request('approval_status') == 'waiting_dept' ? 'selected' : '' }}>Waiting For Dept Head</option>
                                        <option value="waiting_agm" {{ request('approval_status') == 'waiting_agm' ? 'selected' : '' }}>Waiting For AGM</option>
                                        <option value="waiting_ppc" {{ request('approval_status') == 'waiting_ppc' ? 'selected' : '' }}>Waiting For PPC Head</option>
                                        <option value="waiting_procurement" {{ request('approval_status') == 'waiting_procurement' ? 'selected' : '' }}>Waiting
                                            For Procurement</option>
                                        <option value="rejected_sect" {{ request('approval_status') == 'rejected_sect' ? 'selected' : '' }}>Rejected By Sect Head</option>
                                        <option value="rejected_dept" {{ request('approval_status') == 'rejected_dept' ? 'selected' : '' }}>Rejected By Dept Head</option>
                                        <option value="rejected_agm" {{ request('approval_status') == 'rejected_agm' ? 'selected' : '' }}>Rejected By AGM</option>
                                        <option value="rejected_ppc" {{ request('approval_status') == 'rejected_ppc' ? 'selected' : '' }}>Rejected By PPC Head</option>
                                        <option value="rejected_procurement" {{ request('approval_status') == 'rejected_procurement' ? 'selected' : '' }}>Rejected
                                            By Procurement</option>
                                        <option value="completed" {{ request('approval_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>

                                <div class="flex gap-2 items-center flex-shrink-0">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white text-sm font-medium rounded-md">Apply</button>
                                    <a href="{{ route('procurement.cmr.index') }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="responsive-table overflow-x-auto rounded-md ring-1 ring-gray-50">
                        @if(isset($cmrs) && count($cmrs))
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-red-600 text-white">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">No
                                                Reg</span>
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(登録不要)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">CMR
                                                ISSUE DATE</span>
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(発行日)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">SUPPLIER
                                                NAME </span>
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(サプライヤ名)
                                            </span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-40">
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">PART
                                                NAME</span>
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(部品名)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">PART
                                                NUMBER</span>
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(部品番号)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-32">
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">PRODUCT</span>
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">(製品)</span>
                                        </th>
                                        <th class="px-3 py-2 text-left w-36">
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">Status
                                                Approval</span>
                                            <span
                                                class="inline-block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent">ステータス承認</span>
                                        </th>
                                        <th class="px-3 py-2 text-center hidden sm:table-cell w-28">
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tr-lg">Action</span>
                                            <span
                                                class="block px-2 py-1 text-sm font-medium text-white bg-transparent border border-transparent rounded-tr-lg">アクション</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($cmrs as $i => $cmr)
                                        <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition-colors"
                                            data-cmr-id="{{ $cmr->id }}">
                                            <td class="px-3 py-3 text-sm text-gray-900">{{ $cmr->no_reg }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-900">
                                                {{ $cmr->tgl_terbit_cmr ? (is_string($cmr->tgl_terbit_cmr) ? (strtotime($cmr->tgl_terbit_cmr) ? date('d-m-Y', strtotime($cmr->tgl_terbit_cmr)) : '') : $cmr->tgl_terbit_cmr->format('d-m-Y')) : '' }}
                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-900">{{ $cmr->nama_supplier }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-900">{{ $cmr->nama_part }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-900">{{ $cmr->nomor_part }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-900 text-center">
                                                @php
                                                    $prod = strtoupper(trim($cmr->product ?? ''));
                                                    $badgeClass = 'bg-gray-100 text-gray-800';
                                                    $prodText = $cmr->product ?? '-';
                                                    if ($prod === 'SKA') {
                                                        $badgeClass = 'bg-amber-100 text-amber-800';
                                                    } elseif ($prod === 'OCU') {
                                                        $badgeClass = 'bg-blue-100 text-blue-800';
                                                    } elseif ($prod === 'FF') {
                                                        $badgeClass = 'bg-green-100 text-green-800';
                                                    }
                                                @endphp
                                                <span
                                                    class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">{{ $prodText }}</span>
                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-900 status-approval-cell">
                                                @php
                                                    $sect = strtolower($cmr->secthead_status ?? 'pending');
                                                    $dept = strtolower($cmr->depthead_status ?? 'pending');
                                                    $agm = strtolower($cmr->agm_status ?? 'pending');
                                                    $ppc = strtolower($cmr->ppchead_status ?? 'pending');
                                                    $proc = strtolower($cmr->procurement_status ?? '');

                                                    if (is_null($cmr->requested_at_qc)) {
                                                        $statusMsg = 'Waiting for request to be sent';
                                                    } elseif ($sect === 'rejected') {
                                                        $statusMsg = 'Rejected by Sect Head';
                                                    } elseif ($dept === 'rejected') {
                                                        $statusMsg = 'Rejected by Dept Head';
                                                    } elseif ($agm === 'rejected') {
                                                        $statusMsg = 'Rejected by AGM';
                                                    } elseif ($ppc === 'rejected') {
                                                        $statusMsg = 'Rejected by PPC Head';
                                                    } elseif ($proc === 'rejected') {
                                                        $statusMsg = 'Rejected by Procurement';
                                                    } elseif (in_array('canceled', [$sect, $dept, $agm, $ppc, $proc])) {
                                                        $statusMsg = 'Canceled';
                                                    } elseif ($sect === 'pending') {
                                                        $statusMsg = 'Waiting for Sect Head approval';
                                                    } elseif ($sect === 'approved' && $dept === 'pending') {
                                                        $statusMsg = 'Waiting for Dept Head approval';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'pending') {
                                                        $statusMsg = 'Waiting for AGM approval';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'pending') {
                                                        $statusMsg = 'Waiting for PPC Head approval';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'approved' && $proc === 'pending') {
                                                        $statusMsg = 'Waiting for Procurement approval';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'approved' && $proc === 'approved') {
                                                        $statusMsg = 'Completed';
                                                    } elseif ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'approved' && empty($proc)) {
                                                        $statusMsg = 'Completed';
                                                    } else {
                                                        $statusMsg = $cmr->status_approval ?? '-';
                                                    }
                                                @endphp
                                                <div class="font-medium leading-tight">{{ $statusMsg }}</div>
                                            </td>
                                            <td class="px-3 py-3 text-center text-sm hidden sm:table-cell">
                                                <div class="flex flex-col items-center justify-center gap-2">
                                                    <div class="flex items-center justify-center gap-4 action-buttons-container">
                                                        @php
                                                            $procStatus = strtolower($cmr->procurement_status ?? 'pending');
                                                            // try to detect if PPC compensation already stored
                                                            $ppc_note = null;
                                                            try {
                                                                $decoded = is_string($cmr->ppchead_note) ? json_decode($cmr->ppchead_note, true) : $cmr->ppchead_note;
                                                                if (is_array($decoded) && array_key_exists('ppc', $decoded) && is_array($decoded['ppc'])) {
                                                                    $ppc_note = $decoded['ppc'];
                                                                }
                                                            } catch (\Throwable $e) { $ppc_note = null; }
                                                            $has_compensation = false;
                                                            if (!empty($ppc_note)) {
                                                                if (!empty($ppc_note['nominal']) || (isset($ppc_note['disposition']) && $ppc_note['disposition'] === 'pay_compensation')) {
                                                                    $has_compensation = true;
                                                                }
                                                            }
                                                        @endphp
                                                        {{-- Show approve/reject only when it's Procurement's turn: PPC approved and procurement not yet approved/rejected --}}
                                                        @if(!is_null($cmr->requested_at_qc) && (strtolower($cmr->ppchead_status ?? '') === 'approved') && in_array($procStatus, ['pending', '']))
                                                            @if($has_compensation)
                                                                <div class="flex flex-col items-center gap-1">
                                                                    <button type="button"
                                                                        class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50"
                                                                        title="Approve"
                                                                        data-url="{{ route('procurement.cmr.approve', $cmr->id) }}"
                                                                        data-noreg="{{ $cmr->no_reg }}">
                                                                        <img src="{{ asset('icon/approve.ico') }}" alt="Approve" class="w-4 h-4" />
                                                                    </button>
                                                                    <span class="text-xs text-gray-500 mt-1">Approve</span>
                                                                </div>
                                                            @else
                                                                <div class="flex flex-col items-center gap-1">
                                                                    <a href="{{ route('procurement.cmr.inputCompensation', $cmr->id) }}"
                                                                        title="Input Compensation"
                                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50">
                                                                        <img src="{{ asset('icon/approve.ico') }}" alt="Input" class="w-4 h-4" />
                                                                    </a>
                                                                    <span class="text-xs text-gray-500 mt-1">Input</span>
                                                                </div>
                                                            @endif
                                                            <div class="flex flex-col items-center gap-1">
                                                                <button type="button"
                                                                    class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50"
                                                                    title="Tolak"
                                                                    data-url="{{ route('procurement.cmr.reject', $cmr->id) }}"
                                                                    data-noreg="{{ $cmr->no_reg }}">
                                                                    <img src="{{ asset('icon/cancel.ico') }}" alt="Reject" class="w-4 h-4" />
                                                                </button>
                                                                <span class="text-xs text-gray-500 mt-1">Reject</span>
                                                            </div>
                                                        @endif
                                                        @if(!is_null($cmr->requested_at_qc))
                                                            <div class="flex flex-col items-center gap-1">
                                                                <a href="{{ route('procurement.cmr.previewFpdf', $cmr->id) }}"
                                                                    target="_blank"
                                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100"
                                                                    title="Preview PDF">
                                                                    <img src="{{ asset('icon/pdf.ico') }}" alt="PDF" class="w-4 h-4" />
                                                                </a>
                                                                <span class="text-xs text-gray-500 mt-1">PDF</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-12 text-gray-500">
                                <div class="text-lg font-medium">Tidak ada data CMR</div>
                                <div class="text-sm">Belum ada CMR yang sesuai dengan filter yang dipilih.</div>
                            </div>
                        @endif
                    </div>

                    @if(isset($cmrs) && $cmrs->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            <div class="flex items-center justify-between">
                                <nav class="flex items-center justify-center space-x-2 sm:justify-between w-full">
                                    @php $prev = $cmrs->previousPageUrl();
                                    $next = $cmrs->nextPageUrl(); @endphp

                                    <a href="{{ $prev ?: '#' }}"
                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border {{ $cmrs->onFirstPage() ? 'text-gray-400 border-gray-200 pointer-events-none bg-white' : 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' }}">
                                        <span class="text-sm">
                                            < Sebelumnya</span>
                                    </a>

                                    <div
                                        class="hidden sm:inline-flex items-center px-3 py-2 bg-white border border-gray-100 rounded-full shadow-sm text-sm text-gray-700">
                                        Halaman <span class="mx-2 font-semibold">{{ $cmrs->currentPage() }}</span> dari <span
                                            class="mx-2 font-medium">{{ $cmrs->lastPage() }}</span>
                                    </div>

                                    <a href="{{ $next ?: '#' }}"
                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border {{ $cmrs->hasMorePages() ? 'text-gray-600 border-gray-200 bg-white hover:bg-gray-50 shadow-sm' : 'text-gray-400 border-gray-200 pointer-events-none bg-white' }}">
                                        <span class="text-sm">Berikutnya &gt;</span>
                                    </a>

                                    <div class="sm:hidden px-3 py-1 text-xs text-gray-600">Hal. <span
                                            class="font-medium">{{ $cmrs->currentPage() }}</span>/<span
                                            class="font-medium">{{ $cmrs->lastPage() }}</span></div>
                                </nav>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve and Reject modals for Procurement -->
    <div id="approve-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Approve</h3>
            <p id="approve-modal-msg" class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin Approve CMR ini?</p>
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
            <p id="reject-modal-msg" class="text-sm text-gray-700 mb-6">Apakah Anda yakin ingin Reject CMR ini?</p>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // flatpickr init (reuse same logic as depthead)
                (function attachCalendar() {
                    function init(fp) {
                        var locale = {
                            firstDayOfWeek: 1,
                            weekdays: { shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'], longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] },
                            months: { shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] }
                        };

                        ['#date-picker-cmr', '#date-picker-cmr-mobile'].forEach(function (selector) {
                            var el = document.querySelector(selector); if (!el) return;
                            try { fp(el, { dateFormat: 'd-m-Y', allowInput: true, defaultDate: el.value ? el.value : undefined, locale: locale }); } catch (e) { console && console.error(e); }
                        });
                    }

                    if (window.flatpickr) { init(window.flatpickr); return; }
                    var link = document.createElement('link'); link.rel = 'stylesheet'; link.href = '{{ asset("vendor/flatpickr/flatpickr.min.css") }}'; document.head.appendChild(link);
                    var s = document.createElement('script'); s.src = '{{ asset("vendor/flatpickr/flatpickr.min.js") }}'; s.onload = function () { if (window.flatpickr) init(window.flatpickr); }; document.body.appendChild(s);
                })();

                // Toast notification function
                function showToast(message, type) {
                    var toast = document.createElement('div');
                    toast.className = 'fixed top-6 right-6 z-[100] px-6 py-3 rounded-lg shadow-lg text-white text-sm font-medium transition-all duration-300';
                    toast.style.background = type === 'success' ? '#22c55e' : '#ef4444';
                    toast.textContent = message;
                    document.body.appendChild(toast);
                    setTimeout(function () {
                        toast.style.opacity = '0';
                        setTimeout(function () { toast.remove(); }, 300);
                    }, 2500);
                }

                // Update row after AJAX action
                function updateRowAfterAction(row, data) {
                    var statusCell = row.querySelector('.status-approval-cell');
                    if (statusCell && data.new_status) {
                        statusCell.innerHTML = '<div class="font-medium leading-tight">' + data.new_status + '</div>';
                    }
                    if (data.hide_actions) {
                        var actionsContainer = row.querySelector('.action-buttons-container');
                        if (actionsContainer) {
                            // Keep PDF button only
                            var pdfLink = actionsContainer.querySelector('a[href*="previewFpdf"], a[href*="preview-fpdf"], a[href*="previewPdf"], a[href*="preview-pdf"]');
                            if (pdfLink) {
                                var pdfParent = pdfLink.closest('.flex.flex-col');
                                actionsContainer.innerHTML = '';
                                if (pdfParent) actionsContainer.appendChild(pdfParent);
                            } else {
                                actionsContainer.innerHTML = '<span class="text-xs text-gray-400">Processed</span>';
                            }
                        }
                    }
                }

                // Approve/Reject modal handlers with AJAX
                (function () {
                    const approveModal = document.getElementById('approve-modal');
                    const approveForm = document.getElementById('approve-form');
                    const approveCancel = document.getElementById('approve-cancel');
                    const rejectModal = document.getElementById('reject-modal');
                    const rejectForm = document.getElementById('reject-form');
                    const rejectCancel = document.getElementById('reject-cancel');

                    var currentApproveBtn = null;
                    var currentRejectBtn = null;

                    document.querySelectorAll('.open-approve-modal').forEach(btn => {
                        btn.addEventListener('click', function () {
                            currentApproveBtn = this;
                            var url = this.getAttribute('data-url');
                            var noreg = this.getAttribute('data-noreg');
                            if (approveForm) approveForm.setAttribute('action', url);
                            var msg = document.getElementById('approve-modal-msg'); if (msg) msg.textContent = 'Apakah Anda yakin ingin Approve CMR ' + (noreg || '') + '?';
                            if (approveModal) { approveModal.classList.remove('hidden'); approveModal.classList.add('flex'); }
                        });
                    });

                    document.querySelectorAll('.open-reject-modal').forEach(btn => {
                        btn.addEventListener('click', function () {
                            currentRejectBtn = this;
                            var url = this.getAttribute('data-url');
                            var noreg = this.getAttribute('data-noreg');
                            if (rejectForm) rejectForm.setAttribute('action', url);
                            var msg = document.getElementById('reject-modal-msg'); if (msg) msg.textContent = 'Apakah Anda yakin ingin Reject CMR ' + (noreg || '') + '?';
                            if (rejectModal) { rejectModal.classList.remove('hidden'); rejectModal.classList.add('flex'); }
                        });
                    });

                    if (approveCancel) approveCancel.addEventListener('click', function () { approveModal.classList.add('hidden'); approveModal.classList.remove('flex'); });
                    if (rejectCancel) rejectCancel.addEventListener('click', function () { rejectModal.classList.add('hidden'); rejectModal.classList.remove('flex'); });

                    [approveModal, rejectModal].forEach(function (mod) { if (!mod) return; mod.addEventListener('click', function (e) { if (e.target === mod) { mod.classList.add('hidden'); mod.classList.remove('flex'); } }); });

                    // AJAX for approve form
                    if (approveForm) {
                        approveForm.addEventListener('submit', function (e) {
                            e.preventDefault();
                            var url = this.getAttribute('action');
                            var formData = new FormData(this);
                            var submitBtn = this.querySelector('button[type="submit"]');
                            if (submitBtn) submitBtn.disabled = true;

                            fetch(url, {
                                method: 'POST',
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                                body: formData
                            })
                                .then(function (r) { return r.json(); })
                                .then(function (data) {
                                    approveModal.classList.add('hidden');
                                    approveModal.classList.remove('flex');
                                    if (submitBtn) submitBtn.disabled = false;
                                    if (data.success) {
                                        showToast(data.message || 'CMR approved successfully', 'success');
                                        if (currentApproveBtn) {
                                            var row = currentApproveBtn.closest('tr');
                                            if (row) updateRowAfterAction(row, data);
                                        }
                                    } else {
                                        showToast(data.message || 'Failed to approve CMR', 'error');
                                    }
                                })
                                .catch(function (err) {
                                    approveModal.classList.add('hidden');
                                    approveModal.classList.remove('flex');
                                    if (submitBtn) submitBtn.disabled = false;
                                    showToast('An error occurred', 'error');
                                });
                        });
                    }

                    // AJAX for reject form
                    if (rejectForm) {
                        rejectForm.addEventListener('submit', function (e) {
                            e.preventDefault();
                            var url = this.getAttribute('action');
                            var formData = new FormData(this);
                            var submitBtn = this.querySelector('button[type="submit"]');
                            if (submitBtn) submitBtn.disabled = true;

                            fetch(url, {
                                method: 'POST',
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                                body: formData
                            })
                                .then(function (r) { return r.json(); })
                                .then(function (data) {
                                    rejectModal.classList.add('hidden');
                                    rejectModal.classList.remove('flex');
                                    if (submitBtn) submitBtn.disabled = false;
                                    if (data.success) {
                                        showToast(data.message || 'CMR rejected successfully', 'success');
                                        if (currentRejectBtn) {
                                            var row = currentRejectBtn.closest('tr');
                                            if (row) updateRowAfterAction(row, data);
                                        }
                                    } else {
                                        showToast(data.message || 'Failed to reject CMR', 'error');
                                    }
                                })
                                .catch(function (err) {
                                    rejectModal.classList.add('hidden');
                                    rejectModal.classList.remove('flex');
                                    if (submitBtn) submitBtn.disabled = false;
                                    showToast('An error occurred', 'error');
                                });
                        });
                    }
                })();

                // Sync visible dd-mm-YYYY display into hidden ISO date before submit
                var filterForm = document.getElementById('filter-form');
                if (filterForm) {
                    filterForm.addEventListener('submit', function (e) {
                        try {
                            var desktop = document.getElementById('date-picker-cmr');
                            var mobile = document.getElementById('date-picker-cmr-mobile');
                            var visible = desktop && desktop.value ? desktop : (mobile && mobile.value ? mobile : null);
                            var hidden = document.getElementById('date-hidden'); if (!hidden) return;
                            var val = visible ? visible.value.trim() : '';
                            if (!val) { hidden.value = ''; return; }
                            var m = val.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
                            if (m) { var dd = m[1].padStart(2, '0'), mm = m[2].padStart(2, '0'), yy = m[3]; hidden.value = yy + '-' + mm + '-' + dd; }
                            else { var m2 = val.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/); if (m2) { hidden.value = m2[1] + '-' + m2[2].padStart(2, '0') + '-' + m2[3].padStart(2, '0'); } else { hidden.value = val; } }
                        } catch (err) { console && console.error('date sync error', err); }
                    });
                }
            });
        </script>
    @endpush

@endsection
