<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nqr extends Model
{
    use HasFactory;

    protected $fillable = [
        // Data QC
        'no_reg_nqr',
        'tgl_terbit_nqr',
        'tgl_delivery',
        'nama_supplier',
        'nama_part',
        'nomor_po',
        'nomor_part',
        'status_nqr',
        'location_claim_occur',
        'disposition_inventory_location',
        'disposition_inventory_action',
        'claim_occurence_freq',
        'disposition_defect_part',
        'invoice',
        'order',
        'total_del',
        'total_claim',
        'gambar',
        'detail_gambar',

        // Data PPC
    'disposition_claim',
    'pay_compensation_value',
    'pay_compensation_currency',
    'pay_compensation_currency_symbol',
        'send_replacement_method',

        // Approval fields
        'status_approval',
        'approved_by_qc',
        'approved_at_qc',
        'approved_by_sect_head',
        'approved_at_sect_head',
        'approved_by_dept_head',
        'approved_at_dept_head',
        'approved_by_ppc',
        'approved_at_ppc',
        'approved_by_vdd',
        'approved_at_vdd',
        'approved_by_procurement',
        'approved_at_procurement',
        'requested_by',
        'requested_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_terbit_nqr' => 'date',
        'tgl_delivery' => 'date',
        'approved_at_qc' => 'datetime',
        'approved_at_sect_head' => 'datetime',
        'approved_at_dept_head' => 'datetime',
        'approved_at_ppc' => 'datetime',
        'approved_at_vdd' => 'datetime',
        'approved_at_procurement' => 'datetime',
        'requested_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Relasi ke User (created by)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User (updated by)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi untuk approval tracking
     */
    public function qcApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_qc');
    }

    public function approverQc()
    {
        return $this->belongsTo(User::class, 'approved_by_qc');
    }

    public function sectHeadApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_sect_head');
    }

    public function approverSectHead()
    {
        return $this->belongsTo(User::class, 'approved_by_sect_head');
    }

    public function deptHeadApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_dept_head');
    }

    public function approverDeptHead()
    {
        return $this->belongsTo(User::class, 'approved_by_dept_head');
    }

    public function ppcApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_ppc');
    }

    public function vddApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_vdd');
    }

    public function procurementApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_procurement');
    }

    public function approverPpc()
    {
        return $this->belongsTo(User::class, 'approved_by_ppc');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Generate nomor registrasi NQR otomatis dengan sistem sequence
     * Format: 0001/NQR/Bulan Romawi/2025
     */
    public static function generateNoRegNqr()
    {
        $year = date('Y');
        $month = date('n');
        $romanMonth = self::getRomanMonth($month);

        // Jika tabel nqr_sequences belum ada, gunakan cara lama
        if (!\Schema::hasTable('nqr_sequences')) {
            $next = 1;
            $number = str_pad($next, 4, '0', STR_PAD_LEFT);
            return sprintf('%s/NQR/%s/%s', $number, $romanMonth, $year);
        }

        // Gunakan transaction dengan lock untuk memastikan nomor unik
        return \DB::transaction(function () use ($year, $month, $romanMonth) {
            // Lock row untuk tahun ini atau buat baru
            $seq = NqrSequence::where('year', $year)->lockForUpdate()->first();
            if (!$seq) {
                $seq = NqrSequence::create(['year' => $year, 'current' => 0]);
            }

            $seq->current = $seq->current + 1;
            $seq->save();

            $number = str_pad($seq->current, 4, '0', STR_PAD_LEFT);
            return sprintf('%s/NQR/%s/%s', $number, $romanMonth, $year);
        });
    }

    /**
     * Convert bulan ke angka Romawi
     */
    public static function getRomanMonth($month)
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        return $romans[$month];
    }
}
