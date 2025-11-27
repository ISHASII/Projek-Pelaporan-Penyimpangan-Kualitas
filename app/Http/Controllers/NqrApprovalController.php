<?php

namespace App\Http\Controllers;

use App\Models\Nqr;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NqrApprovalRequested;
use App\Notifications\NqrStatusChanged;

class NqrApprovalController extends Controller
{
    /**
     * Helper untuk cek apakah request adalah AJAX
     */
    protected function isAjaxRequest(Request $request = null)
    {
        if ($request) {
            return $request->ajax() || $request->wantsJson();
        }
        return request()->ajax() || request()->wantsJson();
    }

    /**
     * Helper untuk mendapatkan action buttons HTML berdasarkan status dan role
     */
    protected function getActionButtonsHtml($nqr, $role)
    {
        $html = '';
        $statusApproval = $nqr->status_approval;

        if ($role === 'qc') {
            // QC can request approval when status is 'Menunggu Request dikirimkan'
            if ($statusApproval === 'Menunggu Request dikirimkan') {
                $html .= '<div class="flex flex-col items-center">
                    <button type="button"
                        data-url="' . route('qc.nqr.requestApproval', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '"
                        class="open-request-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-blue-50 transition"
                        title="Request Approval">
                        <img src="' . asset('icon/request.ico') . '" alt="Request" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Request</span>
                </div>';
            }

            // QC (Foreman) can approve/reject when status is 'Menunggu Approval Foreman'
            if ($statusApproval === 'Menunggu Approval Foreman') {
                $html .= '<div class="flex flex-col items-center">
                    <button type="button"
                        data-url="' . route('qc.nqr.approve', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '"
                        class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                        title="Approve">
                        <img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Approve</span>
                </div>
                <div class="flex flex-col items-center">
                    <button type="button"
                        data-url="' . route('qc.nqr.reject', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '"
                        class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                        title="Reject">
                        <img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Reject</span>
                </div>';
            }

            // QC: show Edit in the same statuses as the Blade view (and not when rejected)
            if (strpos($statusApproval, 'Ditolak') !== 0 && in_array($statusApproval, [
                'Menunggu Request dikirimkan',
                'Menunggu Approval Foreman',
                'Menunggu Approval Sect Head',
                'Menunggu Approval Dept Head',
                'Menunggu Approval PPC Head',
            ])) {
                $html .= '<div class="flex flex-col items-center">
                    <a href="' . route('qc.nqr.edit', $nqr->id) . '" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-yellow-50 transition" title="Edit NQR">
                        <img src="' . asset('icon/edit.ico') . '" alt="Edit" class="w-4 h-4" />
                    </a>
                    <span class="text-xs text-gray-500 mt-1">Edit</span>
                </div>';
            }

            // QC: show Delete in the same statuses as the Blade view (and not when rejected)
            if (strpos($statusApproval, 'Ditolak') !== 0 && in_array($statusApproval, [
                'Menunggu Request dikirimkan',
                'Menunggu Approval Foreman',
                'Menunggu Approval Sect Head',
            ])) {
                $html .= '<div class="flex flex-col items-center">
                    <button type="button"
                        data-url="' . route('qc.nqr.destroy', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '"
                        class="open-delete-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                        title="Hapus">
                        <img src="' . asset('icon/trash.ico') . '" alt="Hapus" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Hapus</span>
                </div>';
            }

            // PDF button: match Blade view which shows PDF when status_approval is not 'Menunggu Request dikirimkan'
            if ($statusApproval !== 'Menunggu Request dikirimkan') {
                $html .= '<div class="flex flex-col items-center">
                    <a href="' . route('qc.nqr.previewFpdf', $nqr->id) . '" target="_blank"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                        title="Preview PDF (FPDF)">
                        <img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" />
                    </a>
                    <span class="text-xs text-gray-500 mt-1">PDF</span>
                </div>';
            }
        } elseif ($role === 'secthead') {
            if ($statusApproval === 'Menunggu Approval Sect Head') {
                $html .= '<div class="flex flex-col items-center">
                    <button type="button"
                        data-url="' . route('secthead.nqr.approve', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '"
                        class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                        title="Approve">
                        <img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Approve</span>
                </div>
                <div class="flex flex-col items-center">
                    <button type="button"
                        data-url="' . route('secthead.nqr.reject', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '"
                        class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                        title="Reject">
                        <img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Reject</span>
                </div>';
            }
            if (in_array($statusApproval, [
                'Menunggu Approval Sect Head',
                'Menunggu Approval Dept Head',
                'Menunggu Approval PPC Head',
                'Ditolak Sect Head',
                'Ditolak Dept Head',
                'Ditolak PPC Head',
                'Selesai',
            ])) {
                $html .= '<div class="flex flex-col items-center">
                    <a href="' . route('secthead.nqr.previewFpdf', $nqr->id) . '" target="_blank"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                        title="Preview PDF">
                        <img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" />
                    </a>
                    <span class="text-xs mt-1">PDF</span>
                </div>';
            }
        } elseif ($role === 'depthead') {
            if ($statusApproval === 'Menunggu Approval Dept Head') {
                $html .= '<div class="flex flex-col items-center">
                    <button type="button"
                        data-url="' . route('depthead.nqr.approve', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '"
                        class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                        title="Approve">
                        <img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Approve</span>
                </div>
                <div class="flex flex-col items-center">
                    <button type="button"
                        data-url="' . route('depthead.nqr.reject', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '"
                        class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                        title="Reject">
                        <img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Reject</span>
                </div>';
            }
            if (in_array($statusApproval, [
                'Menunggu Approval Sect Head',
                'Menunggu Approval Dept Head',
                'Menunggu Approval PPC Head',
                'Ditolak Sect Head',
                'Ditolak Dept Head',
                'Ditolak PPC Head',
                'Selesai',
            ])) {
                $html .= '<div class="flex flex-col items-center">
                    <a href="' . route('depthead.nqr.previewFpdf', $nqr->id) . '" target="_blank"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                        title="Preview PDF">
                        <img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" />
                    </a>
                    <span class="text-xs mt-1">PDF</span>
                </div>';
            }
        } elseif ($role === 'ppchead') {
            if ($statusApproval === 'Menunggu Approval PPC Head') {
                $ppcComplete = $nqr->disposition_claim && (
                    $nqr->disposition_claim === 'Pay Compensation' ||
                    ($nqr->disposition_claim === 'Send the Replacement' && $nqr->send_replacement_method)
                );

                if (!$ppcComplete) {
                    $html .= '<div class="flex flex-col items-center gap-1">
                        <a href="' . route('ppchead.nqr.edit', $nqr->id) . '" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Isi PPC">
                            <img src="' . asset('icon/input.ico') . '" alt="Isi PPC" class="w-4 h-4" />
                        </a>
                        <span class="text-xs text-gray-500 mt-1">Isi PPC</span>
                    </div>';
                } else {
                    $html .= '<div class="flex flex-col items-center gap-1">
                        <a href="' . route('ppchead.nqr.edit', $nqr->id) . '" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-yellow-50 transition" title="Edit PPC">
                            <img src="' . asset('icon/edit.ico') . '" alt="Edit PPC" class="w-4 h-4" />
                        </a>
                        <span class="text-xs text-gray-500 mt-1">Edit PPC</span>
                    </div>';
                }

                $html .= '<div class="flex flex-col items-center">
                    <a href="' . route('ppchead.nqr.previewFpdf', $nqr->id) . '" target="_blank"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                        title="Preview PDF">
                        <img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" />
                    </a>
                    <span class="text-xs mt-1">PDF</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <button type="button"
                        class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                        title="Setuju"
                        data-url="' . route('ppchead.nqr.approve', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '"
                        data-ppc-complete="' . ($ppcComplete ? 'true' : 'false') . '"
                        data-ppc-url="' . route('ppchead.nqr.edit', $nqr->id) . '">
                        <img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Approve</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <button type="button"
                        class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition"
                        title="Tolak"
                        data-url="' . route('ppchead.nqr.reject', $nqr->id) . '"
                        data-noreg="' . $nqr->no_reg_nqr . '">
                        <img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" />
                    </button>
                    <span class="text-xs text-gray-500 mt-1">Reject</span>
                </div>';
            } elseif (in_array($statusApproval, ['Selesai', 'Ditolak Foreman', 'Ditolak Sect Head', 'Ditolak Dept Head', 'Ditolak PPC Head'])) {
                $html .= '<div class="flex flex-col items-center">
                    <a href="' . route('ppchead.nqr.previewFpdf', $nqr->id) . '" target="_blank"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition"
                        title="Preview PDF">
                        <img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" />
                    </a>
                    <span class="text-xs mt-1">PDF</span>
                </div>';
            }
        }

        return $html;
    }

