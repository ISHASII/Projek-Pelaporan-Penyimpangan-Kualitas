<?php

namespace App\Http\Controllers;

use App\Models\Nqr;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        // QC / Foreman actions
        if ($role === 'qc' || $role === 'foreman') {
            if ($statusApproval === 'Menunggu Request dikirimkan') {
                $requestRoute = ($role === 'foreman') ? route('foreman.nqr.requestApproval', $nqr->id) : route('qc.nqr.requestApproval', $nqr->id);
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . $requestRoute . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-request-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-blue-50 transition" title="Request Approval"><img src="' . asset('icon/send.ico') . '" alt="Request" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Request</span></div>';
            }

            if ($statusApproval === 'Menunggu Approval Foreman') {
                $approveRoute = ($role === 'foreman') ? route('foreman.nqr.approve', $nqr->id) : route('qc.nqr.approve', $nqr->id);
                $rejectRoute = ($role === 'foreman') ? route('foreman.nqr.reject', $nqr->id) : route('qc.nqr.reject', $nqr->id);
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . $approveRoute . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Approve"><img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Approve</span></div>'
                    . '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . $rejectRoute . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition" title="Reject"><img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Reject</span></div>';
            }

            if (strpos($statusApproval, 'Ditolak') !== 0 && in_array($statusApproval, ['Menunggu Request dikirimkan','Menunggu Approval Foreman','Menunggu Approval Sect Head','Menunggu Approval Dept Head','Menunggu Approval PPC Head'])) {
                $editRoute = ($role === 'foreman') ? route('foreman.nqr.edit', $nqr->id) : route('qc.nqr.edit', $nqr->id);
                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . $editRoute . '" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-yellow-50 transition" title="Edit NQR"><img src="' . asset('icon/edit.ico') . '" alt="Edit" class="w-4 h-4" /></a><span class="text-xs text-gray-500 mt-1">Edit</span></div>';
            }

            if (strpos($statusApproval, 'Ditolak') !== 0 && in_array($statusApproval, ['Menunggu Request dikirimkan','Menunggu Approval Foreman','Menunggu Approval Sect Head'])) {
                $destroyRoute = ($role === 'foreman') ? route('foreman.nqr.destroy', $nqr->id) : route('qc.nqr.destroy', $nqr->id);
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . $destroyRoute . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-delete-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition" title="Hapus"><img src="' . asset('icon/trash.ico') . '" alt="Hapus" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Hapus</span></div>';
            }

            if ($statusApproval !== 'Menunggu Request dikirimkan') {
                $pdfRoute = ($role === 'foreman') ? route('foreman.nqr.previewFpdf', $nqr->id) : route('qc.nqr.previewFpdf', $nqr->id);
                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . $pdfRoute . '" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Preview PDF (FPDF)"><img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" /></a><span class="text-xs text-gray-500 mt-1">PDF</span></div>';
            }

            return $html;
        }

        // Sect Head actions
        if ($role === 'secthead') {
            if ($statusApproval === 'Menunggu Approval Sect Head') {
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . route('secthead.nqr.approve', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Approve"><img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Approve</span></div>'
                    . '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . route('secthead.nqr.reject', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition" title="Reject"><img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Reject</span></div>';
            }

            if (in_array($statusApproval, ['Menunggu Approval Sect Head','Menunggu Approval Dept Head','Menunggu Approval PPC Head','Ditolak Sect Head','Ditolak Dept Head','Ditolak PPC Head','Selesai'])) {
                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . route('secthead.nqr.previewFpdf', $nqr->id) . '" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Preview PDF"><img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" /></a><span class="text-xs mt-1">PDF</span></div>';
            }

            return $html;
        }

        // Dept Head actions
        if ($role === 'depthead') {
            if ($statusApproval === 'Menunggu Approval Dept Head') {
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . route('depthead.nqr.approve', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Approve"><img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Approve</span></div>'
                    . '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . route('depthead.nqr.reject', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition" title="Reject"><img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Reject</span></div>';
            }

            if (in_array($statusApproval, ['Menunggu Approval Sect Head','Menunggu Approval Dept Head','Menunggu Approval PPC Head','Ditolak Sect Head','Ditolak Dept Head','Ditolak PPC Head','Selesai'])) {
                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . route('depthead.nqr.previewFpdf', $nqr->id) . '" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Preview PDF"><img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" /></a><span class="text-xs mt-1">PDF</span></div>';
            }

            return $html;
        }

        // PPC Head actions
        if ($role === 'ppchead') {
            if ($statusApproval === 'Menunggu Approval PPC Head') {
                $ppcComplete = $nqr->disposition_claim && ($nqr->disposition_claim === 'Pay Compensation' || ($nqr->disposition_claim === 'Send the Replacement' && $nqr->send_replacement_method));
                if (!$ppcComplete) {
                    $html .= '<div class="flex flex-col items-center gap-1">'
                        . '<a href="' . route('ppchead.nqr.edit', $nqr->id) . '" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Isi PPC"><img src="' . asset('icon/input.ico') . '" alt="Isi PPC" class="w-4 h-4" /></a><span class="text-xs text-gray-500 mt-1">Isi PPC</span></div>';
                } else {
                    $html .= '<div class="flex flex-col items-center gap-1">'
                        . '<a href="' . route('ppchead.nqr.edit', $nqr->id) . '" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-yellow-50 transition" title="Edit PPC"><img src="' . asset('icon/edit.ico') . '" alt="Edit PPC" class="w-4 h-4" /></a><span class="text-xs text-gray-500 mt-1">Edit PPC</span></div>';
                }

                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . route('ppchead.nqr.previewFpdf', $nqr->id) . '" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Preview PDF"><img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" /></a><span class="text-xs mt-1">PDF</span></div>';

                $html .= '<div class="flex flex-col items-center gap-1">'
                    . '<button type="button" class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Setuju" data-url="' . route('ppchead.nqr.approve', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '" data-ppc-complete="' . ($ppcComplete ? 'true' : 'false') . '" data-ppc-url="' . route('ppchead.nqr.edit', $nqr->id) . '"><img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Approve</span></div>';

                $html .= '<div class="flex flex-col items-center gap-1">'
                    . '<button type="button" class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition" title="Tolak" data-url="' . route('ppchead.nqr.reject', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '"><img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Reject</span></div>';
            }

            if (in_array($statusApproval, ['Selesai','Ditolak Foreman','Ditolak Sect Head','Ditolak Dept Head','Ditolak PPC Head'])) {
                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . route('ppchead.nqr.previewFpdf', $nqr->id) . '" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Preview PDF"><img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" /></a><span class="text-xs mt-1">PDF</span></div>';
            }

            return $html;
        }

        // VDD actions
        if ($role === 'vdd') {
            if ($statusApproval === 'Menunggu Approval VDD') {
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . route('vdd.nqr.approve', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Approve"><img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Approve</span></div>';
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . route('vdd.nqr.reject', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition" title="Reject"><img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Reject</span></div>';
            }

            if (in_array($statusApproval, ['Menunggu Approval VDD','Menunggu Approval Procurement','Ditolak VDD','Ditolak Procurement','Selesai'])) {
                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . route('vdd.nqr.previewFpdf', $nqr->id) . '" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Preview PDF"><img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" /></a><span class="text-xs mt-1">PDF</span></div>';
            }

            return $html;
        }

        // Procurement actions
        if ($role === 'procurement') {
            if ($statusApproval === 'Menunggu Approval Procurement') {
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . route('procurement.nqr.approve', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-approve-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Approve"><img src="' . asset('icon/approve.ico') . '" alt="Approve" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Approve</span></div>';
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" data-url="' . route('procurement.nqr.reject', $nqr->id) . '" data-noreg="' . $nqr->no_reg_nqr . '" class="open-reject-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition" title="Reject"><img src="' . asset('icon/cancel.ico') . '" alt="Reject" class="w-4 h-4" /></button><span class="text-xs text-gray-500 mt-1">Reject</span></div>';
            }

            if (in_array($statusApproval, ['Menunggu Approval Procurement','Selesai','Ditolak Procurement'])) {
                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . route('procurement.nqr.previewFpdf', $nqr->id) . '" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-50 transition" title="Preview PDF"><img src="' . asset('icon/pdf.ico') . '" alt="Preview PDF" class="w-4 h-4" /></a><span class="text-xs mt-1">PDF</span></div>';
            }

            return $html;
        }

        return $html;
    }

    // Public wrapper so other controllers / views can request action buttons HTML
    public function actionButtonsHtml($nqr, $role)
    {
        return $this->getActionButtonsHtml($nqr, $role);
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

        // Get selected recipients from request (if any)
        $selectedNpks = [];
        if ($request->has('recipients') && is_array($request->input('recipients'))) {
            $selectedNpks = array_filter($request->input('recipients'));
        }

        // Send notification to Foreman (dept=QA, golongan=3, acting in [1,2])
        try {
            $emailsToNotify = collect();

            if (!empty($selectedNpks)) {
                // Fetch only selected Foreman approvers from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->whereIn('npk', $selectedNpks)
                    ->where('dept', 'QA')
                    ->where('golongan', 3)
                    ->whereIn('acting', [1, 2])
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            } else {
                // Fetch all Foreman approvers from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->where('dept', 'QA')
                    ->where('golongan', 3)
                    ->whereIn('acting', [1, 2])
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            }

            foreach ($lemburRecipients as $lr) {
                $emailsToNotify->push((object)[
                    'npk' => $lr->npk,
                    'name' => $lr->full_name,
                    'email' => $lr->user_email,
                ]);
            }

            // Send dual notification (email + web)
            foreach ($emailsToNotify as $recipient) {
                // Send email
                try {
                    Mail::send(
                        'emails.nqr_approval_requested',
                        ['nqr' => $nqr, 'recipientName' => $recipient->name],
                        function ($message) use ($recipient, $nqr) {
                            $message->to($recipient->email, $recipient->name)
                                    ->subject('Permintaan Persetujuan NQR: ' . $nqr->no_reg_nqr);
                        }
                    );
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send NQR approval email', [
                        'npk' => $recipient->npk,
                        'email' => $recipient->email,
                        'error' => $mailErr->getMessage()
                    ]);
                }

                // Send database notification to local user (if exists)
                $localUser = User::where('npk', $recipient->npk)->first();
                if ($localUser) {
                    try {
                        $localUser->notify(new NqrApprovalRequested($nqr));
                    } catch (\Throwable $notifErr) {
                        Log::warning('Failed to send NQR database notification', [
                            'npk' => $recipient->npk,
                            'user_id' => $localUser->id,
                            'error' => $notifErr->getMessage()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send NQR approval notifications', ['error' => $e->getMessage()]);
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

    public function requestApprovalByForeman(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        if (Auth::user()->role !== 'foreman') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Hanya Foreman yang dapat melakukan request approval.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya Foreman yang dapat melakukan request approval.');
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

        // Send notification to Foreman (dept=QA, golongan=3, acting in [1,2]) - same as QC request
        try {
            $emailsToNotify = collect();

            // Fetch Foreman approvers from lembur database
            $lemburRecipients = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'QA')
                ->where('golongan', 3)
                ->whereIn('acting', [1, 2])
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburRecipients as $lr) {
                $emailsToNotify->push((object)[
                    'npk' => $lr->npk,
                    'name' => $lr->full_name,
                    'email' => $lr->user_email,
                ]);
            }

            // Send dual notification (email + web)
            foreach ($emailsToNotify as $recipient) {
                // Send email
                try {
                    Mail::send(
                        'emails.nqr_approval_requested',
                        ['nqr' => $nqr, 'recipientName' => $recipient->name],
                        function ($message) use ($recipient, $nqr) {
                            $message->to($recipient->email, $recipient->name)
                                    ->subject('Permintaan Persetujuan NQR: ' . $nqr->no_reg_nqr);
                        }
                    );
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send NQR approval email', [
                        'npk' => $recipient->npk,
                        'email' => $recipient->email,
                        'error' => $mailErr->getMessage()
                    ]);
                }

                // Send database notification to local user (if exists)
                $localUser = User::where('npk', $recipient->npk)->first();
                if ($localUser) {
                    try {
                        $localUser->notify(new NqrApprovalRequested($nqr));
                    } catch (\Throwable $notifErr) {
                        Log::warning('Failed to send NQR database notification', [
                            'npk' => $recipient->npk,
                            'user_id' => $localUser->id,
                            'error' => $notifErr->getMessage()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send NQR approval notifications', ['error' => $e->getMessage()]);
        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Request approval berhasil dikirim oleh Foreman!',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, 'foreman'),
            ]);
        }

        return redirect()->route('foreman.nqr.index')->with('success', 'Request approval berhasil dikirim oleh Foreman!');
    }

    public function approveByQc(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        $userRole = Auth::user()->role;
        if (! in_array($userRole, ['qc', 'foreman'])) {
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

        // Send notification to Sect Head (dept=QA, golongan=4, acting=2)
        try {
            $emailsToNotify = collect();

            // Check if specific recipients were selected
            $selectedRecipients = $request->input('approve_recipients', []);
            if (!empty($selectedRecipients)) {
                // Fetch only selected recipients from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->whereIn('npk', $selectedRecipients)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            } else {
                // Fetch all Sect Head approvers from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->where('dept', 'QA')
                    ->where('golongan', 4)
                    ->where('acting', 2)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            }

            foreach ($lemburRecipients as $lr) {
                $emailsToNotify->push((object)[
                    'npk' => $lr->npk,
                    'name' => $lr->full_name,
                    'email' => $lr->user_email,
                ]);
            }

            // Send dual notification (email + web)
            $actorName = Auth::user()->name ?? Auth::id();
            foreach ($emailsToNotify as $recipient) {
                // Send email
                try {
                    Mail::send(
                        'emails.nqr_approval_requested',
                        ['nqr' => $nqr, 'recipientName' => $recipient->name],
                        function ($message) use ($recipient, $nqr) {
                            $message->to($recipient->email, $recipient->name)
                                    ->subject('Permintaan Persetujuan NQR: ' . $nqr->no_reg_nqr);
                        }
                    );
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send NQR approval email to Sect Head', [
                        'npk' => $recipient->npk,
                        'email' => $recipient->email,
                        'error' => $mailErr->getMessage()
                    ]);
                }

                // Send database notification to local user (if exists)
                $localUser = User::where('npk', $recipient->npk)->first();
                if ($localUser) {
                    try {
                        $localUser->notify(new NqrStatusChanged($nqr, 'Foreman', 'approved', null, $actorName));
                    } catch (\Throwable $notifErr) {
                        Log::warning('Failed to send NQR database notification to Sect Head', [
                            'npk' => $recipient->npk,
                            'user_id' => $localUser->id,
                            'error' => $notifErr->getMessage()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send NQR approval notifications to Sect Head', ['error' => $e->getMessage()]);
        }

        $roleForButtons = ($userRole === 'foreman') ? 'foreman' : 'qc';

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'NQR berhasil di-approve oleh Foreman! Menunggu approval Sect Head.',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, $roleForButtons),
            ]);
        }

        if ($userRole === 'foreman') {
            return redirect()->route('foreman.nqr.index')->with('success', 'NQR berhasil di-approve oleh Foreman! Menunggu approval Sect Head.');
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

        // Send notification to Dept Head (dept=QA, golongan=4, acting=1)
        try {
            $emailsToNotify = collect();

            // Check if specific recipients were selected
            $selectedRecipients = $request->input('approve_recipients', []);
            if (!empty($selectedRecipients)) {
                // Fetch only selected recipients from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->whereIn('npk', $selectedRecipients)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            } else {
                // Fetch all Dept Head approvers from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->where('dept', 'QA')
                    ->where('golongan', 4)
                    ->where('acting', 1)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            }

            foreach ($lemburRecipients as $lr) {
                $emailsToNotify->push((object)[
                    'npk' => $lr->npk,
                    'name' => $lr->full_name,
                    'email' => $lr->user_email,
                ]);
            }

            // Send dual notification (email + web)
            $actorName = Auth::user()->name ?? Auth::id();
            foreach ($emailsToNotify as $recipient) {
                // Send email
                try {
                    Mail::send(
                        'emails.nqr_approval_requested',
                        ['nqr' => $nqr, 'recipientName' => $recipient->name],
                        function ($message) use ($recipient, $nqr) {
                            $message->to($recipient->email, $recipient->name)
                                    ->subject('Permintaan Persetujuan NQR: ' . $nqr->no_reg_nqr);
                        }
                    );
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send NQR approval email to Dept Head', [
                        'npk' => $recipient->npk,
                        'email' => $recipient->email,
                        'error' => $mailErr->getMessage()
                    ]);
                }

                // Send database notification to local user (if exists)
                $localUser = User::where('npk', $recipient->npk)->first();
                if ($localUser) {
                    try {
                        $localUser->notify(new NqrStatusChanged($nqr, 'Sect Head', 'approved', null, $actorName));
                    } catch (\Throwable $notifErr) {
                        Log::warning('Failed to send NQR database notification to Dept Head', [
                            'npk' => $recipient->npk,
                            'user_id' => $localUser->id,
                            'error' => $notifErr->getMessage()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send NQR approval notifications to Dept Head', ['error' => $e->getMessage()]);
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

        // Send notification to PPC Head (dept=PPC, golongan=4, acting=1)
        try {
            $emailsToNotify = collect();

            // Check if specific recipients were selected
            $selectedRecipients = $request->input('approve_recipients', []);
            if (!empty($selectedRecipients)) {
                // Fetch only selected recipients from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->whereIn('npk', $selectedRecipients)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            } else {
                // Fetch all PPC Head approvers from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->where('dept', 'PPC')
                    ->where('golongan', 4)
                    ->where('acting', 1)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            }

            foreach ($lemburRecipients as $lr) {
                $emailsToNotify->push((object)[
                    'npk' => $lr->npk,
                    'name' => $lr->full_name,
                    'email' => $lr->user_email,
                ]);
            }

            // Send dual notification (email + web)
            $actorName = Auth::user()->name ?? Auth::id();
            foreach ($emailsToNotify as $recipient) {
                // Send email
                try {
                    Mail::send(
                        'emails.nqr_approval_requested',
                        ['nqr' => $nqr, 'recipientName' => $recipient->name],
                        function ($message) use ($recipient, $nqr) {
                            $message->to($recipient->email, $recipient->name)
                                    ->subject('Permintaan Persetujuan NQR: ' . $nqr->no_reg_nqr);
                        }
                    );
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send NQR approval email to PPC Head', [
                        'npk' => $recipient->npk,
                        'email' => $recipient->email,
                        'error' => $mailErr->getMessage()
                    ]);
                }

                // Send database notification to local user (if exists)
                $localUser = User::where('npk', $recipient->npk)->first();
                if ($localUser) {
                    try {
                        $localUser->notify(new NqrStatusChanged($nqr, 'Dept Head', 'approved', null, $actorName));
                    } catch (\Throwable $notifErr) {
                        Log::warning('Failed to send NQR database notification to PPC Head', [
                            'npk' => $recipient->npk,
                            'user_id' => $localUser->id,
                            'error' => $notifErr->getMessage()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send NQR approval notifications to PPC Head', ['error' => $e->getMessage()]);
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
            // now forward to VDD for next approval step
            'status_approval' => 'Menunggu Approval VDD',
            'approved_by_ppc' => Auth::id(),
            'approved_at_ppc' => now(),
        ]);

        // Send notification to VDD (dept=VDD, golongan=4, acting=1)
        try {
            $emailsToNotify = collect();

            // Check if specific recipients were selected
            $selectedRecipients = $request->input('approve_recipients', []);
            if (!empty($selectedRecipients)) {
                // Fetch only selected recipients from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->whereIn('npk', $selectedRecipients)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            } else {
                // Fetch all VDD approvers from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->where('dept', 'VDD')
                    ->where('golongan', 4)
                    ->where('acting', 1)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            }

            foreach ($lemburRecipients as $lr) {
                $emailsToNotify->push((object)[
                    'npk' => $lr->npk,
                    'name' => $lr->full_name,
                    'email' => $lr->user_email,
                ]);
            }

            // Send dual notification (email + web)
            $actorName = Auth::user()->name ?? Auth::id();
            foreach ($emailsToNotify as $recipient) {
                // Send email
                try {
                    Mail::send(
                        'emails.nqr_approval_requested',
                        ['nqr' => $nqr, 'recipientName' => $recipient->name],
                        function ($message) use ($recipient, $nqr) {
                            $message->to($recipient->email, $recipient->name)
                                    ->subject('Permintaan Persetujuan NQR: ' . $nqr->no_reg_nqr);
                        }
                    );
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send NQR approval email to VDD', [
                        'npk' => $recipient->npk,
                        'email' => $recipient->email,
                        'error' => $mailErr->getMessage()
                    ]);
                }

                // Send database notification to local user (if exists)
                $localUser = User::where('npk', $recipient->npk)->first();
                if ($localUser) {
                    try {
                        $localUser->notify(new NqrStatusChanged($nqr, 'PPC Head', 'approved', null, $actorName));
                    } catch (\Throwable $notifErr) {
                        Log::warning('Failed to send NQR database notification to VDD', [
                            'npk' => $recipient->npk,
                            'user_id' => $localUser->id,
                            'error' => $notifErr->getMessage()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send NQR approval notifications to VDD', ['error' => $e->getMessage()]);
        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'NQR berhasil di-approve oleh PPC! Menunggu approval VDD.',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, 'ppchead'),
            ]);
        }

        return redirect()->route('ppchead.nqr.index')->with('success', 'NQR berhasil di-approve oleh PPC! Menunggu approval VDD.');
    }

    /**
     * VDD approve
     */
    public function approveByVdd(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        if (Auth::user()->role !== 'vdd') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Hanya VDD yang dapat melakukan approval ini.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya VDD yang dapat melakukan approval ini.');
        }

        if (strpos($nqr->status_approval, 'Ditolak') === 0) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR sudah ditolak, tidak dapat di-approve lagi.'], 400);
            }
            return redirect()->back()->with('error', 'NQR sudah ditolak, tidak dapat di-approve lagi.');
        }

        if ($nqr->status_approval !== 'Menunggu Approval VDD') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR belum di-approve oleh PPC.'], 400);
            }
            return redirect()->back()->with('error', 'NQR belum di-approve oleh PPC.');
        }

        // If user submitted PPC inputs, attempt to save them (without auto-closing the workflow)
        $hasPpcInput = ($request->filled('pay_compensation_value') || $request->filled('pay_compensation_currency') || $request->filled('pay_compensation_currency_symbol'));
        if ($hasPpcInput) {
            try {
                // Validate PPC fields minimally
                $rules = [
                    'pay_compensation_currency' => 'nullable|string|in:IDR,JPY,USD,MYR,VND,THB,KRW,INR,CNY,CUSTOM',
                    'pay_compensation_value' => 'nullable|numeric|min:0.01',
                ];
                if ($request->input('pay_compensation_currency') === 'CUSTOM') {
                    $rules['pay_compensation_currency_symbol'] = 'required|string|max:10';
                }
                $validated = $request->validate($rules);

                // Only change disposition if empty or already intended as pay compensation
                if (empty($nqr->disposition_claim) || strtoupper(trim((string)$nqr->disposition_claim)) === 'PAY COMPENSATION') {
                    $nqr->disposition_claim = 'Pay Compensation';
                }
                // Save the fields to the model
                $nqr->pay_compensation_value = $request->input('pay_compensation_value');
                $nqr->pay_compensation_currency = $request->input('pay_compensation_currency');
                $nqr->pay_compensation_currency_symbol = $request->input('pay_compensation_currency_symbol');
                $nqr->save();
            } catch (\Throwable $e) {
                // swallow validation exceptions so VDD approval action still proceeds if the request is AJAX
            }
        }

        // Prepare update data
        $updateData = [
            'status_approval' => 'Menunggu Approval Procurement',
            'approved_by_vdd' => Auth::id(),
            'approved_at_vdd' => now(),
        ];

        // Set disposition to Pay Compensation if empty or already intended as Pay Compensation
        if (empty($nqr->disposition_claim) || strtoupper(trim((string)$nqr->disposition_claim)) === 'PAY COMPENSATION') {
            $updateData['disposition_claim'] = 'Pay Compensation';
        }

        // Now perform the approve action (do not auto-approve Procurement here)
        $nqr->update($updateData);

        // Send notification to Procurement (dept=PROCUREMENT, golongan=4, acting=1)
        try {
            $emailsToNotify = collect();

            // Check if specific recipients were selected
            $selectedRecipients = $request->input('approve_recipients', []);
            if (!empty($selectedRecipients)) {
                // Fetch only selected recipients from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->whereIn('npk', $selectedRecipients)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            } else {
                // Fetch all Procurement approvers from lembur database
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->where('dept', 'PROCUREMENT')
                    ->where('golongan', 4)
                    ->where('acting', 1)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();
            }

            foreach ($lemburRecipients as $lr) {
                $emailsToNotify->push((object)[
                    'npk' => $lr->npk,
                    'name' => $lr->full_name,
                    'email' => $lr->user_email,
                ]);
            }

            // Send dual notification (email + web)
            $actorName = Auth::user()->name ?? Auth::id();
            foreach ($emailsToNotify as $recipient) {
                // Send email
                try {
                    Mail::send(
                        'emails.nqr_approval_requested',
                        ['nqr' => $nqr, 'recipientName' => $recipient->name],
                        function ($message) use ($recipient, $nqr) {
                            $message->to($recipient->email, $recipient->name)
                                    ->subject('Permintaan Persetujuan NQR: ' . $nqr->no_reg_nqr);
                        }
                    );
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send NQR approval email to Procurement', [
                        'npk' => $recipient->npk,
                        'email' => $recipient->email,
                        'error' => $mailErr->getMessage()
                    ]);
                }

                // Send database notification to local user (if exists)
                $localUser = User::where('npk', $recipient->npk)->first();
                if ($localUser) {
                    try {
                        $localUser->notify(new NqrStatusChanged($nqr, 'VDD', 'approved', null, $actorName));
                    } catch (\Throwable $notifErr) {
                        Log::warning('Failed to send NQR database notification to Procurement', [
                            'npk' => $recipient->npk,
                            'user_id' => $localUser->id,
                            'error' => $notifErr->getMessage()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send NQR approval notifications to Procurement', ['error' => $e->getMessage()]);
        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'NQR berhasil di-approve oleh VDD! Menunggu approval Procurement.',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, 'vdd'),
            ]);
        }

        return redirect()->route('vdd.nqr.index')->with('success', 'NQR berhasil di-approve oleh VDD! Menunggu approval Procurement.');
    }

    /**
     * Procurement approve
     */
    public function approveByProcurement(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        if (Auth::user()->role !== 'procurement') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Hanya Procurement yang dapat melakukan approval ini.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya Procurement yang dapat melakukan approval ini.');
        }

        if (strpos($nqr->status_approval, 'Ditolak') === 0) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR sudah ditolak, tidak dapat di-approve lagi.'], 400);
            }
            return redirect()->back()->with('error', 'NQR sudah ditolak, tidak dapat di-approve lagi.');
        }

        if ($nqr->status_approval !== 'Menunggu Approval Procurement') {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'NQR belum di-approve oleh VDD.'], 400);
            }
            return redirect()->back()->with('error', 'NQR belum di-approve oleh VDD.');
        }

        // If procurement approves without providing pay_compensation, still mark disposition as Pay Compensation
        // Only set disposition to 'Pay Compensation' if it's empty or already intended as Pay Compensation.
        // Do not overwrite existing dispositions like 'Send the Replacement'.
        if (empty($nqr->disposition_claim) || strtoupper(trim((string)$nqr->disposition_claim)) === 'PAY COMPENSATION') {
            $nqr->disposition_claim = 'Pay Compensation';
        }

        $nqr->status_approval = 'Selesai';
        $nqr->approved_by_procurement = Auth::id();
        $nqr->approved_at_procurement = now();
        $nqr->save();

        // Send notification to all relevant users that NQR is completed
        try {
            $actorName = Auth::user()->name ?? Auth::id();

            // Get all QA, PPC, VDD users from lembur to notify completion
            $lemburRecipients = DB::connection('lembur')
                ->table('ct_users_hash')
                ->whereIn('dept', ['QA', 'PPC', 'VDD'])
                ->where('golongan', '>=', 3)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburRecipients as $lr) {
                // Send email notification for completion
                try {
                    Mail::send(
                        'emails.nqr_approval_requested',
                        ['nqr' => $nqr, 'recipientName' => $lr->full_name],
                        function ($message) use ($lr, $nqr) {
                            $message->to($lr->user_email, $lr->full_name)
                                    ->subject('NQR Selesai: ' . $nqr->no_reg_nqr);
                        }
                    );
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send NQR completion email', [
                        'npk' => $lr->npk,
                        'email' => $lr->user_email,
                        'error' => $mailErr->getMessage()
                    ]);
                }

                // Send database notification to local user (if exists)
                $localUser = User::where('npk', $lr->npk)->first();
                if ($localUser) {
                    try {
                        $localUser->notify(new NqrStatusChanged($nqr, 'Procurement', 'approved', null, $actorName));
                    } catch (\Throwable $notifErr) {
                        Log::warning('Failed to send NQR completion database notification', [
                            'npk' => $lr->npk,
                            'user_id' => $localUser->id,
                            'error' => $notifErr->getMessage()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send NQR completion notifications', ['error' => $e->getMessage()]);
        }

        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => 'NQR berhasil di-approve oleh Procurement! Proses approval selesai.',
                'newStatus' => $nqr->status_approval,
                'newStatusText' => $nqr->status_approval,
                'actionButtonsHtml' => $this->getActionButtonsHtml($nqr, 'procurement'),
            ]);
        }

        return redirect()->route('procurement.nqr.index')->with('success', 'NQR berhasil di-approve oleh Procurement! Proses approval selesai.');
    }

    /**
     * Reject NQR (bisa dari role mana saja yang memiliki akses)
     */
    public function reject(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);
        $user = Auth::user();

        // Validasi role yang boleh reject
        $allowedRoles = ['qc', 'foreman', 'secthead', 'depthead', 'ppchead', 'vdd', 'procurement'];
        if (!in_array($user->role, $allowedRoles)) {
            if ($this->isAjaxRequest($request)) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk reject NQR ini.'], 403);
            }
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk reject NQR ini.');
        }

        $canReject = false;
        $rejectionStatus = null;
        $roleForButtons = $user->role;

        if (in_array($user->role, ['qc', 'foreman']) && in_array($nqr->status_approval, ['Menunggu Approval Foreman', 'Menunggu Approval Sect Head'])) {
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
        } elseif ($user->role === 'vdd' && $nqr->status_approval === 'Menunggu Approval VDD') {
            $canReject = true;
            $rejectionStatus = 'Ditolak VDD';
        } elseif ($user->role === 'procurement' && $nqr->status_approval === 'Menunggu Approval Procurement') {
            $canReject = true;
            $rejectionStatus = 'Ditolak Procurement';
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
            $actorRoleLabel = (in_array($user->role, ['qc', 'foreman'])) ? 'Foreman' : ucfirst($user->role ?? '');
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
            'Menunggu Approval VDD',
            'Menunggu Approval Procurement',
            'Ditolak Sect Head',
            'Ditolak Dept Head',
            'Ditolak PPC Head',
            'Ditolak VDD',
            'Ditolak Procurement',
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
                'menunggu_vdd' => 'Menunggu Approval VDD',
                'menunggu_procurement' => 'Menunggu Approval Procurement',
                'ditolak_foreman' => 'Ditolak Foreman',
                'ditolak_sect' => 'Ditolak Sect Head',
                'ditolak_dept' => 'Ditolak Dept Head',
                'ditolak_ppc' => 'Ditolak PPC Head',
                'ditolak_vdd' => 'Ditolak VDD',
                'ditolak_procurement' => 'Ditolak Procurement',
                'selesai' => 'Selesai',
            ];

            if (isset($mapping[$approval])) {
                $approval = $mapping[$approval];
            }

            $query->where('status_approval', $approval);
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Fetch Dept Head approvers from lembur database (dept=QA, golongan=4, acting=1)
        $deptApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'QA')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $localUser = User::where('npk', $ext->npk)->first();
                $deptApprovers->push((object)[
                    'id' => $localUser ? $localUser->id : null,
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                    'golongan' => $ext->golongan,
                    'acting' => $ext->acting,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to local users with depthead role
            $deptApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%dept%'])->get()->map(function ($u) {
                return (object)[
                    'id' => $u->id,
                    'npk' => $u->npk,
                    'name' => $u->name,
                    'email' => $u->email,
                    'golongan' => null,
                    'acting' => null,
                ];
            });
        }

        return view('secthead.nqr.index', compact('nqrs', 'deptApprovers'));
    }

    public function deptHeadIndex(Request $request)
    {
        $query = Nqr::whereIn('status_approval', [
            'Menunggu Approval Sect Head',
            'Menunggu Approval Dept Head',
            'Menunggu Approval PPC Head',
            'Menunggu Approval VDD',
            'Menunggu Approval Procurement',
            'Ditolak Sect Head',
            'Ditolak Dept Head',
            'Ditolak PPC Head',
            'Ditolak VDD',
            'Ditolak Procurement',
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
                'menunggu_vdd' => 'Menunggu Approval VDD',
                'menunggu_procurement' => 'Menunggu Approval Procurement',
                'ditolak_foreman' => 'Ditolak Foreman',
                'ditolak_sect' => 'Ditolak Sect Head',
                'ditolak_dept' => 'Ditolak Dept Head',
                'ditolak_ppc' => 'Ditolak PPC Head',
                'ditolak_vdd' => 'Ditolak VDD',
                'ditolak_procurement' => 'Ditolak Procurement',
                'selesai' => 'Selesai',
            ];

            if (isset($mapping[$approval])) {
                $approval = $mapping[$approval];
            }

            $query->where('status_approval', $approval);
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Fetch PPC Head approvers from lembur database (dept=PPC, golongan=4, acting=1)
        $ppcApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'PPC')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $localUser = User::where('npk', $ext->npk)->first();
                $ppcApprovers->push((object)[
                    'id' => $localUser ? $localUser->id : null,
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                    'golongan' => $ext->golongan,
                    'acting' => $ext->acting,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to local users with ppchead role
            $ppcApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%ppc%'])->get()->map(function ($u) {
                return (object)[
                    'id' => $u->id,
                    'npk' => $u->npk,
                    'name' => $u->name,
                    'email' => $u->email,
                    'golongan' => null,
                    'acting' => null,
                ];
            });
        }

        return view('depthead.nqr.index', compact('nqrs', 'ppcApprovers'));
    }

    /**
     * Halaman approval untuk VDD
     */
    public function vddIndex(Request $request)
    {
        // Allow VDD to see/filter all statuses by default like PPC Head - do not restrict by whereIn
        $query = Nqr::with(['creator', 'updater']);

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
                'menunggu_vdd' => 'Menunggu Approval VDD',
                'menunggu_procurement' => 'Menunggu Approval Procurement',
                'ditolak_foreman' => 'Ditolak Foreman',
                'ditolak_sect' => 'Ditolak Sect Head',
                'ditolak_dept' => 'Ditolak Dept Head',
                'ditolak_ppc' => 'Ditolak PPC Head',
                'ditolak_vdd' => 'Ditolak VDD',
                'ditolak_procurement' => 'Ditolak Procurement',
                'selesai' => 'Selesai',
            ];

            if (isset($mapping[$approval])) {
                $approval = $mapping[$approval];
            }

            $query->where('status_approval', $approval);
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Fetch Procurement approvers from lembur database (dept=PROCUREMENT, golongan=4, acting=1)
        $procurementApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'PROCUREMENT')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $localUser = User::where('npk', $ext->npk)->first();
                $procurementApprovers->push((object)[
                    'id' => $localUser ? $localUser->id : null,
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                    'golongan' => $ext->golongan,
                    'acting' => $ext->acting,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to local users with procurement role
            $procurementApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%procurement%'])->get()->map(function ($u) {
                return (object)[
                    'id' => $u->id,
                    'npk' => $u->npk,
                    'name' => $u->name,
                    'email' => $u->email,
                    'golongan' => null,
                    'acting' => null,
                ];
            });
        }

        return view('vdd.nqr.index', compact('nqrs', 'procurementApprovers'));
    }

    /**
     * Halaman approval untuk Procurement
     */
    public function procurementIndex(Request $request)
    {
        // Make Procurement index permissive like VDD - allow filtering across all statuses
        $query = Nqr::with(['creator', 'updater']);

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
                'menunggu_vdd' => 'Menunggu Approval VDD',
                'menunggu_procurement' => 'Menunggu Approval Procurement',
                'ditolak_foreman' => 'Ditolak Foreman',
                'ditolak_sect' => 'Ditolak Sect Head',
                'ditolak_dept' => 'Ditolak Dept Head',
                'ditolak_ppc' => 'Ditolak PPC Head',
                'ditolak_vdd' => 'Ditolak VDD',
                'ditolak_procurement' => 'Ditolak Procurement',
                'selesai' => 'Selesai',
            ];

            if (isset($mapping[$approval])) {
                $approval = $mapping[$approval];
            }

            $query->where('status_approval', $approval);
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('procurement.nqr.index', compact('nqrs'));
    }
}
