<?php

namespace App\Http\Controllers\QC;

use App\Http\Controllers\Controller;
use App\Models\Nqr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;

class NqrController extends Controller
{
    public function previewFpdf($id)
    {
    $nqr = Nqr::with('qcApprover')->findOrFail($id);
        $pdf = new \FPDF('L', 'mm', 'A4');
        $pdf->SetTitle('Laporan NQR');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false, 5);
        $pdf->SetFont('Arial', '', 9);

        // Header
        $pdf->Cell(50, 5, 'PT.KAYABA INDONESIA', 0, 1);
        $pdf->Cell(50, 5, 'QA.DEPT', 0, 0);

        $pdf->SetFont('Arial', 'U', 18);
        $pdf->SetXY(80, 10);
        $pdf->Cell(130, 15, 'NONCONFORMING QUALITY REPORT', 1, 0, 'C');

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(230, 10);
        $pdf->Cell(65, 8, 'Reg No : ' . $nqr->no_reg_nqr, 1, 0);
        $pdf->SetXY(230, 18);
        $pdf->Cell(65, 7, 'Issued Date : ' . ($nqr->tgl_terbit_nqr ? date('F d, Y', strtotime($nqr->tgl_terbit_nqr)) : '-'), 1, 0);
        $pdf->SetXY(230, 26);

