<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OtpVerificationController;

// ============================
// Auth routes
// ============================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/captcha', [AuthController::class, 'captcha'])->name('captcha');
Route::get('/otp', [OtpVerificationController::class, 'show'])->name('otp.form');
Route::post('/otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
Route::post('/otp/resend', [OtpVerificationController::class, 'resend'])->name('otp.resend');

// ============================
// Protected routes (auth only)
// ============================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/monthly-data', [DashboardController::class, 'getMonthlyData'])->name('dashboard.monthlyData');

    // ============================
    // NQR Management (Global)
    // ============================
    Route::resource('nqr', \App\Http\Controllers\NqrController::class);

    // ============================
    // QC Area
    // ============================
    Route::prefix('qc')
        ->middleware(\App\Http\Middleware\EnsureUserIsQc::class)
        ->name('qc.')
        ->group(function () {
            // Exclude 'show' route for QC: QC does not have a separate show page
            Route::resource('lpk', \App\Http\Controllers\QC\LpkController::class)->except(['show']);
            // QC: request approvals (trigger approval workflow to other roles)
            Route::post('lpk/{id}/request', [\App\Http\Controllers\QC\LpkController::class, 'requestApproval'])->name('lpk.request');
            // QC: cancel approval (mark as canceled)
            Route::post('lpk/{id}/cancel', [\App\Http\Controllers\QC\LpkController::class, 'cancelApproval'])->name('lpk.cancel');
            Route::get('lpk/{id}/download-pdf', [\App\Http\Controllers\QC\LpkController::class, 'downloadPdf'])->name('lpk.downloadPdf');
            Route::get('lpk/{id}/preview-pdf', [\App\Http\Controllers\QC\LpkController::class, 'previewPdf'])->name('lpk.previewPdf');
            Route::get('lpk/{id}/download-excel', [\App\Http\Controllers\QC\LpkController::class, 'downloadExcel'])->name('lpk.downloadExcel');
            Route::resource('cmr', \App\Http\Controllers\QC\CmrController::class);
            // CMR approval/request routes for QC
            Route::post('cmr/{id}/request-approval', [\App\Http\Controllers\QC\CmrController::class, 'requestApproval'])->name('cmr.requestApproval');
            // QC no longer approves/rejects CMR directly; Sect Head / Dept Head handle approvals.
            // QC: cancel a previously sent approval request (mirror LPK cancel)
            Route::post('cmr/{id}/cancel', [\App\Http\Controllers\QC\CmrController::class, 'cancelApproval'])->name('cmr.cancel');
            Route::get('cmr/{id}/preview-fpdf', [\App\Http\Controllers\QC\CmrController::class, 'previewFpdf'])->name('cmr.previewFpdf');
            Route::resource('nqr', \App\Http\Controllers\QC\NqrController::class);
            // Route for FPDF export
            Route::get('nqr/{id}/export-pdf', [\App\Http\Controllers\QC\NqrController::class, 'exportPdf'])->name('nqr.exportPdf');
            // Route for FPDF preview (inline)
            Route::get('nqr/{id}/preview-fpdf', [\App\Http\Controllers\QC\NqrController::class, 'previewFpdf'])->name('nqr.previewFpdf');
            // NQR Approval routes for QC
            Route::post('nqr/{id}/request-approval', [\App\Http\Controllers\NqrApprovalController::class, 'requestApproval'])->name('nqr.requestApproval');
            Route::post('nqr/{id}/approve', [\App\Http\Controllers\NqrApprovalController::class, 'approveByQc'])->name('nqr.approve');
            Route::post('nqr/{id}/reject', [\App\Http\Controllers\NqrApprovalController::class, 'reject'])->name('nqr.reject');
            // NQR PDF routes
            Route::get('nqr/{id}/download-pdf', [\App\Http\Controllers\QC\NqrController::class, 'downloadPdf'])->name('nqr.downloadPdf');
            Route::get('nqr/{id}/preview-pdf', [\App\Http\Controllers\QC\NqrController::class, 'previewPdf'])->name('nqr.previewPdf');
        });

    // ============================
    // Foreman Area
    // ============================
    Route::prefix('foreman')
        ->middleware(\App\Http\Middleware\EnsureUserIsForeman::class)
        ->name('foreman.')
        ->group(function () {
            // Foreman: NQR index and inline approval actions
            Route::get('nqr', [\App\Http\Controllers\Foreman\NqrController::class, 'index'])->name('nqr.index');
            // Foreman: create / store / update using same controller logic as QC but under foreman routes
            Route::get('nqr/create', [\App\Http\Controllers\QC\NqrController::class, 'create'])->name('nqr.create');
            Route::post('nqr', [\App\Http\Controllers\QC\NqrController::class, 'store'])->name('nqr.store');
            Route::put('nqr/{nqr}', [\App\Http\Controllers\QC\NqrController::class, 'update'])->name('nqr.update');
            // Use QC preview controller for PDF rendering
            Route::get('nqr/{id}/preview-fpdf', [\App\Http\Controllers\QC\NqrController::class, 'previewFpdf'])->name('nqr.previewFpdf');
            // Allow foreman to edit/destroy via QC controller methods (access controlled by Foreman middleware here)
            Route::get('nqr/{nqr}/edit', [\App\Http\Controllers\QC\NqrController::class, 'edit'])->name('nqr.edit');
            Route::delete('nqr/{nqr}', [\App\Http\Controllers\QC\NqrController::class, 'destroy'])->name('nqr.destroy');

            // Approval actions mapped to shared approval controller
            Route::post('nqr/{id}/approve', [\App\Http\Controllers\NqrApprovalController::class, 'approveByQc'])->name('nqr.approve');
            Route::post('nqr/{id}/reject', [\App\Http\Controllers\NqrApprovalController::class, 'reject'])->name('nqr.reject');
            // Foreman dashboard (controller provides prepared data)
            Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'foremanDashboard'])->name('dashboard');
        });

    // ============================
    // Secthead Area
    // ============================
    Route::prefix('secthead')
        ->middleware(\App\Http\Middleware\EnsureUserIsSecthead::class)
        ->name('secthead.')
        ->group(function () {
            // Secthead: exclude 'show' - handled from index actions
            Route::resource('lpk', \App\Http\Controllers\Secthead\LpkController::class)->except(['show']);
            Route::post('lpk/{id}/approve', [\App\Http\Controllers\Secthead\LpkController::class, 'approve'])->name('lpk.approve');
            Route::post('lpk/{id}/reject', [\App\Http\Controllers\Secthead\LpkController::class, 'reject'])->name('lpk.reject');
            // Allow secthead to download LPK PDF/Excel
            Route::get('lpk/{id}/download-pdf', [\App\Http\Controllers\Secthead\LpkController::class, 'downloadPdf'])->name('lpk.downloadPdf');
            Route::get('lpk/{id}/preview-pdf', [\App\Http\Controllers\Secthead\LpkController::class, 'previewPdf'])->name('lpk.previewPdf');
            Route::get('lpk/{id}/download-excel', [\App\Http\Controllers\Secthead\LpkController::class, 'downloadExcel'])->name('lpk.downloadExcel');
            Route::resource('cmr', \App\Http\Controllers\Secthead\CmrController::class);
            Route::get('cmr/{id}/preview-fpdf', [\App\Http\Controllers\Secthead\CmrController::class, 'previewFpdf'])->name('cmr.previewFpdf');
            Route::post('cmr/{id}/approve', [\App\Http\Controllers\Secthead\CmrController::class, 'approve'])->name('cmr.approve');
            Route::post('cmr/{id}/reject', [\App\Http\Controllers\Secthead\CmrController::class, 'reject'])->name('cmr.reject');
            // NQR routes for Sect Head (inline approval di index)
            Route::get('nqr', [\App\Http\Controllers\NqrApprovalController::class, 'sectHeadIndex'])->name('nqr.index');
            Route::get('nqr/{id}/preview-fpdf', [\App\Http\Controllers\Secthead\NqrController::class, 'previewFpdf'])->name('nqr.previewFpdf');
            Route::post('nqr/{id}/approve', [\App\Http\Controllers\NqrApprovalController::class, 'approveBySectHead'])->name('nqr.approve');
            Route::post('nqr/{id}/reject', [\App\Http\Controllers\NqrApprovalController::class, 'reject'])->name('nqr.reject');
        });

    // ============================
    // Depthead Area
    // ============================
    Route::prefix('depthead')
        ->middleware(\App\Http\Middleware\EnsureUserIsDepthead::class)
        ->name('depthead.')
        ->group(function () {
            // Depthead: exclude 'show' - inline approve/reject handled from index
            Route::resource('lpk', \App\Http\Controllers\Depthead\LpkController::class)->except(['show']);
            Route::post('lpk/{id}/approve', [\App\Http\Controllers\Depthead\LpkController::class, 'approve'])->name('lpk.approve');
            Route::post('lpk/{id}/reject', [\App\Http\Controllers\Depthead\LpkController::class, 'reject'])->name('lpk.reject');
            // Allow depthead to download LPK PDF/Excel
            Route::get('lpk/{id}/download-pdf', [\App\Http\Controllers\Depthead\LpkController::class, 'downloadPdf'])->name('lpk.downloadPdf');
            Route::get('lpk/{id}/preview-pdf', [\App\Http\Controllers\Depthead\LpkController::class, 'previewPdf'])->name('lpk.previewPdf');
            Route::get('lpk/{id}/download-excel', [\App\Http\Controllers\Depthead\LpkController::class, 'downloadExcel'])->name('lpk.downloadExcel');
            Route::resource('cmr', \App\Http\Controllers\Depthead\CmrController::class);
            Route::get('cmr/{id}/preview-fpdf', [\App\Http\Controllers\Depthead\CmrController::class, 'previewFpdf'])->name('cmr.previewFpdf');
            Route::post('cmr/{id}/approve', [\App\Http\Controllers\Depthead\CmrController::class, 'approve'])->name('cmr.approve');
            Route::post('cmr/{id}/reject', [\App\Http\Controllers\Depthead\CmrController::class, 'reject'])->name('cmr.reject');
            // NQR routes for Dept Head (inline approval di index)
            Route::get('nqr', [\App\Http\Controllers\NqrApprovalController::class, 'deptHeadIndex'])->name('nqr.index');
            Route::get('nqr/{id}/preview-fpdf', [\App\Http\Controllers\Depthead\NqrController::class, 'previewFpdf'])->name('nqr.previewFpdf');
            Route::post('nqr/{id}/approve', [\App\Http\Controllers\NqrApprovalController::class, 'approveByDeptHead'])->name('nqr.approve');
            Route::post('nqr/{id}/reject', [\App\Http\Controllers\NqrApprovalController::class, 'reject'])->name('nqr.reject');
        });

    // ============================
    // PPC Head Area
    // ============================
    Route::prefix('ppchead')
        ->middleware(\App\Http\Middleware\EnsureUserIsPpchead::class)
        ->name('ppchead.')
        ->group(function () {
            Route::resource('lpk', \App\Http\Controllers\Ppchead\LpkController::class);
            Route::get('lpk/{id}/ppc-form', [\App\Http\Controllers\Ppchead\LpkController::class, 'showPpcForm'])->name('lpk.ppcForm');
            Route::post('lpk/{id}/ppc-form', [\App\Http\Controllers\Ppchead\LpkController::class, 'storePpcForm'])->name('lpk.ppcForm.store');
            Route::post('lpk/{id}/approve', [\App\Http\Controllers\Ppchead\LpkController::class, 'approve'])->name('lpk.approve');
            Route::post('lpk/{id}/reject', [\App\Http\Controllers\Ppchead\LpkController::class, 'reject'])->name('lpk.reject');
            // Allow ppchead to download LPK PDF/Excel via QC controller
            Route::get('lpk/{id}/download-pdf', [\App\Http\Controllers\QC\LpkController::class, 'downloadPdf'])->name('lpk.downloadPdf');
            Route::get('lpk/{id}/preview-pdf', [\App\Http\Controllers\QC\LpkController::class, 'previewPdf'])->name('lpk.previewPdf');
            Route::get('lpk/{id}/download-excel', [\App\Http\Controllers\QC\LpkController::class, 'downloadExcel'])->name('lpk.downloadExcel');
            Route::resource('cmr', \App\Http\Controllers\Ppchead\CmrController::class);
            // PPC form routes for CMR (show form and store PPC input)
            Route::get('cmr/{id}/ppc-form', [\App\Http\Controllers\Ppchead\CmrController::class, 'showPpcForm'])->name('cmr.ppcForm');
            Route::post('cmr/{id}/ppc-form', [\App\Http\Controllers\Ppchead\CmrController::class, 'storePpcForm'])->name('cmr.ppc.store');
            Route::get('cmr/{id}/preview-fpdf', [\App\Http\Controllers\Ppchead\CmrController::class, 'previewFpdf'])->name('cmr.previewFpdf');
            // PPC approve/reject actions for CMR
            Route::post('cmr/{id}/approve', [\App\Http\Controllers\Ppchead\CmrController::class, 'approve'])->name('cmr.approve');
            Route::post('cmr/{id}/reject', [\App\Http\Controllers\Ppchead\CmrController::class, 'reject'])->name('cmr.reject');
            Route::resource('nqr', \App\Http\Controllers\Ppchead\NqrController::class);
            // Route for FPDF preview (inline) for PPC Head
            Route::get('nqr/{id}/preview-fpdf', [\App\Http\Controllers\Ppchead\NqrController::class, 'previewFpdf'])->name('nqr.previewFpdf');
            // NQR Approval route for PPC
            Route::post('nqr/{id}/approve', [\App\Http\Controllers\NqrApprovalController::class, 'approveByPpc'])->name('nqr.approve');
            Route::post('nqr/{id}/reject', [\App\Http\Controllers\NqrApprovalController::class, 'reject'])->name('nqr.reject');
        });

    // ============================
    // VDD Area
    // ============================
    Route::prefix('vdd')
        ->middleware(\App\Http\Middleware\EnsureUserIsVdd::class)
        ->name('vdd.')
        ->group(function () {
            // VDD dashboard
            Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'vddDashboard'])->name('dashboard');

            // NQR approval pages for VDD
            Route::get('nqr', [\App\Http\Controllers\NqrApprovalController::class, 'vddIndex'])->name('nqr.index');
            Route::get('nqr/{id}/preview-fpdf', [\App\Http\Controllers\QC\NqrController::class, 'previewFpdf'])->name('nqr.previewFpdf');
            Route::post('nqr/{id}/approve', [\App\Http\Controllers\NqrApprovalController::class, 'approveByVdd'])->name('nqr.approve');
            Route::post('nqr/{id}/reject', [\App\Http\Controllers\NqrApprovalController::class, 'reject'])->name('nqr.reject');
            // CMR approval pages for VDD
            Route::resource('cmr', \App\Http\Controllers\Vdd\CmrController::class)->only(['index', 'show']);
            Route::get('cmr/{id}/preview-fpdf', [\App\Http\Controllers\Vdd\CmrController::class, 'previewFpdf'])->name('cmr.previewFpdf');
            Route::post('cmr/{id}/approve', [\App\Http\Controllers\Vdd\CmrController::class, 'approve'])->name('cmr.approve');
            Route::post('cmr/{id}/reject', [\App\Http\Controllers\Vdd\CmrController::class, 'reject'])->name('cmr.reject');
        });

    // ============================
    // Role-specific dashboards
    // ============================
    Route::view('/dashboard/qc', 'qc.dashboard')->name('dashboard.qc');
    Route::view('/dashboard/secthead', 'secthead.dashboard')->name('dashboard.secthead');
    Route::view('/dashboard/depthead', 'depthead.dashboard')->name('dashboard.depthead');
    Route::view('/dashboard/ppchead', 'ppchead.dashboard')->name('dashboard.ppchead');
    Route::view('/dashboard/agm', 'agm.dashboard')->name('dashboard.agm');
    Route::view('/dashboard/procurement', 'procurement.dashboard')->name('dashboard.procurement');

    // AGM Area
    Route::prefix('agm')
        ->middleware(\App\Http\Middleware\EnsureUserIsAgm::class)
        ->name('agm.')
        ->group(function () {
            // AGM: provide a CMR index page
            Route::resource('cmr', \App\Http\Controllers\Agm\CmrController::class)->only(['index', 'show']);
            Route::get('cmr/{id}/preview-fpdf', [\App\Http\Controllers\Agm\CmrController::class, 'previewFpdf'])->name('cmr.previewFpdf');
            Route::post('cmr/{id}/approve', [\App\Http\Controllers\Agm\CmrController::class, 'approve'])->name('cmr.approve');
            Route::post('cmr/{id}/reject', [\App\Http\Controllers\Agm\CmrController::class, 'reject'])->name('cmr.reject');
        });

    // Procurement Area
    Route::prefix('procurement')
        ->middleware(\App\Http\Middleware\EnsureUserIsProcurement::class)
        ->name('procurement.')
        ->group(function () {
            // Procurement: provide a CMR index page
            Route::resource('cmr', \App\Http\Controllers\Procurement\CmrController::class)->only(['index', 'show']);
            Route::get('cmr/{id}/preview-fpdf', [\App\Http\Controllers\Procurement\CmrController::class, 'previewFpdf'])->name('cmr.previewFpdf');
            Route::post('cmr/{id}/approve', [\App\Http\Controllers\Procurement\CmrController::class, 'approve'])->name('cmr.approve');
            Route::post('cmr/{id}/reject', [\App\Http\Controllers\Procurement\CmrController::class, 'reject'])->name('cmr.reject');
            // Procurement: input compensation form (Procurement fills pay_compensation and approves)
            Route::get('cmr/{id}/input-compensation', [\App\Http\Controllers\Procurement\CmrController::class, 'showInputCompensation'])->name('cmr.inputCompensation');
            Route::post('cmr/{id}/input-compensation', [\App\Http\Controllers\Procurement\CmrController::class, 'storeCompensation'])->name('cmr.storeCompensation');
            // Procurement: NQR approval
            Route::get('nqr', [\App\Http\Controllers\NqrApprovalController::class, 'procurementIndex'])->name('nqr.index');
            Route::get('nqr/{id}/preview-fpdf', [\App\Http\Controllers\QC\NqrController::class, 'previewFpdf'])->name('nqr.previewFpdf');
            // Procurement: input/pay compensation for NQR (show form + store)
            Route::get('nqr/{id}/input-pay-compensation', [\App\Http\Controllers\Procurement\NqrController::class, 'showInputPayCompensation'])->name('nqr.inputPayCompensation');
            Route::post('nqr/{id}/input-pay-compensation', [\App\Http\Controllers\Procurement\NqrController::class, 'storePayCompensation'])->name('nqr.storePayCompensation');
            Route::post('nqr/{id}/approve', [\App\Http\Controllers\NqrApprovalController::class, 'approveByProcurement'])->name('nqr.approve');
            Route::post('nqr/{id}/reject', [\App\Http\Controllers\NqrApprovalController::class, 'reject'])->name('nqr.reject');
        });

    // Notifications
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.markRead');
});