    public function requestApproval(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        if (Auth::user()->role !== 'qc') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Hanya QC yang dapat melakukan request approval.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya QC yang dapat melakukan request approval.');
        }

        if ($nqr->status_approval !== 'Menunggu Request dikirimkan') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR sudah di-request sebelumnya.'], 400);
            }
            return redirect()->back()->with('error', 'NQR sudah di-request sebelumnya.');
        }

        $nqr->update([
            'status_approval' => 'Menunggu Approval Foreman',
            'requested_by' => Auth::id(),
            'requested_at' => now(),
        ]);

        try {
            $approvers = User::all()->filter(function($u){
                return $u->hasRole('sect') || $u->hasRole('dept') || $u->hasRole('ppc');
            });
            if ($approvers->count()) {
                Notification::send($approvers, new NqrApprovalRequested($nqr));
            }
        } catch (\Throwable $e) {

        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Request approval berhasil dikirim!',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, 'qc'),
            ]);
        }

        return redirect()->route('qc.nqr.index')->with('success', 'Request approval berhasil dikirim!');
    }

    public function approveByQc(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        if (Auth::user()->role !== 'qc') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Hanya Foreman yang dapat melakukan approval ini.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya Foreman yang dapat melakukan approval ini.');
        }

        if (strpos($nqr->status_approval, 'Ditolak') === 0) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR sudah ditolak, tidak dapat di-approve lagi.'], 400);
            }
            return redirect()->back()->with('error', 'NQR sudah ditolak, tidak dapat di-approve lagi.');
        }

        if ($nqr->status_approval !== 'Menunggu Approval Foreman') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR belum di-request atau sudah diproses.'], 400);
            }
            return redirect()->back()->with('error', 'NQR belum di-request atau sudah diproses.');
        }

        $nqr->update([
            'status_approval' => 'Menunggu Approval Sect Head',
            'approved_by_qc' => Auth::id(),
            'approved_at_qc' => now(),
        ]);

        try {
            $actorName = Auth::user()->name ?? Auth::id();
            $notification = new NqrStatusChanged($nqr, 'Foreman', 'approved', null, $actorName);
            $recipients = User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
            Notification::send($recipients, $notification);
        } catch (\Throwable $e) {
        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'NQR berhasil di-approve oleh Foreman! Menunggu approval Sect Head.',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, 'qc'),
            ]);
        }

        return redirect()->route('qc.nqr.index')->with('success', 'NQR berhasil di-approve oleh Foreman! Menunggu approval Sect Head.');
    }

    /**
     * Sect Head Approve
     */
    public function approveBySectHead(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        if (Auth::user()->role !== 'secthead') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Hanya Sect Head yang dapat melakukan approval ini.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya Sect Head yang dapat melakukan approval ini.');
        }

        if (strpos($nqr->status_approval, 'Ditolak') === 0) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR sudah ditolak, tidak dapat di-approve lagi.'], 400);
            }
            return redirect()->back()->with('error', 'NQR sudah ditolak, tidak dapat di-approve lagi.');
        }

        if ($nqr->status_approval !== 'Menunggu Approval Sect Head') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR belum di-approve oleh Foreman.'], 400);
            }
            return redirect()->back()->with('error', 'NQR belum di-approve oleh Foreman.');
        }

        $nqr->update([
            'status_approval' => 'Menunggu Approval Dept Head',
            'approved_by_sect_head' => Auth::id(),
            'approved_at_sect_head' => now(),
        ]);

        try {
            $actorName = Auth::user()->name ?? Auth::id();
            $notification = new NqrStatusChanged($nqr, 'Sect Head', 'approved', null, $actorName);
            $recipients = User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
            Notification::send($recipients, $notification);
        } catch (\Throwable $e) {
        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'NQR berhasil di-approve oleh Sect Head! Menunggu approval Dept Head.',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, 'secthead'),
            ]);
        }

        return redirect()->back()->with('success', 'NQR berhasil di-approve oleh Sect Head! Menunggu approval Dept Head.');
    }

    public function approveByDeptHead(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        if (Auth::user()->role !== 'depthead') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Hanya Dept Head yang dapat melakukan approval ini.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya Dept Head yang dapat melakukan approval ini.');
        }

        if (strpos($nqr->status_approval, 'Ditolak') === 0) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR sudah ditolak, tidak dapat di-approve lagi.'], 400);
            }
            return redirect()->back()->with('error', 'NQR sudah ditolak, tidak dapat di-approve lagi.');
        }

        if ($nqr->status_approval !== 'Menunggu Approval Dept Head') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR belum di-approve oleh Sect Head.'], 400);
            }
            return redirect()->back()->with('error', 'NQR belum di-approve oleh Sect Head.');
        }

        $nqr->update([
            'status_approval' => 'Menunggu Approval PPC Head',
            'approved_by_dept_head' => Auth::id(),
            'approved_at_dept_head' => now(),
        ]);

        try {
            $actorName = Auth::user()->name ?? Auth::id();
            $notification = new NqrStatusChanged($nqr, 'Dept Head', 'approved', null, $actorName);
            $recipients = User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
            Notification::send($recipients, $notification);
        } catch (\Throwable $e) {
        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'NQR berhasil di-approve oleh Dept Head! Menunggu PPC melengkapi data dan approve.',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, 'depthead'),
            ]);
        }

        return redirect()->back()->with('success', 'NQR berhasil di-approve oleh Dept Head! Menunggu PPC melengkapi data dan approve.');
    }

    public function approveByPpc(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        if (Auth::user()->role !== 'ppchead') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Hanya PPC yang dapat melakukan approval ini.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya PPC yang dapat melakukan approval ini.');
        }

        if (strpos($nqr->status_approval, 'Ditolak') === 0) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR sudah ditolak, tidak dapat di-approve lagi.'], 400);
            }
            return redirect()->back()->with('error', 'NQR sudah ditolak, tidak dapat di-approve lagi.');
        }

        if ($nqr->status_approval !== 'Menunggu Approval PPC Head') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR belum di-approve oleh Dept Head.'], 400);
            }
            return redirect()->back()->with('error', 'NQR belum di-approve oleh Dept Head.');
        }

        if (empty($nqr->disposition_claim)) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Form PPC harus dilengkapi terlebih dahulu sebelum approve!'], 400);
            }
            return redirect()->back()->with('error', 'Form PPC harus dilengkapi terlebih dahulu sebelum approve!');
        }

        if ($nqr->disposition_claim === 'Send the Replacement' && empty($nqr->send_replacement_method)) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Metode pengiriman replacement harus diisi!'], 400);
            }
            return redirect()->back()->with('error', 'Metode pengiriman replacement harus diisi!');
        }

        $nqr->update([
            'status_approval' => 'Selesai',
            'approved_by_ppc' => Auth::id(),
            'approved_at_ppc' => now(),
        ]);

        try {
            $actorName = Auth::user()->name ?? Auth::id();
            $notification = new NqrStatusChanged($nqr, 'PPC Head', 'approved', null, $actorName);
            $recipients = User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
            Notification::send($recipients, $notification);
        } catch (\Throwable $e) {
        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'NQR berhasil di-approve oleh PPC! Proses approval selesai.',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, 'ppchead'),
            ]);
        }

        return redirect()->route('ppchead.nqr.index')->with('success', 'NQR berhasil di-approve oleh PPC! Proses approval selesai.');
    }

    /**
     * Reject NQR (bisa dari role mana saja yang memiliki akses)
     */
    public function reject(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);
        $user = Auth::user();

        // Validasi role yang boleh reject
        $allowedRoles = ['qc', 'secthead', 'depthead', 'ppchead'];
        if (!in_array($user->role, $allowedRoles)) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk reject NQR ini.'], 403);
            }
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk reject NQR ini.');
        }

        $canReject = false;
        $rejectionStatus = null;
        $roleForButtons = $user->role;

        if ($user->role === 'qc' && in_array($nqr->status_approval, ['Menunggu Approval Foreman', 'Menunggu Approval Sect Head'])) {
            $canReject = true;
            $rejectionStatus = 'Ditolak Foreman';
        } elseif ($user->role === 'secthead' && $nqr->status_approval === 'Menunggu Approval Sect Head') {
            $canReject = true;
            $rejectionStatus = 'Ditolak Sect Head';
        } elseif ($user->role === 'depthead' && $nqr->status_approval === 'Menunggu Approval Dept Head') {
            $canReject = true;
            $rejectionStatus = 'Ditolak Dept Head';
        } elseif ($user->role === 'ppchead' && $nqr->status_approval === 'Menunggu Approval PPC Head') {
            $canReject = true;
            $rejectionStatus = 'Ditolak PPC Head';
        }

        if (!$canReject) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Anda tidak dapat reject NQR pada tahap ini.'], 400);
            }
            return redirect()->back()->with('error', 'Anda tidak dapat reject NQR pada tahap ini.');
        }

        $nqr->update([
            'status_approval' => $rejectionStatus,
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
        ]);

        try {
            $actorName = Auth::user()->name ?? Auth::id();
            $actorRoleLabel = ($user->role === 'qc') ? 'Foreman' : ucfirst($user->role ?? '');
            $notification = new NqrStatusChanged($nqr, $actorRoleLabel, 'rejected', $request->input('reason') ?? null, $actorName);
            $recipients = User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
            Notification::send($recipients, $notification);
        } catch (\Throwable $e) {
        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'NQR berhasil di-reject.',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, $roleForButtons),
            ]);
        }

        return redirect()->back()->with('success', 'NQR berhasil di-reject.');
    }

    /**
     * Halaman approval untuk Sect Head
     */
    public function sectHeadIndex(Request $request)
    {
        $query = Nqr::whereIn('status_approval', [
            'Menunggu Approval Sect Head',
            'Menunggu Approval Dept Head',
            'Menunggu Approval PPC Head',
            'Ditolak Sect Head',
            'Ditolak Dept Head',
            'Ditolak PPC Head',
            'Selesai'
        ]);

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('no_reg_nqr', 'like', '%' . $request->q . '%')
                  ->orWhere('nama_supplier', 'like', '%' . $request->q . '%')
                  ->orWhere('nama_part', 'like', '%' . $request->q . '%')
                  ->orWhere('nomor_part', 'like', '%' . $request->q . '%');
            });
        }

        $dateParam = null;
        if ($request->filled('date')) {
            $dateParam = $request->date;
        } elseif ($request->filled('date_display')) {
            $dateParam = $request->date_display;
        }

        if (!empty($dateParam)) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dateParam))->format('Y-m-d');
                $query->whereDate('tgl_terbit_nqr', $date);
            } catch (\Exception $e) {
                try {
                    $date = \Carbon\Carbon::parse(trim($dateParam))->format('Y-m-d');
                    $query->whereDate('tgl_terbit_nqr', $date);
                } catch (\Exception $e) {
                }
            }
        }

        if ($request->filled('year')) {
            $query->whereYear('tgl_terbit_nqr', $request->year);
        }

        if ($request->filled('status_nqr')) {
            $query->where('status_nqr', $request->status_nqr);
        }

        if ($request->filled('approval_status')) {
            $approval = $request->approval_status;
            $mapping = [
                'menunggu_request' => 'Menunggu Request dikirimkan',
                'menunggu_foreman' => 'Menunggu Approval Foreman',
                'menunggu_sect' => 'Menunggu Approval Sect Head',
                'menunggu_dept' => 'Menunggu Approval Dept Head',
                'menunggu_ppc' => 'Menunggu Approval PPC Head',
                'ditolak_foreman' => 'Ditolak Foreman',
                'ditolak_sect' => 'Ditolak Sect Head',
                'ditolak_dept' => 'Ditolak Dept Head',
                'ditolak_ppc' => 'Ditolak PPC Head',
                'selesai' => 'Selesai',
            ];

            if (isset($mapping[$approval])) {
                $approval = $mapping[$approval];
            }

            $query->where('status_approval', $approval);
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('secthead.nqr.index', compact('nqrs'));
    }

    public function deptHeadIndex(Request $request)
    {
        $query = Nqr::whereIn('status_approval', [
            'Menunggu Approval Sect Head',
            'Menunggu Approval Dept Head',
            'Menunggu Approval PPC Head',
            'Ditolak Sect Head',
            'Ditolak Dept Head',
            'Ditolak PPC Head',
            'Selesai'
        ]);

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('no_reg_nqr', 'like', '%' . $request->q . '%')
                  ->orWhere('nama_supplier', 'like', '%' . $request->q . '%')
                  ->orWhere('nama_part', 'like', '%' . $request->q . '%')
                  ->orWhere('nomor_part', 'like', '%' . $request->q . '%');
            });
        }
        $dateParam = null;
        if ($request->filled('date')) {
            $dateParam = $request->date;
        } elseif ($request->filled('date_display')) {
            $dateParam = $request->date_display;
        }

        if (!empty($dateParam)) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dateParam))->format('Y-m-d');
                $query->whereDate('tgl_terbit_nqr', $date);
            } catch (\Exception $e) {
                try {
                    $date = \Carbon\Carbon::parse(trim($dateParam))->format('Y-m-d');
                    $query->whereDate('tgl_terbit_nqr', $date);
                } catch (\Exception $e) {

                }
            }
        }

        if ($request->filled('year')) {
            $query->whereYear('tgl_terbit_nqr', $request->year);
        }

        if ($request->filled('status_nqr')) {
            $query->where('status_nqr', $request->status_nqr);
        }

        if ($request->filled('approval_status')) {
            $approval = $request->approval_status;
            $mapping = [
                'menunggu_request' => 'Menunggu Request dikirimkan',
                'menunggu_foreman' => 'Menunggu Approval Foreman',
                'menunggu_sect' => 'Menunggu Approval Sect Head',
                'menunggu_dept' => 'Menunggu Approval Dept Head',
                'menunggu_ppc' => 'Menunggu Approval PPC Head',
                'ditolak_foreman' => 'Ditolak Foreman',
                'ditolak_sect' => 'Ditolak Sect Head',
                'ditolak_dept' => 'Ditolak Dept Head',
                'ditolak_ppc' => 'Ditolak PPC Head',
                'selesai' => 'Selesai',
            ];

            if (isset($mapping[$approval])) {
                $approval = $mapping[$approval];
            }

            $query->where('status_approval', $approval);
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('depthead.nqr.index', compact('nqrs'));
    }
}