        $pdf->SetY(27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 5, "Supplier Name\n" . $nqr->nama_supplier, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(60, 27);


        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 5, "Part Name\n" . $nqr->nama_part, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(110, 27);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(35, 5, 'Part No ' . ($nqr->part_no ?? ''), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(145, 27);

        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(25, 5, "PO No\n   " . $nqr->nomor_po, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(170, 27);

        $pdf->Cell(10, 5, 'Rev', 1, 0, 'C');
        $pdf->Cell(25, 5, 'Revision Item', 1, 0, 'C');
        $pdf->Cell(15, 5, 'Date', 1, 0, 'C');
        $pdf->Cell(25, 5, 'Dept Head', 1, 0, 'C');
        $pdf->Cell(25, 5, 'Sect Head', 1, 0, 'C');
        $pdf->Cell(25, 5, 'Foreman', 1, 1, 'C');

        $pdf->SetX(110);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(35, 5, $nqr->nomor_part ?? '', 1, 0, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetX(170);
        $pdf->Cell(10, 5, '', 1, 0, 'C');
        $pdf->SetX(180);
        $pdf->Cell(25, 5, ' ', 1, 0, 'C');
        $pdf->Cell(15, 5, '', 1, 0, 'C');

        // Dept.Head Approval
        $pdf->SetFont('Arial', '', 8);
        $yApproval = $pdf->GetY();
        $pdf->SetX(220);
        $pdf->Cell(25, 15, '', 1, 0, 'C');

        // Sect.Head Approval
        $pdf->SetX(245);
        $pdf->Cell(25, 15, '', 1, 0, 'C');

        // Foreman Approval
        $pdf->SetX(270);
        $pdf->Cell(25, 15, '', 1, 1, 'C');

        // Logic ceklis/approved/Rejected untuk masing-masing kotak
        // Dept.Head Approved
        if ($nqr->approved_by_dept_head && $nqr->approved_at_dept_head) {
            $pdf->SetFont('Arial', '', 7);
            $offsetYDept = -15;
            $iconYDept = $yApproval + 16 + $offsetYDept;
            $iconXDept = 223;
            $textXDept = $iconXDept + 4;
            $pdf->Image(public_path('icon/ceklis-ijo.png'), $iconXDept, $iconYDept, 4, 4);
            $pdf->SetXY($textXDept, $iconYDept + 1);
            $pdf->Cell(15, 4, 'Approved', 0, 2, 'L');
            $pdf->SetXY(220, $yApproval + 22 + $offsetYDept);
            $pdf->Cell(25, 4, date('d-m-Y', strtotime($nqr->approved_at_dept_head)), 0, 2, 'C');
            $pdf->SetXY(220, $yApproval + 26 + $offsetYDept);
            $pdf->Cell(25, 4, $nqr->deptHeadApprover ? $nqr->deptHeadApprover->name : '', 0, 2, 'C');
            $pdf->SetFont('Arial', '', 9);
        }

        // Dept.Head Rejected
        if ($nqr->status_approval === 'Ditolak Dept Head') {
            $pdf->SetFont('Arial', '', 7);
            $offsetYDept = -15;
            $iconYDept = $yApproval + 16 + $offsetYDept;
            $iconXDept = 223;
            $textXDept = $iconXDept + 4;
            $pdf->SetXY($textXDept, $iconYDept + 1);
            // set text color to red for Cancel label
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(15, 4, 'Cancel', 0, 2, 'L');
            // restore text color to black
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(220, $yApproval + 22 + $offsetYDept);
            $pdf->Cell(25, 4, date('d-m-Y', strtotime($nqr->updated_at)), 0, 2, 'C');
            $pdf->SetXY(220, $yApproval + 26 + $offsetYDept);
            $pdf->Cell(25, 4, $nqr->rejector && $nqr->rejector->name ? $nqr->rejector->name : ($nqr->deptHeadApprover ? $nqr->deptHeadApprover->name : ''), 0, 2, 'C');
            $pdf->SetFont('Arial', '', 9);
        }

        // Sect.Head Approved
        if ($nqr->approved_by_sect_head && $nqr->approved_at_sect_head) {
            $pdf->SetFont('Arial', '', 7);
            $offsetYSect = -15;
            $iconYSect = $yApproval + 16 + $offsetYSect;
            $iconXSect = 248;
            $textXSect = $iconXSect + 4;
            $pdf->Image(public_path('icon/ceklis-ijo.png'), $iconXSect, $iconYSect, 4, 4);
            $pdf->SetXY($textXSect, $iconYSect + 1);
            $pdf->Cell(15, 4, 'Approved', 0, 2, 'L');
            $pdf->SetXY(245, $yApproval + 22 + $offsetYSect);
            $pdf->Cell(25, 4, date('d-m-Y', strtotime($nqr->approved_at_sect_head)), 0, 2, 'C');
            $pdf->SetXY(245, $yApproval + 26 + $offsetYSect);
            $pdf->Cell(25, 4, $nqr->sectHeadApprover ? $nqr->sectHeadApprover->name : '', 0, 2, 'C');
            $pdf->SetFont('Arial', '', 9);
        }

        // Sect Head Rejected
        if ($nqr->status_approval === 'Ditolak Sect Head') {
            $pdf->SetFont('Arial', '', 7);
            $offsetY = -15;
            $iconY = $yApproval + 16 + $offsetY;
            $iconX = 248;
            $textX = $iconX + 4;
            $pdf->SetXY($textX, $iconY + 1);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(15, 4, 'Cancel', 0, 2, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(245, $yApproval + 22 + $offsetY);
            $pdf->Cell(25, 4, date('d-m-Y', strtotime($nqr->updated_at)), 0, 2, 'C');
            $pdf->SetXY(245, $yApproval + 26 + $offsetY);
            $rejectorName = ($nqr->rejector && $nqr->rejector->name) ? $nqr->rejector->name : ($nqr->sectHeadApprover ? $nqr->sectHeadApprover->name : '');
            $pdf->Cell(25, 4, $rejectorName, 0, 2, 'C');
            $pdf->SetFont('Arial', '', 9);
        }

        // Foreman (QC) Approved
        if ($nqr->approved_by_qc && $nqr->approved_at_qc && $nqr->status_approval !== 'Ditolak Foreman') {
            $pdf->SetFont('Arial', '', 7);
            $offsetY = -15;
            $iconY = $yApproval + 16 + $offsetY;
            $iconX = 273;
            $textX = $iconX + 4;
            $pdf->Image(public_path('icon/ceklis-ijo.png'), $iconX, $iconY, 4, 4);
            $pdf->SetXY($textX, $iconY + 1);
            $pdf->Cell(15, 4, 'Approved', 0, 2, 'L');
            $pdf->SetXY(270, $yApproval + 22 + $offsetY);
            $pdf->Cell(25, 4, date('d-m-Y', strtotime($nqr->approved_at_qc)), 0, 2, 'C');
            $pdf->SetXY(270, $yApproval + 26 + $offsetY);
            $pdf->Cell(25, 4, $nqr->qcApprover ? $nqr->qcApprover->name : '', 0, 2, 'C');
            $pdf->SetFont('Arial', '', 9);
        }

        // Foreman (QC) Rejected (status_approval)
        if ($nqr->status_approval === 'Ditolak Foreman') {
            $pdf->SetFont('Arial', '', 7);
            $offsetY = -15;
            $iconY = $yApproval + 16 + $offsetY;
            $iconX = 273;
            $textX = $iconX + 4;
            $pdf->SetXY($textX, $iconY + 1);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(15, 4, 'Cancel', 0, 2, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(270, $yApproval + 22 + $offsetY);
            $pdf->Cell(25, 4, date('d-m-Y', strtotime($nqr->updated_at)), 0, 2, 'C');
            $pdf->SetXY(270, $yApproval + 26 + $offsetY);
            $rejectorName = ($nqr->rejector && $nqr->rejector->name) ? $nqr->rejector->name : ($nqr->qcApprover ? $nqr->qcApprover->name : '');
            $pdf->Cell(25, 4, $rejectorName, 0, 2, 'C');
            $pdf->SetFont('Arial', '', 9);
        }


        // Bagian problem, invoice, order, total, note
        $pdf->SetXY(10, 37);
        $pdf->Cell(100, 70, '', 1, 0);
        $pdf->SetXY(12, 37);
        $pdf->Cell(10, 5, 'Search of problem :', 0, 0);
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(12, 57);
            $imgY = $pdf->GetY();
            if (!empty($nqr->gambar)) {
                $imagePath = public_path('storage/' . $nqr->gambar);
                if (file_exists($imagePath)) {
                    $pdf->Image($imagePath, $pdf->GetX() + 10, $imgY - 15, 80, 40);
                    $imgBottomY = $imgY - 15 + 40;
                } else {
                    $pdf->Cell(96, 10, 'Gambar tidak ditemukan', 0, 1, 'C');
                    $imgBottomY = $pdf->GetY();
                }
            } else {
                $pdf->Cell(96, 10, 'Tidak ada gambar', 0, 1, 'C');
                $imgBottomY = $pdf->GetY();
            }

            if (!empty($nqr->detail_gambar)) {
                $pdf->SetFont('Arial', '', 6);
                $pdf->SetXY(12, $imgBottomY + 2);
                $pdf->MultiCell(96, 2, $nqr->detail_gambar, 0, 'L');
                $pdf->SetFont('Arial', '', 10);
            }

        $pdf->SetFont('Arial', '', 6);
        $pdf->SetXY(12, 67);
        $pdf->SetXY(12, 82);
        $pdf->Cell(30, 29, 'INVOICE', 0, 0);
        $pdf->Cell(66, 29, ($nqr->invoice ?? ''), 0, 1);
        $pdf->SetXY(12, 87);
        $pdf->Cell(30, 25, 'ORDER :', 0, 0);
        $pdf->Cell(66, 25, ($nqr->nomor_po ?? ''), 0, 1);
        $pdf->SetXY(12, 92);
        $pdf->Cell(30, 21, 'TOTAL DEL :', 0, 0);
        $pdf->Cell(66, 21, ($nqr->total_del ?? '') . ' Pcs', 0, 1);
        $pdf->SetXY(12, 97);
        $pdf->Cell(30, 17, 'TOTAL CLAIM :', 0, 0);
        $pdf->Cell(66, 17, ($nqr->total_claim ?? '') . ' Pcs', 0, 1);
        $pdf->SetXY(12, 102);
        if (!empty($nqr->note)) {
            $pdf->Cell(96, -2, '(' . $nqr->note . ')', 0, 1, 'C');
        } else {
            $pdf->Cell(96, -2, '', 0, 1, 'C');
        }
        $pdf->SetFont('Arial', '', 10);

        $pdf->SetXY(115, 37);
        $pdf->Cell(35, 5, 'Claim:', 0, 1);
        $pdf->SetX(115);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50, 5, 'Complaint[Information]', 0, 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(111, 38);
        $pdf->Cell(3, 3, '', 1, 1);
        $pdf->SetXY(111, 43);
        $pdf->Cell(3, 3, '', 1, 0);
        if (($nqr->status_nqr ?? null) === 'Claim') {
            $pdf->Image(public_path('icon/ceklis.png'), 110, 37, 5, 5);
        } elseif (($nqr->status_nqr ?? null) === 'Complaint (Informasi)') {
            $pdf->Image(public_path('icon/ceklis.png'), 110, 42, 5, 5);
        }

        $pdf->SetXY(145, 37);
        $pdf->Cell(75, 10, '', 1, 1, 'C');
        $pdf->SetXY(145, 37);
        $pdf->Cell(75, 5, 'Delivery Date', 0, 1);
        $pdf->SetXY(145, 42);
        $tglTerbit = $nqr->tgl_terbit_nqr ? date('d-m-Y', strtotime($nqr->tgl_terbit_nqr)) : '';
        $pdf->Cell(75, 5, $tglTerbit, 0, 1, 'C');

        $pdf->SetXY(220, 37);
        if (($nqr->sts_mgr_qa ?? null) == 1) {
            $date_formatted = ($nqr->dt_mgr_qa == '0000-00-00 00:00:00') ? '' : date('d-m-Y H:i', strtotime($nqr->dt_mgr_qa));
            $pdf->Cell(25, 5, $date_formatted, 0, 1, 'C');
        }
        $pdf->SetXY(245, 37);
        if (($nqr->sts_spv_qa ?? null) == 1) {
            $date_formatted = ($nqr->dt_spv_qa == '0000-00-00 00:00:00') ? '' : date('d-m-Y H:i', strtotime($nqr->dt_spv_qa));
            $pdf->Cell(25, 5, $date_formatted, 0, 1, 'C');
        }
        $pdf->SetXY(270, 37);
        if (($nqr->sts_fm_qa ?? null) == 1) {
            $date_formatted = ($nqr->dt_fm_qa == '0000-00-00 00:00:00') ? '' : date('d-m-Y H:i', strtotime($nqr->dt_fm_qa));
            $pdf->Cell(25, 5, $date_formatted, 0, 1, 'C');
        }
        $pdf->SetXY(220, 42);
        if (($nqr->sts_mgr_qa ?? null) == 1) {
            $pdf->Cell(25, 5, $nqr->nm_mgr_qa ?? '', 0, 1, 'C');
        }
        $pdf->SetXY(245, 42);
        if (($nqr->sts_spv_qa ?? null) == 1) {
            $pdf->Cell(25, 5, $nqr->nm_spv_qa ?? '', 0, 1, 'C');
        }
        $pdf->SetXY(270, 42);
        if (($nqr->sts_fm_qa ?? null) == 1) {
            $pdf->Cell(25, 5, $nqr->nm_fm_qa ?? '', 0, 1, 'C');
        }

        // Location Claim Occur
        $pdf->SetXY(110, 47);
        $pdf->Cell(35, 60, '', 1, 1, 'C');
        $pdf->SetXY(110, 47);
        $pdf->Cell(10, 5, 'Location Claim Occur', 0, 0);
        $pdf->SetXY(115, 57);
        $pdf->Cell(20, 5, 'Receiving Insp', 0, 0);
        $pdf->SetXY(115, 67);
        $pdf->Cell(20, 5, 'In-Process', 0, 0);
        $pdf->SetXY(115, 77);
        $pdf->Cell(20, 5, 'Customer', 0, 0);
        $pdf->SetXY(111, 58);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(111, 68);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(111, 78);
        $pdf->Cell(3, 3, '', 1, 0);

        if (($nqr->location_claim_occur ?? null) === 'Receiving Insp') {
            $pdf->Image(public_path('icon/ceklis.png'), 110, 57, 5, 5);
        } elseif (($nqr->location_claim_occur ?? null) === 'In-Process') {
            $pdf->Image(public_path('icon/ceklis.png'), 110, 67, 5, 5);
        } elseif (($nqr->location_claim_occur ?? null) === 'Customer') {
            $pdf->Image(public_path('icon/ceklis.png'), 110, 77, 5, 5);
        }


        $pdf->SetXY(145, 47);
        $pdf->Cell(75, 5, 'Disposition of inventory', 1, 1, 'C');
        $pdf->SetXY(145, 52);
        $pdf->Cell(40, 55, '', 1, 1, 'C');
        $pdf->SetXY(145, 52);
        $pdf->Cell(40, 5, 'At customer', 0, 0);
        $pdf->SetXY(150, 57);
        $pdf->Cell(40, 5, 'Sorted by Customer', 0, 0);
        $pdf->SetXY(115, 57);
        $pdf->SetXY(150, 62);
        $pdf->SetXY(150, 72);
        $pdf->Cell(40, 5, 'Sorted by Supplier', 0, 0);
        $pdf->SetXY(150, 77);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(150, 87);
        $pdf->Cell(40, 5, 'Sorted by PT.KYBI', 0, 0);
        $pdf->SetXY(150, 102);
        $pdf->Cell(40, 5, 'Keep to use', 0, 0);
        $pdf->SetXY(146, 58);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(146, 73);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(146, 88);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(146, 103);
        $pdf->Cell(3, 3, '', 1, 0);

        if (!empty($nqr->disposition_inventory_location) && !empty($nqr->disposition_inventory_action)) {
            $iconPath = public_path('icon/ceklis.png');

            if ($nqr->disposition_inventory_location === 'At Customer') {
                if ($nqr->disposition_inventory_action === 'Sorted by Customer') {
                    $pdf->Image($iconPath, 145, 57, 5, 5);
                } elseif ($nqr->disposition_inventory_action === 'Sorted by Supplier') {
                    $pdf->Image($iconPath, 145, 72, 5, 5);
                } elseif ($nqr->disposition_inventory_action === 'Sorted by PT.KYBI') {
                    $pdf->Image($iconPath, 145, 87, 5, 5);
                } elseif ($nqr->disposition_inventory_action === 'Keep to use' || $nqr->disposition_inventory_action === 'Keep to Use') {
                    $pdf->Image($iconPath, 145, 102, 5, 5);
                }
            }

            if ($nqr->disposition_inventory_location === 'At PT.KYBI') {
                if ($nqr->disposition_inventory_action === 'Sorted by Supplier') {
                    $pdf->Image($iconPath, 185, 57, 5, 5);
                } elseif ($nqr->disposition_inventory_action === 'Sorted by PT.KYBI') {
                    $pdf->Image($iconPath, 185, 71, 5, 5);
                } elseif ($nqr->disposition_inventory_action === 'Keep to use' || $nqr->disposition_inventory_action === 'Keep to Use') {
                    $pdf->Image($iconPath, 185, 87, 5, 5);
                }
            }
        }

        $pdf->SetXY(185, 52);
        $pdf->Cell(35, 55, '', 1, 1, 'C');
        $pdf->SetXY(185, 52);
        $pdf->Cell(35, 5, 'At PT. KYBI', 0, 0);
        $pdf->SetXY(186, 58);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(190, 57);
        $pdf->Cell(35, 5, 'Sorted by Supplier', 0, 0);
        $pdf->SetXY(190, 62);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetXY(190, 67);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(186, 72);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(190, 71);
        $pdf->Cell(40, 5, 'Sorted by PT.KYBI', 0, 0);
        $pdf->SetXY(186, 88);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(190, 87);
        $pdf->Cell(40, 5, 'Keep to use', 0, 0);

        // Claim occurance freq.
        $pdf->SetXY(220, 47);
        $pdf->Cell(40, 60, '', 1, 1, 'C');
        $pdf->SetXY(225, 47);
        $pdf->Cell(40, 5, 'Claim occurance freq.', 0, 0);
        $pdf->SetXY(221, 53);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(225, 52);
        $pdf->Cell(40, 5, 'First time', 0, 0);
        $pdf->SetXY(221, 58);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(225, 57);
        $pdf->Cell(40, 5, 'Reoccured/routin', 0, 0);
        $pdf->SetXY(225, 62);
        if (!empty($nqr->routin) && $nqr->routin != 0) {
            $pdf->Cell(35, 5, '(' . $nqr->routin . ' times)', 0, 0);
        }
        if (!empty($nqr->claim_occurence_freq)) {
            $iconPath = public_path('icon/ceklis.png');
            if ($nqr->claim_occurence_freq === 'First Time') {
                $pdf->SetXY(220, 52);
                $pdf->Image($iconPath, 220, 52, 5, 5);
            } elseif ($nqr->claim_occurence_freq === 'Reoccurred/Routine') {
                $pdf->SetXY(220, 57);
                $pdf->Image($iconPath, 220, 57, 5, 5);
            }
        }

        // Disposition of defect part
        $pdf->SetXY(220, 72);
        $pdf->Cell(40, 5, 'Disposition of defect part', 0, 0);
        $pdf->SetXY(221, 78);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(225, 77);
        $pdf->Cell(40, 5, 'Keep to use', 0, 0);
        $pdf->SetXY(221, 83);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(225, 82);
        $pdf->Cell(40, 5, 'Return to Supplier', 0, 0);
        $pdf->SetXY(225, 87);
        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(35, 5, $nqr->nama_supplier ?? '', 0, 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(221, 93);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(225, 92);
        $pdf->Cell(40, 5, 'Scrapped at PT.KYBI', 0, 0);

        if ($nqr->dodp == 1) {
            $pdf->SetXY(220, 77);
            $pdf->Image(public_path('icon/ceklis.png'), 220, 77, 5, 5);
        } elseif ($nqr->dodp == 2) {
            $pdf->SetXY(220, 82);
            $pdf->Image(public_path('icon/ceklis.png'), 220, 82, 5, 5);
        } elseif ($nqr->dodp == 3) {
            $pdf->SetXY(220, 92);
            $pdf->Image(public_path('icon/ceklis.png'), 220, 92, 5, 5);
        }
        if (!empty($nqr->disposition_defect_part)) {
            $iconPath = public_path('icon/ceklis.png');
            if ($nqr->disposition_defect_part === 'Keep to Use') {
                $pdf->SetXY(220, 77);
                $pdf->Image($iconPath, 220, 77, 5, 5);
            } elseif ($nqr->disposition_defect_part === 'Return to Supplier') {
                $pdf->SetXY(220, 82);
                $pdf->Image($iconPath, 220, 82, 5, 5);
            } elseif ($nqr->disposition_defect_part === 'Scrapped at PT.KYBI') {
                $pdf->SetXY(220, 92);
                $pdf->Image($iconPath, 220, 92, 5, 5);
            }
        }

        // Disposition of this Claim
        $pdf->SetXY(260, 47);
        $pdf->Cell(35, 60, '', 1, 1, 'C');
        $pdf->SetXY(260, 47);
        $pdf->Cell(40, 5, 'Disposition of this Claim', 0, 0);
        $pdf->SetXY(261, 53);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(265, 52);
        $pdf->Cell(40, 5, 'Pay compensation', 0, 0);

        if ($nqr->disposition_claim === 'Pay Compensation' && !empty($nqr->pay_compensation_value)) {
            // Get currency data
            $ppc_currency = $nqr->pay_compensation_currency ?? '';
            $ppc_currency_symbol = $nqr->pay_compensation_currency_symbol ?? '';
            $ppc_nominal = $nqr->pay_compensation_value;

            // Determine currency symbol
            $currencySymbol = '';
            if (!empty($ppc_currency_symbol)) {
                // Use explicit symbol if provided
                $currencySymbol = $ppc_currency_symbol;
            } else {
                // Map currency code to symbol
                switch (strtoupper($ppc_currency)) {
                    case 'IDR':
                        $currencySymbol = 'Rp';
                        break;
                    case 'USD':
                        $currencySymbol = '$';
                        break;
                    case 'JPY':
                        $currencySymbol = 'JPY';
                        break;
                    case 'MYR':
                        $currencySymbol = 'RM';
                        break;
                    case 'VND':
                        $currencySymbol = 'VND';
                        break;
                    case 'THB':
                        $currencySymbol = 'THB';
                        break;
                    case 'KRW':
                        $currencySymbol = 'KRW';
                        break;
                    case 'INR':
                        $currencySymbol = 'INR';
                        break;
                    case 'CNY':
                        $currencySymbol = 'CNY';
                        break;
                    case 'CUSTOM':
                        $currencySymbol = '';
                        break;
                    default:
                        $currencySymbol = '';
                }
            }

            // Format the nominal value
            $valueText = '';
            if (is_numeric($ppc_nominal)) {
                // Format without decimals
                $valueText = number_format((float)$ppc_nominal, 0, ',', '.');
            } else {
                $valueText = $ppc_nominal;
            }

            // Combine symbol and value with proper spacing
            if (!empty($currencySymbol) && !empty($valueText)) {
                $display = $currencySymbol . ' ' . $valueText;
            } elseif (!empty($valueText)) {
                $display = $valueText;
            } else {
                $display = '';
            }

            // Display on PDF
            $pdf->SetXY(265, 57);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(35, 5, $display, 0, 0);
            $pdf->SetFont('Arial', '', 9);
        }

        $pdf->SetXY(265, 67);
        $pdf->Cell(35, 5, '', 0, 0);
        $pdf->SetXY(261, 73);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(265, 72);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40, 5, 'Send the replacement', 0, 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(266, 78);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(270, 77);
        $pdf->Cell(35, 5, 'Q By Air', 0, 0);
        $pdf->SetXY(266, 83);
        $pdf->Cell(3, 3, '', 1, 0);
        $pdf->SetXY(270, 82);
        $pdf->Cell(35, 5, 'Q By Sea', 0, 0);
        $pdf->SetXY(273, 92);
        $pdf->Cell(20, 5, 'PPC Dept', 1, 0);

        $pdf->SetXY(273, 92);
        $yKotakPpc = $pdf->GetY();
        $pdf->Cell(20, 15, '', 1, 1);

        // PPC Head Approved
        if ($nqr->approved_by_ppc && $nqr->approved_at_ppc) {
            $pdf->SetFont('Arial', '', 6);
            $offsetYPpc = -10;
            $jarakY = -2.5;
            $iconYPpc = $yKotakPpc + 16 + $offsetYPpc;
            $iconXPpc = 277;
            $textXPpc = $iconXPpc + 3;
            $pdf->Image(public_path('icon/ceklis-ijo.png'), $iconXPpc, $iconYPpc, 2.5, 2.5);
            $pdf->SetXY($textXPpc, $iconYPpc + 0.5);
            $pdf->Cell(10, 3, 'Approved', 0, 2, 'L');
            $pdf->SetXY(273, $yKotakPpc + 22 + $offsetYPpc + $jarakY);
            $pdf->Cell(20, 3, date('d-m-Y', strtotime($nqr->approved_at_ppc)), 0, 2, 'C');
            $pdf->SetXY(273, $yKotakPpc + 25 + $offsetYPpc + $jarakY);
            $pdf->Cell(20, 3, $nqr->ppcApprover ? $nqr->ppcApprover->name : '', 0, 2, 'C');
            $pdf->SetFont('Arial', '', 9);
        }

        // PPC Head Rejected
        if ($nqr->status_approval === 'Ditolak PPC Head') {
            $pdf->SetFont('Arial', '', 6);
            $offsetYPpc = -10;
            $jarakY = -2.5;
            $iconYPpc = $yKotakPpc + 16 + $offsetYPpc;
            $iconXPpc = 277;
            $textXPpc = $iconXPpc + 3;
            $pdf->SetXY($textXPpc, $iconYPpc + 0.5);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(10, 3, 'Cancel', 0, 2, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(273, $yKotakPpc + 22 + $offsetYPpc + $jarakY);
            $pdf->Cell(20, 3, date('d-m-Y', strtotime($nqr->updated_at)), 0, 2, 'C');
            $pdf->SetXY(273, $yKotakPpc + 25 + $offsetYPpc + $jarakY);
            $pdf->Cell(20, 3, $nqr->rejector && $nqr->rejector->name ? $nqr->rejector->name : ($nqr->ppcApprover ? $nqr->ppcApprover->name : ''), 0, 2, 'C');
            $pdf->SetFont('Arial', '', 9);
        }

        $iconPath = public_path('icon/ceklis.png');
        if (!empty($nqr->disposition_claim)) {
            if ($nqr->disposition_claim === 'Pay Compensation') {
                $pdf->Image($iconPath, 260, 52, 5, 5);
            } elseif ($nqr->disposition_claim === 'Send the Replacement') {
                $pdf->Image($iconPath, 260, 72, 5, 5);
                if (!empty($nqr->send_replacement_method)) {
                    if ($nqr->send_replacement_method === 'By Air') {
                        $pdf->Image($iconPath, 265, 77, 5, 5);
                    } elseif ($nqr->send_replacement_method === 'By Sea') {
                        $pdf->Image($iconPath, 265, 82, 5, 5);
                    }
                }
            }
        }

        $pdf->SetY(107);
        $pdf->Cell(20, 5, 'Fill in by Supplier', 0, 1);

        $pdf->SetY(112);
        $pdf->Cell(215, 5, 'PROBLEM IDENTIFICATION', 1, 0, 'C');
        $pdf->SetXY(226, 112);
        $pdf->Cell(30, 5, 'SUPPLIER:', 1, 0, 'C');
        $pdf->SetX(260);
        $pdf->Cell(35, 5, 'VERIFICATION', 1, 1, 'C');

        $pdf->Cell(50, 5, 'ROOT CASE', 1, 0, 'C');
        $pdf->Cell(140, 5, 'TEMPORARY ACTION & PERMANENT ACTION', 1, 0, 'C');
        $pdf->Cell(25, 5, 'SCHEDULE', 1, 0, 'C');
        $pdf->SetX(226);
        $pdf->Cell(30, 25, '', 1, 0);
        $pdf->SetX(226);
        $pdf->Cell(30, 5, 'Approved:', 0, 0, 'C');

        $pdf->SetX(260);
        $pdf->Cell(12, 5, 'M-1', 1, 0, 'C');
        $pdf->Cell(12, 5, 'M-2', 1, 0, 'C');
        $pdf->Cell(11, 5, 'M-3', 1, 1, 'C');

        $pdf->Cell(50, 75, '', 1, 0);
        $xTempPerm = $pdf->GetX();
        $yTempPerm = $pdf->GetY();
        $pdf->Cell(140, 75, '', 1, 0);

        if (in_array($nqr->status_approval, ['Ditolak Foreman', 'Ditolak Sect Head', 'Ditolak Dept Head', 'Ditolak PPC Head'])) {
            $pdf->SetXY($xTempPerm + 60, $yTempPerm + 30);
            $pdf->SetFont('Arial', 'B', 30);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(0, 20, 'Cancel', 0, 1, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 9);
        }

        $pdf->SetXY($xTempPerm + 140, $yTempPerm);
        $pdf->Cell(25, 75, '', 1, 0);

        $pdf->SetXY(226, 142);
        $pdf->Cell(30, 25, '', 1, 0);
        $pdf->SetX(226);
        $pdf->Cell(30, 5, 'Checked:', 0, 0, 'C');

        $pdf->SetXY(260, 122);
        $pdf->Cell(12, 10, '', 1, 0);
        $pdf->Cell(12, 10, '', 1, 0);
        $pdf->Cell(11, 10, '', 1, 0);

        $pdf->SetXY(260, 132);
        $pdf->Cell(35, 45, '', 1, 1);

        $pdf->SetXY(260, 132);
        $pdf->Cell(35, 5, 'REMARK:', 0, 0);

        $pdf->SetXY(226, 167);
        $pdf->Cell(30, 30, '', 1, 0, '');
        $pdf->SetX(22);

        $pdf->SetXY(260, 177);
        $pdf->Cell(20, 20, '', 1, 0);
        $pdf->Cell(15, 20, '', 1, 0);

        $pdf->SetXY(260, 177);
        $pdf->Cell(20, 5, 'JUDGE:', 0, 0);

        $pdf->SetXY(260, 182);
        $pdf->SetXY(260, 187);
        $pdf->SetXY(280, 177);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetY(197);
        $pdf->Cell(20, 5, 'Bold Line to be filled by PT.KYBI', 0, 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, 'Route: QA Receiving Insp. PT.KYBI -> Supplier -> VD PT.KYBI -> QA Receiving PT.KYBI', 0, 1);

        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="laporan-nqr.pdf"');
        $pdf->Output('I', 'laporan-nqr.pdf');
        exit;
    }

    public function downloadPdf($id)
    {
        return redirect()->back()->with('error', 'Gunakan tombol/route Export PDF (FPDF) untuk download PDF NQR.');
    }

    public function previewPdf($id)
    {
        $nqr = Nqr::findOrFail($id);

        if ($nqr->status_approval === 'Menunggu Request dikirimkan') {
            return redirect()->back()->with('error', 'NQR belum di-request. Silakan request approval terlebih dahulu.');
        }

        $pdf = Pdf::loadView('qc.nqr.export_pdf', compact('nqr'));
        $pdf->setPaper('a5', 'landscape');
        $raw = 'NQR-'.$nqr->no_reg_nqr.'-preview.pdf';
        $filename = $this->sanitizeFilename($raw);
        return $pdf->stream($filename);
    }

    protected function sanitizeFilename(string $name): string
    {
        $safe = str_replace(['/', '\\', '%'], '-', $name);
        $safe = preg_replace('/[\x00-\x1F\x7F]+/u', '', $safe);
        $safe = trim($safe);
        if ($safe === '') {
            $safe = 'nqr_export_'.date('Ymd');
        }
        return $safe;
    }

    public function index(Request $request)
    {
        $query = Nqr::with(['creator', 'updater']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('no_reg_nqr', 'like', "%{$search}%")
                  ->orWhere('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('nama_part', 'like', "%{$search}%")
                  ->orWhere('nomor_po', 'like', "%{$search}%")
                  ->orWhere('nomor_part', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
                $query->whereDate('tgl_terbit_nqr', $date);
            } catch (\Exception $e) {
            }
        }

        if ($request->filled('year')) {
            $query->whereYear('tgl_terbit_nqr', $request->year);
        }

        if ($request->filled('status_nqr')) {
            $query->where('status_nqr', $request->status_nqr);
        }

        if ($request->filled('approval_status')) {
            $status = $request->approval_status;

            switch ($status) {
                case 'menunggu_request':
                    $query->where('status_approval', 'Menunggu Request dikirimkan');
                    break;
                case 'menunggu_foreman':
                    $query->where('status_approval', 'Menunggu Approval Foreman');
                    break;
                case 'menunggu_sect':
                    $query->where('status_approval', 'Menunggu Approval Sect Head');
                    break;
                case 'menunggu_dept':
                    $query->where('status_approval', 'Menunggu Approval Dept Head');
                    break;
                case 'menunggu_ppc':
                    $query->where('status_approval', 'Menunggu Approval PPC Head');
                    break;
                case 'ditolak_foreman':
                    $query->where('status_approval', 'Ditolak Foreman');
                    break;
                case 'ditolak_sect':
                    $query->where('status_approval', 'Ditolak Sect Head');
                    break;
                case 'ditolak_dept':
                    $query->where('status_approval', 'Ditolak Dept Head');
                    break;
                case 'ditolak_ppc':
                    $query->where('status_approval', 'Ditolak PPC Head');
                    break;
                case 'selesai':
                    $query->where('status_approval', 'Selesai');
                    break;
            }
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('qc.nqr.index', compact('nqrs'));
    }

    public function create()
    {
        $noRegNqr = '####/NQR/' . Nqr::getRomanMonth(date('n')) . '/' . date('Y');

        // Fetch supplier master data and part items for dropdowns (like LPK)
        $suppliers = DB::table('por_supplier')->orderBy('por_nama')->get();
        $items = DB::table('por_item')->select('kode', 'description')->orderBy('kode')->get();

        // If current user is Foreman, render the foreman-specific create view
        try {
            $rawRole = auth()->user()->role ?? '';
            $r = strtolower(preg_replace('/[\s_\-]/', '', $rawRole));
            if (str_contains($r, 'foreman')) {
                return view('foreman.nqr.create', compact('noRegNqr', 'suppliers', 'items'));
            }
        } catch (\Exception $e) {
            // fallback to QC view if anything goes wrong
        }

        return view('qc.nqr.create', compact('noRegNqr', 'suppliers', 'items'));
    }

    public function store(Request $request)
    {
        // If nomor_part provided but nama_part is empty, try to fill nama_part from por_item master
        try {
            if ($request->filled('nomor_part') && !$request->filled('nama_part')) {
                $item = DB::table('por_item')->where('kode', $request->input('nomor_part'))->first();
                if ($item && isset($item->description)) {
                    $request->merge(['nama_part' => $item->description]);
                }
            }
        } catch (\Exception $e) {
            // ignore lookup failures; validation will handle missing fields
        }

        $validated = $request->validate([
            'tgl_terbit_nqr' => 'required|date',
            'tgl_delivery' => 'required|date',
            'nama_supplier' => 'required|string|max:255',
            'nama_part' => 'required|string|max:255',
            'nomor_po' => 'required|string|max:255',
            'nomor_part' => 'required|string|max:255',
            'status_nqr' => 'required|in:Claim,Complaint (Informasi)',
            'location_claim_occur' => 'required|in:Receiving Insp,In-Process,Customer',
            'disposition_inventory_location' => 'required|in:At Customer,At PT.KYBI',
            'disposition_inventory_action' => 'required|string',
            'claim_occurence_freq' => 'required|in:First Time,Reoccurred/Routine',
            'disposition_defect_part' => 'required|in:Keep to Use,Return to Supplier,Scrapped at PT.KYBI',
            'invoice' => 'required|string|max:255',
            'total_del' => 'required|string|max:255',
            'total_claim' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'detail_gambar' => 'nullable|string|max:265',
            'disposition_claim' => 'nullable|string',
            'pay_compensation_value' => 'nullable|numeric',
            'pay_compensation_currency' => 'nullable|string|max:10',
            'pay_compensation_currency_symbol' => 'nullable|string|max:10',
            'send_replacement_method' => 'nullable|string|max:50',
        ]);

        $validated['no_reg_nqr'] = Nqr::generateNoRegNqr();

        $validated['order'] = $validated['nomor_po'];

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('nqr_images', $filename, 'public');
            $validated['gambar'] = $path;
        }

        $validated['created_by'] = Auth::id();

        $nqr = Nqr::create($validated);

        // If the current user is Foreman and created this NQR via foreman routes,
        // mark it as already requested for Foreman approval so it appears in the
        // Foreman index (Foreman should be able to approve immediately).
        try {
            $rawRole = auth()->user()->role ?? '';
            $r = strtolower(preg_replace('/[\s_\-]/', '', $rawRole));
            if (str_contains($r, 'foreman')) {
                if (\Schema::hasColumn('nqrs', 'status_approval')) {
                    $nqr->status_approval = 'Menunggu Approval Foreman';
                    $nqr->save();
                }
            }
        } catch (\Exception $e) {
            // don't break the creation flow if role check or DB column check fails
        }

        return $this->redirectToIndex()->with('success', 'NQR berhasil dibuat dengan nomor: ' . $nqr->no_reg_nqr);
    }

    public function show(Nqr $nqr)
    {
        $nqr->load(['creator', 'updater']);
        return view('qc.nqr.show', compact('nqr'));
    }

    public function edit(Nqr $nqr)
    {
        // Provide supplier and item masters for dropdowns
        $suppliers = DB::table('por_supplier')->orderBy('por_nama')->get();
        $items = DB::table('por_item')->select('kode', 'description')->orderBy('kode')->get();
        // If current user is Foreman, render the foreman-specific edit view
        try {
            $rawRole = auth()->user()->role ?? '';
            $r = strtolower(preg_replace('/[\s_\-]/', '', $rawRole));
            if (str_contains($r, 'foreman')) {
                return view('foreman.nqr.edit', compact('nqr', 'suppliers', 'items'));
            }
        } catch (\Exception $e) {
            // fallback to QC view if anything goes wrong
        }

        return view('qc.nqr.edit', compact('nqr', 'suppliers', 'items'));
    }

    public function update(Request $request, Nqr $nqr)
    {
        // If nomor_part provided but nama_part is empty, try to fill nama_part from por_item master
        try {
            if ($request->filled('nomor_part') && !$request->filled('nama_part')) {
                $item = DB::table('por_item')->where('kode', $request->input('nomor_part'))->first();
                if ($item && isset($item->description)) {
                    $request->merge(['nama_part' => $item->description]);
                }
            }
        } catch (\Exception $e) {
            // ignore lookup failures; validation will handle missing fields
        }

        $validated = $request->validate([
            'tgl_terbit_nqr' => 'required|date',
            'tgl_delivery' => 'required|date',
            'nama_supplier' => 'required|string|max:255',
            'nama_part' => 'required|string|max:255',
            'nomor_po' => 'required|string|max:255',
            'nomor_part' => 'required|string|max:255',
            'status_nqr' => 'required|in:Claim,Complaint (Informasi)',
            'location_claim_occur' => 'required|in:Receiving Insp,In-Process,Customer',
            'disposition_inventory_location' => 'required|in:At Customer,At PT.KYBI',
            'disposition_inventory_action' => 'required|string',
            'claim_occurence_freq' => 'required|in:First Time,Reoccurred/Routine',
            'disposition_defect_part' => 'required|in:Keep to Use,Return to Supplier,Scrapped at PT.KYBI',
            'invoice' => 'required|string|max:255',
            'total_del' => 'required|string|max:255',
            'total_claim' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'detail_gambar' => 'nullable|string|max:265',
            'disposition_claim' => 'nullable|string',
            'pay_compensation_value' => 'nullable|numeric',
            'pay_compensation_currency' => 'nullable|string|max:10',
            'pay_compensation_currency_symbol' => 'nullable|string|max:10',
            'send_replacement_method' => 'nullable|string|max:50',
        ]);

        $willRemoveOldImage = $request->input('remove_gambar') == '1';
        $hasNewImage = $request->hasFile('gambar');
        $hasExistingImage = !empty($nqr->gambar);

        if ($willRemoveOldImage && !$hasNewImage) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['gambar' => 'Gambar wajib ada. Silakan upload gambar baru sebelum menghapus gambar lama.']);
        }

        if (!$hasExistingImage && !$hasNewImage) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['gambar' => 'Gambar wajib diupload.']);
        }

        $validated['order'] = $validated['nomor_po'];

        if ($willRemoveOldImage && $hasNewImage) {
            if ($nqr->gambar) {
                Storage::disk('public')->delete($nqr->gambar);
            }
        }

        if ($request->hasFile('gambar')) {
            if ($nqr->gambar) {
                Storage::disk('public')->delete($nqr->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('nqr_images', $filename, 'public');
            $validated['gambar'] = $path;
        }

        $validated['updated_by'] = Auth::id();

        $nqr->update($validated);

        return $this->redirectToIndex()->with('success', 'NQR berhasil diupdate: ' . $nqr->no_reg_nqr);
    }

    public function destroy(Nqr $nqr)
    {
        if ($nqr->gambar) {
            Storage::disk('public')->delete($nqr->gambar);
        }

        $nqr->delete();

        return $this->redirectToIndex()->with('success', 'NQR berhasil dihapus');
    }

    /**
     * Decide proper index redirect depending on current user's role.
     */
    protected function redirectToIndex()
    {
        $rawRole = auth()->user()->role ?? '';
        $r = strtolower(preg_replace('/[\s_\-]/', '', $rawRole));

        if (str_contains($r, 'foreman')) {
            return redirect()->route('foreman.nqr.index');
        }

        return redirect()->route('qc.nqr.index');
    }
}
