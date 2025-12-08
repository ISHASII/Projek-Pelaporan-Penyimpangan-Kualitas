<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status NQR Diperbarui</title>
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
            background-color: #16a34a;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .header.pending {
            background-color: #2563eb;
        }

        .header.rejected {
            background-color: #dc2626;
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

        .status-selesai {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .status-menunggu {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-ditolak {
            background-color: #fef2f2;
            color: #dc2626;
        }
    </style>
</head>

<body>
    @php
        $statusLower = strtolower($nqr->status_approval ?? '');
        $isSelesai = str_contains($statusLower, 'selesai');
        $isDitolak = str_contains($statusLower, 'ditolak');
        $headerClass = $isSelesai ? '' : ($isDitolak ? 'rejected' : 'pending');
        $statusClass = $isSelesai ? 'status-selesai' : ($isDitolak ? 'status-ditolak' : 'status-menunggu');
    @endphp

    <div class="header {{ $headerClass }}">
        <h1 style="margin: 0; font-size: 24px;">Status NQR Diperbarui</h1>
    </div>

    <div class="content">
        <p>Yth. <strong>{{ $recipientName ?? 'Bapak/Ibu' }}</strong>,</p>

        <p>Status Non-Conformance Quality Report (NQR) berikut telah diperbarui:</p>

        <table class="info-table">
            <tr>
                <td>No. Registrasi</td>
                <td><strong>{{ $nqr->no_reg_nqr }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal Terbit</td>
                <td>{{ $nqr->tgl_terbit_nqr ? \Carbon\Carbon::parse($nqr->tgl_terbit_nqr)->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td>{{ $nqr->nama_supplier ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama Part</td>
                <td>{{ $nqr->nama_part ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nomor Part</td>
                <td>{{ $nqr->nomor_part ?? '-' }}</td>
            </tr>
            <tr>
                <td>Status Approval</td>
                <td>
                    <span class="status-badge {{ $statusClass }}">
                        {{ $nqr->status_approval ?? 'N/A' }}
                    </span>
                </td>
            </tr>
            @if($action ?? null)
                <tr>
                    <td>Tindakan</td>
                    <td>{{ ucfirst($action) }} oleh {{ $actorName ?? 'System' }}</td>
                </tr>
            @endif
        </table>

        <p>Silakan login ke sistem untuk melihat detail lebih lanjut.</p>

        <p style="margin-top: 20px;">
            Terima kasih atas perhatian dan kerjasamanya.
        </p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Non-Conformance Quality Report.</p>
        <p>Harap tidak membalas email ini.</p>
    </div>
</body>

</html>