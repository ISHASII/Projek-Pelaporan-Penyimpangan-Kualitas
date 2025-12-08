<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Persetujuan NQR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background-color: #f9fafb;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .info-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #6b7280;
        }

        .footer {
            background-color: #f3f4f6;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-nqr {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .approval-step {
            background-color: #fef3c7;
            color: #d97706;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 24px;">Permintaan Persetujuan NQR</h1>
    </div>

    <div class="content">
        <p>Yth. <strong><?php echo e($recipientName ?? 'Bapak/Ibu'); ?></strong>,</p>

        <p>Anda menerima permintaan persetujuan untuk Non-Conformance Quality Report (NQR) berikut:</p>

        <table class="info-table">
            <tr>
                <td>No. Registrasi</td>
                <td><strong><?php echo e($nqr->no_reg_nqr); ?></strong></td>
            </tr>
            <tr>
                <td>Tanggal Terbit</td>
                <td><?php echo e($nqr->tgl_terbit_nqr ? \Carbon\Carbon::parse($nqr->tgl_terbit_nqr)->format('d/m/Y') : '-'); ?></td>
            </tr>
            <tr>
                <td>Tanggal Delivery</td>
                <td><?php echo e($nqr->tgl_delivery ? \Carbon\Carbon::parse($nqr->tgl_delivery)->format('d/m/Y') : '-'); ?></td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td><?php echo e($nqr->nama_supplier ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Nama Part</td>
                <td><?php echo e($nqr->nama_part ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Nomor Part</td>
                <td><?php echo e($nqr->nomor_part ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Nomor PO</td>
                <td><?php echo e($nqr->nomor_po ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Total Claim</td>
                <td><?php echo e($nqr->total_claim ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Status NQR</td>
                <td>
                    <span class="status-badge status-nqr">
                        <?php echo e($nqr->status_nqr ?? 'N/A'); ?>

                    </span>
                </td>
            </tr>
            <tr>
                <td>Status Approval</td>
                <td>
                    <span class="status-badge approval-step">
                        <?php echo e($nqr->status_approval ?? 'Pending'); ?>

                    </span>
                </td>
            </tr>
        </table>

        <p>Silakan login ke sistem untuk melakukan review dan persetujuan.</p>

        <p style="margin-top: 20px;">
            Terima kasih atas perhatian dan kerjasamanya.
        </p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Non-Conformance Quality Report.</p>
        <p>Harap tidak membalas email ini.</p>
    </div>
</body>

</html><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/emails/nqr_approval_requested.blade.php ENDPATH**/ ?>