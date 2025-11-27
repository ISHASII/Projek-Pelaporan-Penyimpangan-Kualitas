<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penyimpangan Kualitas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 7px;
            margin: 5px;
            padding: 0;
            line-height: 1.0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0;
            border-spacing: 0;
        }

        td,
        th {
            border-width: 0.5px !important;
            border-style: solid !important;
            border-color: #000 !important;
            padding: 1px 1px;
            vertical-align: top;
            font-size: 7px;
            height: auto;
            box-shadow: none !important;
        }

        .noborder,
        .noborder td,
        .noborder th {
            border: none !important;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 7px;
        }

        .tiny {
            font-size: 6px;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        /* Force uniform table borders: make table cells 1px and add inner hairline for a double-line look */
        table td,
        table th {
            border: 1px solid #000 !important;
            box-shadow: inset 0 0 0 1px #000;
        }

        /* Remove gaps between tables */
        .header-table {
            margin-bottom: 0 !important;
        }

        /* Ensure header shows a single thin line: remove inset hairline from header cells and use a thin border */
        .header-table,
        .header-table tr,
        .header-table td,
        .header-table th {
            border-bottom: 0.6px solid rgba(0, 0, 0, 0.95) !important;
            /* very thin line */
        }

        .header-table td,
        .header-table th {
            box-shadow: none !important;
            /* remove inset 1px hairline from header cells */
        }

        /* Prevent the following table from re-drawing a top border that would duplicate the line */
        .header-table+table {
            border-top: none !important;
        }

        table+table {
            margin-top: -1px !important;
            border-top: none !important;
        }

        /* Special case: hide the seam under the right meta box only */
        .header-table td.meta-cell {
            border-bottom: none !important;
        }

        .header-table+table tr:first-child td:last-child {
            border-top: none !important;
            box-shadow: none !important;
        }

        /* Remove gap between header table and subsequent tables */
        .header-table+table {
            margin-top: -1px !important;
            border-top: none !important;
        }

        .header-table+table tr:first-child td {
            border-top: 1px solid #000 !important;
        }

        /* Ensure all consecutive tables are connected without gaps */
        table:not(.header-table):not(.meta-table):not(.noborder)+table:not(.noborder) {
            margin-top: -1px !important;
        }

        table:last-child {
            /* no-op for last table */
        }

        table tr:last-child td {
            /* if the next sibling is a table, suppress this bottom border via adjacent selector */
        }

        /* Header styles */
        .header-title {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            padding: 3px;
        }

        .logo-section {
            width: 180px;
            text-align: left;
            vertical-align: middle;
            /* center vertically so logo stays visible */
            padding: 0 !important;
        }

        /* Hilangkan hanya garis internal di area logo, pertahankan border cell utama */
        .inner-logo,
        .inner-logo *,
        .inner-logo td,
        .inner-logo tr {
            border: none !important;
            border-top: none !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            box-shadow: none !important;
        }

        /* Ensure logo scales to fit the shorter header */
        .inner-logo img {
            max-height: 60px;
            width: auto;
            display: block;
        }

        .info-section {
            width: 60px;
            font-size: 7px;
            text-align: left;
        }

        /* Image and status section - fixed exact width */
        .image-cell {
            width: 180px;
            height: <?php echo e($imageHeight ?? 140); ?>px;
            text-align: center;
            vertical-align: top;
            padding: 2px;
            box-sizing: border-box;
        }

        .part-image {
            max-width: 160px;
            max-height: <?php echo e($imageHeight ?? 100); ?>px;
            border: 2px solid #000;
        }

        /* Ensure all table cells have consistent box-sizing */
        td {
            box-sizing: border-box;
        }

        /* Checkbox styles */
        .checkbox {
            font-size: 10px;
        }

        /* Clean option marker that looks like the sample image: circle for off, check for on */
        .opt-marker {
            display: inline-block;
            width: 10px;
            text-align: center;
            margin-right: 4px;
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        .opt-marker.off:before {
            content: '◯';
        }

        .opt-marker.on:before {
            content: '✔';
        }

        .option-line {
            line-height: 1.25;
            margin: 0 0 2px 0;
        }

        /* make sure the entire claim cell uses unicode-capable font */
        .claim-cell {
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        /* Status box / claim column tweaks to match visual proportions */
        .claim-cell {
            width: 70px;
            padding: 4px 6px !important;
            font-size: 8px;
        }

        .status-combined {
            width: 240px;
            padding: 4px !important;
            vertical-align: top;
        }

        .status-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .status-grid td {
            border: none !important;
            box-shadow: none !important;
            padding: 3px 6px;
            font-size: 8px;
            vertical-align: top;
        }

        /* Taller cells for Lokasi Penemuan Claim and Status Repair */
        .tall-header {
            height: 18px;
        }

        .tall-content {
            height: 50px;
            vertical-align: top;
            padding-top: 6px;
        }

        /* Narrow right column helper (for Perlakuan Terhadap Claim / Part Defect column) */
        .right-narrow {
            width: 100px !important;
            min-width: 100px !important;
            max-width: 100px !important;
            box-sizing: border-box;
        }

        /* Status Repair header: full-width underline that touches left/right cell borders */
        .status-repair-header {
            position: relative;
            padding-bottom: 8px;
            padding-left: 0;
            padding-right: 0;
        }

        .status-repair-header:after {
            content: '';
            position: absolute;
            left: 0;
            /* start at very left of cell */
            right: 0;
            /* end at very right of cell */
            bottom: 1px;
            /* sit close to the bottom to align with border */
            height: 1px;
            /* thin underline */
            background: #000;
            box-shadow: none !important;
            z-index: 2;
        }

        /* small left-aligned underline for compact subheaders */
        .subheader-underline {
            position: relative;
            display: inline-block;
            padding-bottom: 2px;
        }

        .subheader-underline:after {
            content: '';
            position: absolute;
            left: 0;
            top: 100%;
            width: 22%;
            height: 1px;
            background: #000;
        }

        /* Statistics section */
        .stats-cell {
            text-align: center;
            font-weight: bold;
            font-size: 7px;
            height: 25px;
        }

        /* Analysis section */
        .analysis-header {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
            padding: 2px;
        }

        /* Reusable section background used in 'Identifikasi Masalah' - apply to other boxes */
        .section-bg {
            background-color: #f0f0f0;
        }

        /* Inner small info table in header (No Reg / Tgl Terbit / Reciv No SPB) */
        .meta-table {
            width: 100%;
            border: none;
            /* use separate so vertical borders inside outer cell are preserved */
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
            padding: 0;
            font-size: 9px;
        }

        /* remove inner cell borders to avoid doubling with outer cell borders; draw only horizontal separators */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            /* outer box */
            font-size: 9px;
        }

        .meta-table td {
            border: none !important;
            box-shadow: none !important;
            padding: 4px 6px;
            vertical-align: middle;
        }

        .meta-table tr+tr td {
            border-top: 1px solid #000 !important;
        }

        .meta-table {
            border-left: none !important;
            border-top: none !important;
            border-right: 1px solid #000 !important;
            border-bottom: none !important;
        }

        .meta-cell {
            border: none !important;
            padding: 0 !important;
        }

        .meta-table td {
            padding: 6px 6px !important;
            min-height: 14px;
            box-shadow: none !important;
            border: none !important;
            vertical-align: middle;
        }

        /* Center the two meta rows as a group */
        /* Balanced paddings so the two rows sit centered nicely */
        .meta-table tr:first-child td {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }

        .meta-table tr:nth-child(2) td {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }

        /* Redraw horizontal separators between rows */
        .meta-table tr+tr td {
            border-top: 1px solid #000 !important;
        }

        /* No bottom border on the last row (Tgl Terbit) */
        .meta-table tr:last-child td {
            border-bottom: none !important;
        }

        .meta-cell {
            position: relative;
            padding-top: 0 !important;
        }

        /* Top border drawn by pseudo-element to avoid rendering gaps */
        .meta-cell:before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: -1px;
            height: 1px;
            background: #000;
            display: block;
            box-shadow: none !important;
            z-index: 2;
        }

        .meta-table {
            margin-top: 0;
        }

        .meta-cell:after {
            content: '';
            position: absolute;
            right: 0;
            top: -1px;
            bottom: 0;
            width: 1px;
            background: #000;
            display: block;
            box-shadow: none !important;
            z-index: 2;
        }

        /* Fishbone diagram */
        .fishbone-cell {
            width: 280px;
            height: 80px;
            text-align: center;
            vertical-align: middle;
        }

        /* Supplier/Part info row: fix layout so boxes stay compact */
        .supplier-part-table {
            table-layout: fixed;
            width: 100%;
        }

        .supplier-part-table td {
            vertical-align: top;
        }

        .supplier-cell {
            width: 28%;
        }

        .part-cell {
            width: 22%;
        }

        .no-cell {
            width: 15%;
        }

        .rev-cell {
            width: 6%;
            text-align: center;
        }

        .revision-cell {
            width: 14%;
        }

        .labels-cell {
            width: 7%;
        }

        .values-cell {
            width: 8%;
        }

        /* Make long names wrap and slightly smaller */
        .shrink-text {
            font-size: 7px;
            line-height: 1.05;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

            <?php
                $pdef = trim($lpk->perlakuan_part_defect ?? '');
            ?>
            <div class="inner-list" style="padding:6px; line-height:1.1; font-size:8px;">
                <div class="option-line" style="margin-bottom:2px;"><span class="opt-marker <?php echo e($pdef==='Direpair Supplier' ? 'on' : 'off'); ?>"></span>Direpair Supplier</div>
                <div class="option-line" style="margin-bottom:2px;"><span class="opt-marker <?php echo e($pdef==='Replace' ? 'on' : 'off'); ?>"></span>Replace</div>
                <div class="option-line" style="margin-bottom:2px;"><span class="opt-marker <?php echo e($pdef==='Dikembalikan ke Supplier' ? 'on' : 'off'); ?>"></span>Dikembalikan ke Supplier</div>
                <div class="option-line" style="margin-top:2px;">
                    <span class="opt-marker <?php echo e(($pdef==='Discrap di PT KYBI' || $pdef==='Discrap PT KYBI' || stripos($pdef,'scrap')!==false || stripos($pdef,'serap')!==false) ? 'on' : 'off'); ?>"></span>
                    Discrap di PT KYBI
                </div>
            </div>

        .signature-header:after {
            display: none;
        }

        /* Naikkan spesifisitas agar menimpa aturan global 'table td' yang memberi border bawah */
        table td.signature-header {
            box-shadow: none !important;
        }

        /* ===== Bottom Summary + Signatures (New) ===== */
        .bottom-summary-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
            border: 0.5px solid #000;
        }

        .bottom-summary-table td {
            border: 0.5px solid #000;
            padding: 2px 4px;
            font-size: 8px;
            box-sizing: border-box;
            line-height: 1.05;
            vertical-align: top;
        }

        /* Container for totals and signatures */
        .bottom-summary-table .totals-section {
            width: 15%;
            padding: 0 !important;
            vertical-align: top;
        }

        .bottom-summary-table .signatures-section {
            width: 85%;
            padding: 0 !important;
            vertical-align: top;
        }

        /* Adjusted width per request (lebih kecil) */
        .totals-block {
            width: 110px;
            padding: 0 !important;
        }

        .totals-flex-wrapper {
            display: flex;
            flex-direction: column;
            height: 80px;
        }

        .totals-inner {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .totals-inner td {
            border: 0.5px solid #000;
            padding: 2px 4px;
            font-size: 8px;
        }

        .totals-inner td.label {
            font-weight: bold;
        }

        .totals-inner td.value {
            text-align: right;
        }

        .percentage-label {
            font-weight: bold;
        }

        .percentage-value {
            text-align: right;
            font-weight: bold;
            white-space: nowrap;
        }

        .totals-spacer {
            flex: 1 1 auto;
            border: 0.5px solid #000;
            border-top: none;
        }

        /* Shorter header height */
        .sig-header {
            font-style: italic;
            text-align: center;
            height: 16px;
            padding: 2px 4px;
            font-size: 8px;
            background-color: #f0f0f0;
            font-weight: bold;
            vertical-align: middle;
            border: 0.1px solid #000 !important;
        }

        /* Shorter signature box height */
        .sig-box {
            height: 33px;
            padding: 2px;
            text-align: center;
            vertical-align: bottom;
            border: 0.1px solid #000 !important;
            border-top: none !important;
        }

        /* Make Dept Head box shorter only for this role */
        .sig-box.dept-head-box {
            height: 22px;
            min-height: 22px;
            max-height: 22px;
            border: 0.1px solid #000 !important;
            border-top: none !important;
        }

        /* Ensure consistent border with totals section */
        .totals-section .totals-inner td {
            border-width: 0.5px !important;
            border-style: solid !important;
            border-color: #000 !important;
        }



        /* === Totals block refinement === */
        .totals-cell {
            width: 180px;
            padding: 0 !important;
            border: 0.5px solid #000 !important;
            box-sizing: border-box;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .totals-table tr {
            height: 16px;
        }

        .totals-table td {
            border: 0.5px solid #000;
            font-size: 8px;
            padding: 2px 4px;
            box-shadow: none !important;
        }

        .totals-table td.label {
            font-weight: bold;
        }

        .totals-table td.value {
            text-align: right;
        }

        .totals-table td.border-left {
            border-left: 0.5px solid #000;
        }

        .totals-table tr:last-child td {
            /* ensure bottom border aligns with inner line */
        }

        /* Final override: remove any inner divider lines inside the right meta box */
        .meta-table tr+tr td {
            border-top: none !important;
            box-shadow: none !important;
        }

        /* Nudge percentage value text slightly downward for visual alignment */
        .totals-inner tr:last-child td.percentage-value {
            padding-top: 4px;
        }

        .meta-table tr:first-child td {
            border-bottom: none !important;
            box-shadow: none !important;
        }

        /* Beat overly-broad header-table bottom borders on nested rows/cells */
        .header-table .meta-table tr {
            border: none !important;
        }

        .header-table .meta-table td {
            border: none !important;
            box-shadow: none !important;
        }

        /* Add back a single strong underline under 'No Reg' spanning full width */
        .header-table .meta-table tr:first-child td {
            border-bottom: 1px solid #000 !important;
            box-shadow: none !important;
        }

        /* PERBAIKAN: Optimasi tinggi agar muat 1 halaman meskipun diisi penuh */
        .text-content-limited {
            min-height: 38px;
            max-height: 38px;
            overflow: hidden;
            position: relative;
        }

        /* Hapus gradient fade-out khusus untuk problem agar tidak terlihat terpotong */
        .text-content-limited.problem-text::after {
            display: none;
        }

        /* Gradient fade-out hanya untuk detail_gambar */
        .text-content-limited:not(.problem-text)::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(transparent, white);
        }

        .text-content-limited.problem-text {
            min-height: 44px;
            max-height: 44px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr valign="middle">
            <td rowspan="1" class="logo-section" valign="middle">
                
                <table class="noborder inner-logo"
                    style="width:auto; margin:0 auto; padding:0; border:none !important; border-collapse:separate;">
                    <tr style="border:none !important;">
                        <td valign="middle"
                            style="border:none !important; vertical-align:middle; width:60px; padding:0; box-shadow:none !important;">

                            <?php if(file_exists(public_path('image/kayaba.png'))): ?>
                                <img src="<?php echo e(public_path('image/kayaba.png')); ?>"
                                    style="border:none; margin:0; padding:0;">
                            <?php endif; ?>
                        </td>
                        <td valign="middle"
                            style="border:none !important; vertical-align:middle; padding:0 2px 0 0; font-size:8px; text-align:left; box-shadow:none !important;">
                            PT KAYABA INDONESIA<br>
                            Vendor Development Dept.
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="5" class="header-title header-title-cell"
                style="text-align: center; height: 30px; vertical-align: middle; padding: 0;">LAPORAN PENYIMPANGAN
                KUALITAS</td>
            <td class="small meta-cell" valign="middle"
                style="width:150px; padding:6px 0; border-bottom:none !important; vertical-align: middle !important;">
                <table class="meta-table">
                    <tr>
                        <td style="width:55%;">No Reg</td>
                        <td style="width:45%; text-align:right;"><?php echo e($lpk->no_reg ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td>Tgl Terbit</td>
                        <td style="text-align:right;">
                            <?php echo e($lpk->tgl_terbit ? \Carbon\Carbon::parse($lpk->tgl_terbit)->format('d-m-Y') : ''); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- Supplier and Part Info -->
    <table class="supplier-part-table" style="margin-top: -1px;">
        <tr>
            <td class="small supplier-cell">
                Nama (Supplier)<br>
                <span class="bold shrink-text"><?php echo e($lpk->nama_supply ?? ''); ?></span>
            </td>
            <td class="small part-cell">
                Nama Part<br>
                <span class="bold"><?php echo e($lpk->nama_part ?? ''); ?></span>
            </td>
            <td class="small no-cell">
                No Part<br>
                <span class="bold"><?php echo e($lpk->nomor_part ?? ''); ?></span>
            </td>
            <td class="small center rev-cell">Rev<br><span class="bold"><?php echo e($lpk->rev ?? ''); ?></span></td>
            <td class="small revision-cell">Revision Item<br><span class="bold"><?php echo e($lpk->revision_item ?? ''); ?></span>
            </td>
            <td class="small labels-cell">
                Date<br>
                Nomor PO<br>
                Tgl Delivery
            </td>
            <td class="small values-cell">
                <br>
                <?php echo e($lpk->nomor_po ?? ''); ?><br>
                <?php echo e($lpk->tgl_delivery ? \Carbon\Carbon::parse($lpk->tgl_delivery)->format('d-m-Y') : ''); ?>

            </td>
        </tr>
    </table>
    <!-- Main Content: Image and Status Section -->
    <table class="info-table" style="margin-top: -1px;">
        <tr>
            <!-- Left: Image Section (spans 5 rows) -->
            <td rowspan="5" class="image-cell">
                <?php if($lpk->gambar && file_exists(public_path('storage/' . ltrim($lpk->gambar, 'storage/')))): ?>
                    <img src="<?php echo e(public_path('storage/' . ltrim($lpk->gambar, 'storage/'))); ?>" class="part-image">
                <?php else: ?>
                    <div
                        style="width:160px; height:<?php echo e($imageHeight ?? 120); ?>px; border:2px solid #000; display:flex; align-items:center; justify-content:center; background:#f8f8f8;">
                        <span class="tiny">NG</span>
                    </div>
                <?php endif; ?>
                <br>
                <!-- PERBAIKAN: Ukuran dioptimalkan agar tetap 1 halaman meskipun isi penuh -->
                <div class="bold text-content-limited" style="font-weight:bold; word-break:break-word; white-space:pre-line; padding-top:5px; text-align:left; min-height:43px; max-height:43px;"><?php echo e($lpk->detail_gambar ?? ''); ?></div>
                <div class="text-content-limited problem-text" style="font-weight:normal; word-break:break-word; white-space:pre-line; padding-top:5px; text-align:left; min-height:43px; max-height:43px; overflow:hidden;"><?php echo e($lpk->problem ?? ''); ?></div>
            </td>

            <!-- Row 1: Status options driven by dropdown 'status' -->
            <td rowspan="2" class="small checkbox claim-cell">
                <?php
                    $statusVal = strtolower(trim($lpk->status ?? ''));
                    // Normalize some common variants
                    $isClaimSel = strpos($statusVal, 'claim') !== false && strpos($statusVal, 'complaint') === false;
                    $isComplaintSel =
                        strpos($statusVal, 'complaint') !== false || strpos($statusVal, 'informasi') !== false;
                ?>
                <div class="option-line"><span class="opt-marker <?php echo e($isClaimSel ? 'on' : 'off'); ?>"></span>Claim</div>
                <div class="option-line"><span class="opt-marker <?php echo e($isComplaintSel ? 'on' : 'off'); ?>"></span>Complaint
                    (Informasi)</div>
            </td>
            <!-- Combined box: replace two separate cells (and the two below) with one outer cell -->
            <td colspan="2" rowspan="2" class="small checkbox status-combined" style="padding:0;">
                <?php
                    $kat = strtolower(trim($lpk->kategori ?? ''));
                    $selQty = strpos($kat, 'qty kurang') !== false || strpos($kat, 'kurang') !== false;
                    $selSubcont = strpos($kat, 'subcont') !== false;
                    $selRepair = strpos($kat, 'part repair') !== false || strpos($kat, 'repair') !== false;
                    $selReject = strpos($kat, 'reject process') !== false || strpos($kat, 'reject') !== false;
                    $selWrong = strpos($kat, 'salah barang') !== false || strpos($kat, 'label') !== false; // Salah Barang/Label
                ?>
                <!-- inner borderless table preserves original 2x2 visual layout but removes the middle vertical border -->
                <div style="position:relative; width:100%; height:0;">
                    <div style="position:absolute; left:62%; top:-5px; height:90px; border-left:1px solid #000; z-index:10;"></div>
                </div>
                <table class="noborder status-grid" style="width:100%;">
                    <tr>
                        <td style="text-align:left; width:32%; border-right:2px solid #000;"><span class="opt-marker <?php echo e($selQty ? 'on' : 'off'); ?>"></span>QTY Kurang</td>
                        <td style="text-align:left; width:32%; border-right:2px solid #000;"><span class="opt-marker <?php echo e($selRepair ? 'on' : 'off'); ?>"></span>Part Repair</td>
                        <td style="text-align:left; width:36%;"><span class="opt-marker <?php echo e(($lpk->jenis_ng ?? '') == 'Quality' ? 'on' : 'off'); ?>"></span>Quality</td>
                    </tr>
                    <tr>
                        <td style="text-align:left; width:32%; border-right:2px solid #000;"><span class="opt-marker <?php echo e($selSubcont ? 'on' : 'off'); ?>"></span>Subcont Prod</td>
                        <td style="text-align:left; width:32%; border-right:2px solid #000;"><span class="opt-marker <?php echo e($selReject ? 'on' : 'off'); ?>"></span>Reject Process</td>
                        <td style="text-align:left; width:36%;"><span class="opt-marker <?php echo e(($lpk->jenis_ng ?? '') == 'Delivery' ? 'on' : 'off'); ?>"></span>Delivery</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:left; border-right:2px solid #000;"><span class="opt-marker <?php echo e($selWrong ? 'on' : 'off'); ?>"></span>Salah Barang/Label</td>
                        <td></td>
                    </tr>
                </table>
            </td>
            <td rowspan="5" class="small center right-narrow" style="width:45%;">
                <div>
                    <div class="section-bg" style="padding:40px 20px; box-sizing:border-box;">
                        <div style="display:block; height:6px;"></div>
                        <div class="bold" style="font-size:12px; text-align:center;">Perlakuan</div>
                        <div class="bold" style="font-size:12px; text-align:center; margin-bottom:6px;">Terhadap Part
                        </div>
                    </div>
                    <!-- underline konsisten -->
                    <div
                        style="position:relative; left:-3px; width:calc(100% + 6px); height:1px; background:#000; margin-top:4.3px; margin-bottom:6px; box-shadow:none; display:block; z-index:4; box-sizing:border-box;">
                    </div>
                </div>
                <div class="tiny" style="margin-top: 2px;">
                    <?php
                        $ppcPartOptions = [
                            'Sortir oleh Supplier',
                            'Sortir Oleh PT KYBI',
                            'Part Tetap Dipakai',
                        ];
                        $ppcPartValue = $lpk->ppc_perlakuan_terhadap_part ?? '';
                    ?>
                    <?php
                        // Atur alignment dan padding antar value di sini
                        $ppcPartAlign = 'left'; // 'left', 'center', 'right'
                        $ppcPartPadding = '6px'; // padding antar value
                    ?>
                    <div style="display: flex; flex-direction: column; gap: <?php echo e($ppcPartPadding); ?>; align-items: <?php echo e($ppcPartAlign); ?>;">
                        <?php $__currentLoopData = $ppcPartOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div style="display: flex; align-items: center; min-height:13px; text-align:<?php echo e($ppcPartAlign); ?>;">
                                <?php
                                    $ppcPartValNorm = strtolower(trim($ppcPartValue));
                                    $optNorm = strtolower(trim($opt));
                                ?>
                                <span class="opt-marker <?php echo e($ppcPartValNorm === $optNorm ? 'on' : 'off'); ?>" style="width:5px;height:5px;margin-right:6px;display:inline-block;vertical-align:middle;position:relative;top:-1px;"></span>
                                <span style="font-size:8px;position:relative;top:-1px;"><?php echo e($opt); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </td>

            <td rowspan="5" class="small center right-narrow" style="width:45%;">
                <div>
                    <div class="section-bg" style="padding:40px 20px; box-sizing:border-box;">
                        <div style="display:block; height:6px;"></div>
                        <div class="bold" style="font-size:12px; text-align:center;">Perlakuan</div>
                        <div class="bold" style="font-size:12px; text-align:center; margin-bottom:6px;">Terhadap Claim
                        </div>
                    </div>
                    <!-- add underline to align with Perlakuan Terhadap Part (tuned) - extend into padding so it touches borders -->
                    <div
                        style="position:relative; left:-3px; width:calc(100% + 6px); height:1px; background:#000; margin-top:4.3px; margin-bottom:6px; box-shadow:none; display:block; z-index:4; box-sizing:border-box;">
                    </div>
                </div>
                <div class="tiny" style="margin-top: 10px;">
                    <?php
                        $ppcClaimOptions = [
                            'pemotongan pembayaran',
                            'kirim pengganti',
                        ];
                        $ppcClaimValue = $lpk->ppc_perlakuan_terhadap_claim ?? '';
                    ?>
                    <?php
                        // Atur alignment dan padding antar value di sini
                        $ppcClaimAlign = 'left'; // 'left', 'center', 'right'
                        $ppcClaimPadding = '6px'; // padding antar value
                    ?>
                    <div style="display: flex; flex-direction: column; gap: <?php echo e($ppcClaimPadding); ?>; align-items: <?php echo e($ppcClaimAlign); ?>;">
                            <?php $__currentLoopData = $ppcClaimOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="display: flex; align-items: center; min-height:13px; text-align:<?php echo e($ppcClaimAlign); ?>;<?php echo e($i > 0 ? 'padding-top:7px;' : ''); ?>">
                                <?php
                                    $ppcClaimValNorm = strtolower(trim($ppcClaimValue));
                                    $optClaimNorm = strtolower(trim($opt));
                                ?>
                                <span class="opt-marker <?php echo e($ppcClaimValNorm === $optClaimNorm ? 'on' : 'off'); ?>" style="width:5px;height:5px;margin-right:6px;display:inline-block;vertical-align:middle;position:relative;top:-1px;"></span>
                                <span style="font-size:8px;position:relative;top:-1px;"><?php echo e($opt); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <!-- Row 2: (cells merged into the combined box above) -->
        </tr>
        <tr>
            <!-- Row 3: Lokasi Penemuan Claim -->
            <td class="small bold center tall-header section-bg"
                style="width:35%; padding-bottom:18px; vertical-align:bottom; border-bottom:1px solid #000;">
                Lokasi<br>Penemuan Claim</td>
            <td class="small bold center section-bg"
                style="width:20%; padding-bottom:18px; vertical-align:bottom; border-bottom:1px solid #000;">
                <div style="display:block; line-height:1.05;">Perlakuan<br>Terhadap Part</div>
            </td>
            <td class="small bold center section-bg"
                style="width:45%; padding-bottom:18px; vertical-align:bottom; border-bottom:1px solid #000;">
                Frekuensi<br>Claim</td>
        </tr>
        <tr>
            <!-- Row 4: Details -->
            <td class="small tall-content" style="width:35%;">
                <?php
                    // Prefer canonicalized lokasi from model accessor; fallback to robust detection
                    $canon = $lpk->lokasi_canonical ?? null;
                    $lok = strtolower(trim($lpk->lokasi_penemuan_claim ?? ''));
                    $lokNorm = preg_replace('/[\s\-\x{2010}-\x{2015}_]+/u', '', $lok);
                    $selReceiving = $canon === 'receiving' || strpos($lok, 'receiving') !== false;
                    $selInProcess =
                        $canon === 'in-process' ||
                        strpos($lok, 'in-process') !== false ||
                        strpos($lok, 'in process') !== false ||
                        strpos($lok, 'in proses') !== false || // Indonesian
                        strpos($lokNorm, 'inprocess') !== false ||
                        strpos($lokNorm, 'inproses') !== false;
                    $selCustomer = $canon === 'customer' || strpos($lok, 'customer') !== false;
                ?>
                <div class="option-line"><span class="opt-marker <?php echo e($selReceiving ? 'on' : 'off'); ?>"></span>Receiving
                    Insp</div>
                <div class="option-line"><span
                        class="opt-marker <?php echo e($selInProcess ? 'on' : 'off'); ?>"></span>In-Process</div>
                <div class="option-line"><span class="opt-marker <?php echo e($selCustomer ? 'on' : 'off'); ?>"></span>Customer
                    PT</div>
                <?php if($selCustomer && !empty($lpk->customer_pt_name)): ?>
                    <div style="margin-left: 12px; margin-top: 1px; font-size: 6px; color: #333;">
                        <?php echo e($lpk->customer_pt_name); ?>

                    </div>
                <?php endif; ?>
            </td>
            <td class="small tall-content" style="width:20%; border-bottom: none !important;">
                <?php
                    $ptpCanon = $lpk->perlakuan_terhadap_part_canonical ?? null;
                    $rawPtp = $lpk->perlakuan_terhadap_part ?? '';
                    $isCust = $ptpCanon === 'customer' || stripos($rawPtp, 'customer') !== false;
                    $isSupp = $ptpCanon === 'supplier' || stripos($rawPtp, 'supplier') !== false;
                    $isKybi = $ptpCanon === 'kybi' || stripos($rawPtp, 'kybi') !== false;
                    $isTetap =
                        $ptpCanon === 'tetap' ||
                        stripos($rawPtp, 'tetap') !== false ||
                        stripos($rawPtp, 'dipakai') !== false;
                ?>
                <div class="option-line"><span class="opt-marker <?php echo e($isCust ? 'on' : 'off'); ?>"></span>Sortir Oleh
                    Customer</div>
                <div class="option-line"><span class="opt-marker <?php echo e($isSupp ? 'on' : 'off'); ?>"></span>Sortir Oleh
                    Supplier
                </div>
                <div class="option-line"><span class="opt-marker <?php echo e($isKybi ? 'on' : 'off'); ?>"></span>Sortir PT KYBI
                </div>
                <div class="option-line" style="margin-bottom: 0;"><span class="opt-marker <?php echo e($isTetap ? 'on' : 'off'); ?>"></span>Part Tetap
                    Dipakai</div>
            </td>
            <td class="small tall-content" style="width:45%;">
                <?php
                    $fqCanon = $lpk->frekuensi_claim_canonical ?? null;
                    $rawFq = $lpk->frekuensi_claim ?? '';
                    $isPertama =
                        $fqCanon === 'pertama' ||
                        stripos($rawFq, 'pertama') !== false ||
                        stripos($rawFq, 'sekali') !== false;
                    $isBerulang =
                        $fqCanon === 'berulang' ||
                        stripos($rawFq, 'berulang') !== false ||
                        stripos($rawFq, 'rutin') !== false ||
                        stripos($rawFq, 'berkala') !== false;
                ?>
                <div style="margin-left: 1px;">
                    <div class="option-line"><span class="opt-marker <?php echo e($isPertama ? 'on' : 'off'); ?>"></span>Pertama
                        Kali</div>
                    <div class="option-line"><span
                            class="opt-marker <?php echo e($isBerulang ? 'on' : 'off'); ?>"></span>Berulang/Rutin</div>
                </div>
            </td>
        </tr>
    <!-- Row 5: Status Part Claim -->
        <td class="small bold center tall-header" style="width:35%; padding:0;">
            <!-- Bagian header abu-abu -->
            <div style="background:#f0f0f0; padding:8px 6px; box-sizing:border-box;">
                <div style="text-align:center;">Status Part Claim</div>
            </div>
            <!-- Garis bawah full nempel -->
            <div style="width:100%; height:1px; background:#000; margin:0;"></div>
            <!-- Isi konten Status Part Claim (berdasarkan controller/blade) -->
            <?php
                $srRaw = strtolower(trim($lpk->status_repair ?? ''));
                $srBisa = strpos($srRaw, 'bisa') !== false;
                $srTidak = strpos($srRaw, 'tidak') !== false;
            ?>
            <div class="inner-list"
                style="padding:6px 6px 4px 6px; line-height:1.1; font-size:8px; text-align:left; font-weight: normal;">
                <div class="option-line" style="margin-bottom:3px;"><span
                        class="opt-marker <?php echo e($srBisa ? 'on' : 'off'); ?>"></span>Bisa Repair</div>
                <div class="option-line"><span class="opt-marker <?php echo e($srTidak ? 'on' : 'off'); ?>"></span>Tidak Repair
                </div>
            </div>
        </td>

        <td class="small" style="width:20%; border-top: none !important;"></td>

        <td class="small tall-content left" style="width:45%; padding:0; border-top:none !important;">

            <!-- Header abu-abu -->
            <div style="background:#f0f0f0; padding:6px; box-sizing:border-box;">
                <div style="font-size:7px; text-align:center; font-weight:bold;">
                    Perlakuan Part Defect
                </div>
            </div>

            <!-- Garis bawah full -->
            <div style="width:100%; height:1px; background:#000; margin:0;"></div>

            <!-- Isi konten putih: driven by controller value and Blade options -->
            <?php
                $pdef = trim($lpk->perlakuan_part_defect ?? '');
            ?>

            <div class="inner-list" style="padding:6px; line-height:1.1; font-size:8px;">
                <div class="option-line" style="margin-bottom:2px;"><span
                        class="opt-marker <?php echo e($pdef === 'Direpair Supplier' ? 'on' : 'off'); ?>"></span>Direpair Supplier
                </div>
                <div class="option-line" style="margin-bottom:2px;"><span
                        class="opt-marker <?php echo e($pdef === 'Replace' ? 'on' : 'off'); ?>"></span>Replace</div>
                <div class="option-line" style="margin-bottom:2px;"><span
                        class="opt-marker <?php echo e($pdef === 'Dikembalikan ke Supplier' ? 'on' : 'off'); ?>"></span>Dikembalikan
                        ke Supplier
                </div>
                <div class="option-line" style="margin-top:2px;"><span
                        class="opt-marker <?php echo e(($pdef === 'Discrap di PT KYBI' || $pdef === 'Discrap PT KYBI' || stripos($pdef,'scrap')!==false || stripos($pdef,'discrap')!==false || stripos($pdef,'serap')!==false) ? 'on' : 'off'); ?>"></span>Discrap di PT KYBI</div>
                </div>
        </td>
    </table>

    <!-- Totals + Signature Section (full width) -->
    <?php
        $totalCheck    = $lpk->total_check ?? null;
        $totalDelivery = $lpk->total_delivery ?? null;
        $totalNg       = $lpk->total_ng ?? null;
        $totalClaim    = $lpk->total_claim ?? null;
            $percentage    = $lpk->percentage ?? null;
            if ($percentage === null && is_numeric($totalCheck) && $totalCheck > 0 && is_numeric($totalNg)) {
                $percentage = round(($totalNg / $totalCheck) * 100, 2);
            }
    ?>

    <!-- Container table for both sections -->
    <table class="bottom-summary-table" style="margin-top:-1px; width:100%;">
        <tr>
            <td class="totals-section" style="width:26.3%;">
                <table class="totals-inner" style="width:100%; border-collapse:collapse; table-layout:fixed;">
                    <tr>
                        <td class="label" style="width:70px;">TOTAL CHECK</td>
                        <td class="value" style="width:25px;"><?php echo e($totalCheck ?? ''); ?></td>
                        <td class="label" style="width:40px;">TOTAL DELIVERY</td>
                        <td class="value"><?php echo e($totalDelivery ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="label" style="width:40px;">TOTAL NG</td>
                        <td class="value" style="width:25px;"><?php echo e($totalNg ?? ''); ?></td>
                        <td class="label" style="width:40px;">TOTAL CLAIM</td>
                        <td class="value"><?php echo e($totalClaim ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="percentage-label" colspan="3">PROCENTAGE</td>
                        <td class="percentage-value"><?php echo e($percentage !== null ? $percentage . ' %' : ''); ?></td>
                    </tr>
                </table>
            </td>

            <!-- Right section: Signature tables (85% width) -->
            <td class="signatures-section" style="width:85%; padding:0;">
                <!-- Horizontal container using table layout for better PDF compatibility -->
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <!-- Dept Head Table Cell -->
                        <td style="width:9.6%; min-width:80px; max-width:80px; vertical-align:top; padding:0;">
                            <table style="width:100%; border-collapse:collapse;">
                                <tr>
                                    <td class="sig-header" style="height:16px; font-style:italic; text-align:center; padding:2px 4px; font-size:8px; background-color:#f0f0f0; font-weight:bold; vertical-align:middle; border:0.5px solid #000;">
                                        Dept Head
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sig-box dept-head-box" style="height:33px; padding:2px; text-align:center; vertical-align:bottom; border:0.5px solid #000; border-top:none;">
                                        <?php if(strtolower($lpk->depthead_status ?? '') === 'approved'): ?>
                                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;">
                                                    <div style="display:flex; flex-direction:row; align-items:center; justify-content:center; gap:6px;">
                                                        <span style="font-size:13px; color:green; font-family: 'DejaVu Sans', Arial, sans-serif;">&#x2714;</span>
                                                        <span style="font-size:7px; color:#222;">
                                                            <?php if($lpk->depthead_approved_at): ?>
                                                                <?php echo e(\Carbon\Carbon::parse($lpk->depthead_approved_at)->format('d-m-Y')); ?>

                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    <span style="font-size:7px; color:#222; margin-top:2px;"><?php echo e($lpk->depthead_approver_name ?? ''); ?></span>
                                                </div>
                                        <?php elseif(strtolower($lpk->depthead_status ?? '') === 'rejected'): ?>
                                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;">
                                                    <div style="font-size:8px; font-weight:bold; color:red; margin-bottom:2px;">Cancel</div>
                                                    <div style="font-size:6px; color:#222;">
                                                        <?php if($lpk->depthead_approved_at): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($lpk->depthead_approved_at)->format('d-m-Y')); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <div style="font-size:6px; color:#222; margin-top:1px;"><?php echo e($lpk->depthead_approver_name ?? ''); ?></div>
                                                </div>
                                        <?php else: ?>
                                            <span style="font-size:7px; color:#888;">Belum disetujui</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <!-- Sect Head 1 Table Cell -->
                        <td style="width:18.4%; vertical-align:top; padding:0;">
                            <table style="width:100%; border-collapse:collapse;">
                                <tr>
                                    <td class="sig-header" style="height:16px; font-style:italic; text-align:center; padding:2px 4px; font-size:8px; background-color:#f0f0f0; font-weight:bold; vertical-align:middle; border:0.5px solid #000;">
                                        Sect Head
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sig-box" style="height:33px; padding:2px; text-align:center; vertical-align:bottom; border:0.5px solid #000; border-top:none;">
                                        <?php if(strtolower($lpk->secthead_status ?? '') === 'approved'): ?>
                                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;">
                                                    <div style="display:flex; flex-direction:row; align-items:center; justify-content:center; gap:6px;">
                                                        <span style="font-size:13px; color:green; font-family: 'DejaVu Sans', Arial, sans-serif;">&#x2714;</span>
                                                        <span style="font-size:7px; color:#222;">
                                                            <?php if($lpk->secthead_approved_at): ?>
                                                                <?php echo e(\Carbon\Carbon::parse($lpk->secthead_approved_at)->format('d-m-Y')); ?>

                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    <span style="font-size:7px; color:#222; margin-top:2px;"><?php echo e($lpk->secthead_approver_name ?? ''); ?></span>
                                                </div>
                                        <?php elseif(strtolower($lpk->secthead_status ?? '') === 'rejected'): ?>
                                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;">
                                                    <div style="font-size:8px; font-weight:bold; color:red; margin-bottom:2px;">Cancel</div>
                                                    <div style="font-size:6px; color:#222;">
                                                        <?php if($lpk->secthead_approved_at): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($lpk->secthead_approved_at)->format('d-m-Y')); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <div style="font-size:6px; color:#222; margin-top:1px;"><?php echo e($lpk->secthead_approver_name ?? ''); ?></div>
                                                </div>
                                        <?php else: ?>
                                            <span style="font-size:7px; color:#888;">Belum disetujui</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <!-- Sect Head 2 Table Cell -->
                        <td style="width:17.8%; vertical-align:top; padding:0;">
                            <table style="width:100%; border-collapse:collapse;">
                                <tr>
                                    <td class="sig-header" style="height:16px; font-style:italic; text-align:center; padding:2px 4px; font-size:8px; background-color:#f0f0f0; font-weight:bold; vertical-align:middle; border:0.5px solid #000;">
                                        Sect Head
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sig-box" style="height:33px; padding:2px; text-align:center; vertical-align:bottom; border:0.5px solid #000; border-top:none;">
                                            <?php if(strtolower($lpk->secthead_status ?? '') === 'approved'): ?>
                                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;">
                                                    <div style="display:flex; flex-direction:row; align-items:center; justify-content:center; gap:6px;">
                                                        <span style="font-size:13px; color:green; font-family: 'DejaVu Sans', Arial, sans-serif;">&#x2714;</span>
                                                        <span style="font-size:7px; color:#222;">
                                                            <?php if($lpk->secthead_approved_at): ?>
                                                                <?php echo e(\Carbon\Carbon::parse($lpk->secthead_approved_at)->format('d-m-Y')); ?>

                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    <span style="font-size:7px; color:#222; margin-top:2px;"><?php echo e($lpk->secthead_approver_name ?? ''); ?></span>
                                                </div>
                                            <?php elseif(strtolower($lpk->secthead_status ?? '') === 'rejected'): ?>
                                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;">
                                                    <div style="font-size:8px; font-weight:bold; color:red; margin-bottom:2px;">Cancel</div>
                                                    <div style="font-size:6px; color:#222;">
                                                        <?php if($lpk->secthead_approved_at): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($lpk->secthead_approved_at)->format('d-m-Y')); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <div style="font-size:6px; color:#222; margin-top:1px;"><?php echo e($lpk->secthead_approver_name ?? ''); ?></div>
                                                </div>
                                            <?php else: ?>
                                                <span style="font-size:7px; color:#888;">Belum disetujui</span>
                                            <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <!-- PPC Head Table Cell -->
                        <td style="width:24%; vertical-align:top; padding:0;">
                            <table style="width:100%; border-collapse:collapse;">
                                <tr>
                                    <td class="sig-header" style="height:16px; font-style:italic; text-align:center; padding:2px 4px; font-size:8px; background-color:#f0f0f0; font-weight:bold; vertical-align:middle; border:0.5px solid #000;">
                                        PPC Head
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sig-box" style="height:33px; padding:2px; text-align:center; vertical-align:bottom; border:0.5px solid #000; border-top:none;">
                                            <?php if(strtolower($lpk->ppchead_status ?? '') === 'approved'): ?>
                                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;">
                                                    <div style="display:flex; flex-direction:row; align-items:center; justify-content:center; gap:6px;">
                                                        <span style="font-size:13px; color:green; font-family: 'DejaVu Sans', Arial, sans-serif;">&#x2714;</span>
                                                        <span style="font-size:7px; color:#222;">
                                                            <?php if($lpk->ppchead_approved_at): ?>
                                                                <?php echo e(\Carbon\Carbon::parse($lpk->ppchead_approved_at)->format('d-m-Y')); ?>

                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    <span style="font-size:7px; color:#222; margin-top:2px;"><?php echo e($lpk->ppchead_approver_name ?? ''); ?></span>
                                                </div>
                                            <?php elseif(strtolower($lpk->ppchead_status ?? '') === 'rejected'): ?>
                                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;">
                                                    <div style="font-size:8px; font-weight:bold; color:red; margin-bottom:2px;">Cancel</div>
                                                    <div style="font-size:6px; color:#222;">
                                                        <?php if($lpk->ppchead_approved_at): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($lpk->ppchead_approved_at)->format('d-m-Y')); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <div style="font-size:6px; color:#222; margin-top:1px;"><?php echo e($lpk->ppchead_approver_name ?? ''); ?></div>
                                                </div>
                                            <?php else: ?>
                                                <span style="font-size:7px; color:#888;">Belum disetujui</span>
                                            <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Tabel IDENTIFIKASI MASALAH versi presisi dan proporsional -->
    <table style="width:100%; border-collapse:collapse; font-size:8px;">
        <tr>
            <td colspan="3" style="text-align:center; font-weight:bold; letter-spacing:2px; border:1px solid #000; font-size:7px; background:#f0f0f0;">IDENTIFIKASI MASALAH</td>
            <td colspan="1" style="text-align:center; font-weight:bold; border:1px solid #000; font-size:7px; background:#f0f0f0;">SUPPLIER</td>
            <td colspan="3" style="text-align:center; font-weight:bold; border:1px solid #000; font-size:7px; background:#f0f0f0;">VERIFIKASI</td>
        </tr>

        <tr>
            <td style="text-align:center; border:1px solid #000; width:18%; font-size:7px;">SEBAB UTAMA</td>
            <td style="text-align:center; border:1px solid #000; width:30%; font-size:7px;">TINDAKAN SEMENTARA &amp; TINDAKAN PERMANEN</td>
            <td style="text-align:center; border:1px solid #000; width:7%; font-size:7px;">JADUAL</td>
            <td style="text-align:left; border:1px solid #000; width:10%; font-size:7px;">Disetujui</td>
            <td style="text-align:center; border:1px solid #000; width:5%; font-size:7px;">BLN-1</td>
            <td style="text-align:center; border:1px solid #000; width:5%; font-size:7px;">BLN-2</td>
            <td style="text-align:center; border:1px solid #000; width:5%; font-size:7px;">BLN-3</td>
        </tr>

        <tr>
            <td rowspan="3" style="border:1px solid #000; vertical-align:top; padding:2px; font-size:7px;">
                GUNAKAN ANALISA 4M+1E &amp; ANALISA WHY-WHY<br>
                <div style="margin-top:6px; text-align:center;">
                    <img src="<?php echo e(public_path('image/fishbone.png')); ?>" alt="fishbone" style="width:100px; max-width:100%; height:auto;">
                </div>
                <div style="font-size:6px; margin-top:10px;">Note : Apabila lembar ini tidak cukup dapat menggunakan lembar lain.</div>
            </td>

            <td rowspan="3" style="border:1px solid #000; vertical-align:top; padding:0; font-size:8px;">
                <?php
                    // Check if LPK is rejected by any of the approval roles
                    $sect = strtolower($lpk->secthead_status ?? 'pending');
                    $dept = strtolower($lpk->depthead_status ?? 'pending');
                    $ppc = strtolower($lpk->ppchead_status ?? 'pending');
                    $isRejected = in_array('rejected', [$sect, $dept, $ppc]);
                ?>

                <?php if($isRejected): ?>
                    <!-- Show Cancel text if LPK is rejected -->
                    <div style="text-align:center; margin-top:10px; margin-bottom:10px; font-size:20px; font-weight:bold; color:red;">
                        Cancel
                    </div>
                <?php endif; ?>

                <!-- Always show document review content -->
                <div style="margin-top:<?php echo e($isRejected ? '0' : '40px'); ?>; margin-bottom:1px; padding-top:10px;">
                    <span style="font-size:8px; padding-left:4px;">Dokumen yang perlu direview terkait perbaikan diatas</span>
                    <div style="margin-top:1px; font-size:8px; padding-top:1px; padding-left:4px;">
                        <span style="display:inline-block; width:7px; height:7px; border:1px solid #000; margin-right:4px; vertical-align:middle;"></span> QCPC / Control Plan
                        <span style="display:inline-block; width:32px;"></span>
                        <span style="display:inline-block; width:7px; height:7px; border:1px solid #000; margin-right:4px; vertical-align:middle;"></span> Lainnya ....
                        <br>
                        <span style="display:inline-block; width:7px; height:7px; border:1px solid #000; margin-right:4px; vertical-align:middle; margin-top:1px;"></span> Working Instruction
                    </div>
                </div>
            </td>

            <td rowspan="3" style="border:1px solid #000; font-size:8px;"></td>
            <td style="border:1px solid #000; text-align:left; height:15px; font-size:8px; padding-left:4px;"></td>
            <td style="border:1px solid #000; text-align:center; font-size:8px;">&nbsp;</td>
            <td style="border:1px solid #000; text-align:center; font-size:8px;">&nbsp;</td>
            <td style="border:1px solid #000; text-align:center; font-size:8px;">&nbsp;</td>
        </tr>

        <tr>
            <td style="border:1px solid #000; text-align:left; height:15px; font-size:8px; padding-left:4px;">Diperiksa</td>
            <td colspan="3" style="border:1px solid #000; text-align:left; font-size:8px;">KETERANGAN:</td>
        </tr>

        <tr>
            <td style="border:1px solid #000; text-align:left; height:15px; font-size:8px; padding-left:4px;">Dibuat</td>
            <td style="border:1px solid #000; text-align:center; font-size:8px; font-weight:bold;" colspan="2">KEPUTUSAN<br><span style="font-size:11px;">OK/NG</span></td>
            <td style="border:1px solid #000; text-align:center; font-size:8px; font-weight:bold;">PARAF</td>
        </tr>
    </table>

    <!-- Footer section dengan alignment -->
    <table style="width:100%; border-collapse:collapse; margin-top:1px;">
        <tr>
            <td style="text-align:left; font-size:8px; width:60%; border:none;">
                Route : Vendor Development PT KYBI->PPC PT KYB->Procurement PT KYBI->QA Receiving PT KYBI
            </td>
            <td style="text-align:right; font-size:8px; width:40%; border:none;">
                Dijawab Maksismal 5 Hari Kerja Setelah LPK Diterima
            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/qc/lpk/export_pdf.blade.php ENDPATH**/ ?>