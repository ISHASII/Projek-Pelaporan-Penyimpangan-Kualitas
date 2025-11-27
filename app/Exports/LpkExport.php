<?php
namespace App\Exports;

use App\Models\Lpk;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;



class LpkExport implements FromArray, WithHeadings, WithTitle, WithEvents, WithDrawings {

    /**
     * Untuk gambar di Excel (jika ada)
     */
    public function drawings()
    {
        if (!$this->lpk->gambar) return [];
        $gambarField = ltrim($this->lpk->gambar, 'storage/\\');
        $path = public_path('storage/' . $gambarField);
        if (!file_exists($path)) return [];
        $drawing = new Drawing();
        $drawing->setName('Gambar LPK');
        $drawing->setDescription('Gambar LPK');
        $drawing->setPath($path);
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetY(5);
        return [$drawing];
    }
    protected $lpk;
    public function __construct(Lpk $lpk) {
        $this->lpk = $lpk;
    }

    public function array(): array
    {
        return [[
            $this->lpk->no_reg,
            $this->lpk->tgl_terbit,
            $this->lpk->tgl_delivery,
            $this->lpk->nama_part,
            $this->lpk->nomor_part,
            $this->lpk->nama_supply,
            $this->lpk->nomor_po,
            $this->lpk->status,
            $this->lpk->jenis_ng,
            $this->lpk->total_check,
            $this->lpk->total_ng,
            $this->lpk->total_delivery,
            $this->lpk->total_claim,
            ($this->lpk->percentage !== null ? rtrim(rtrim(number_format($this->lpk->percentage,2), '0'), '.') . '%' : ''),
            $this->lpk->perlakuan_terhadap_part,
            $this->lpk->frekuensi_claim,
            $this->lpk->perlakuan_part_defect,
            $this->lpk->lokasi_penemuan_claim,
            $this->lpk->status_repair,
            $this->lpk->ppc_perlakuan_terhadap_part,
            $this->lpk->ppc_perlakuan_terhadap_claim,
            $this->lpk->secthead_status_label,
            $this->lpk->secthead_note,
            $this->lpk->depthead_status_label,
            $this->lpk->depthead_note,
            $this->lpk->ppchead_status_label,
            $this->lpk->ppchead_note,
        ]];
    }

    public function headings(): array
    {
        return [
            'No REG', 'Tanggal Terbit', 'Tanggal Delivery', 'Nama Part', 'Nomor Part', 'Nama Supply', 'Nomor PO', 'Status', 'Jenis LPK',
            'Total Check', 'Total NG', 'Total Delivery', 'Total Claim', 'Persentase',
            'Perlakuan Terhadap Part', 'Frekuensi Claim', 'Perlakuan Part Defect', 'Lokasi Penemuan Claim', 'Status Repair',
            'PPC Perlakuan Part', 'PPC Perlakuan Claim',
            'Sect Head Status', 'Sect Head Note',
            'Dept Head Status', 'Dept Head Note',
            'PPC Head Status', 'PPC Head Note',
        ];
    }

    public function title(): string
    {
        return 'LPK-' . $this->lpk->no_reg;
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A5);
                $sheet->getPageSetup()->setFitToPage(true);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(1);
                // Set column widths (A-P)
                $widths = [
                    'A' => 15, 'B' => 13, 'C' => 13, 'D' => 18, 'E' => 18, 'F' => 15, 'G' => 10, 'H' => 12,
                    'I' => 18, 'J' => 18, 'K' => 15, 'L' => 25, 'M' => 15, 'N' => 25, 'O' => 15, 'P' => 25
                ];
                foreach ($widths as $col => $w) {
                    $sheet->getColumnDimension($col)->setWidth($w);
                }

                // Styling: font kecil, wrap text, border tipis, header bold abu-abu
                $highestCol = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();
                $allCells = "A1:{$highestCol}{$highestRow}";
                $headerCells = "A1:{$highestCol}1";

                // Header styling
                $sheet->getStyle($headerCells)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '444444']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'f3f3f3']
                    ],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'bbbbbb']
                        ]
                    ]
                ]);
                // Body styling
                $sheet->getStyle($allCells)->applyFromArray([
                    'font' => ['size' => 9],
                    'alignment' => [
                        'vertical' => 'top',
                        'wrapText' => true,
                        'horizontal' => 'left',
                        'shrinkToFit' => false
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'bbbbbb']
                        ]
                    ]
                ]);

                // Styling khusus kolom note agar mirip PDF (wrap, word-break)
                // Kolom L, N, dan P adalah note
                foreach(['L', 'N', 'P'] as $col) {
                    $sheet->getStyle($col.'2')->getAlignment()->setWrapText(true);
                }
            },
        ];
    }
}