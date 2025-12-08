<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Persetujuan LPK</title>
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
            background-color: #dc2626;
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

        .status-claim {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .status-complaint {
            background-color: #fffbeb;
            color: #d97706;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 24px;">Permintaan Persetujuan LPK</h1>
    </div>

    <div class="content">
        <p>Yth. <strong><?php echo e($recipientName ?? 'Bapak/Ibu'); ?></strong>,</p>

        <p>Anda menerima permintaan persetujuan untuk Laporan Penyimpangan Kualitas (LPK) berikut:</p>

        <table class="info-table">
            <tr>
                <td>No. Registrasi</td>
                <td><strong><?php echo e($lpk->no_reg); ?></strong></td>
            </tr>
            <tr>
                <td>Tanggal Terbit</td>
                <td><?php echo e($lpk->tgl_terbit ? \Carbon\Carbon::parse($lpk->tgl_terbit)->format('d/m/Y') : '-'); ?></td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td><?php echo e($lpk->nama_supply ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Nama Part</td>
                <td><?php echo e($lpk->nama_part ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Nomor Part</td>
                <td><?php echo e($lpk->nomor_part ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td><?php echo e($lpk->problem ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Status LPK</td>
                <td>
                    <span
                        class="status-badge <?php echo e(strtolower($lpk->status ?? '') === 'claim' ? 'status-claim' : 'status-complaint'); ?>">
                        <?php echo e($lpk->status ?? 'N/A'); ?>

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
        <p>Email ini dikirim secara otomatis oleh Sistem Pelaporan Penyimpangan Kualitas.</p>
        <p>Harap tidak membalas email ini.</p>
    </div>
</body>

</html><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/emails/lpk_approval_requested.blade.php ENDPATH**/ ?>