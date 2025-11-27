<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Carbon\Carbon;

class Lpk extends Model
{
    use HasFactory;
    protected $fillable = [
    'no_reg', 'tgl_terbit', 'tgl_delivery', 'nama_supply', 'nama_part', 'nomor_part', 'nomor_po',
    'status', 'jenis_ng', 'gambar', 'detail_gambar', 'total_check', 'total_ng', 'total_delivery', 'total_claim', 'kategori',
    'problem',
    // new dropdown fields
    'perlakuan_terhadap_part', 'frekuensi_claim', 'perlakuan_part_defect', 'lokasi_penemuan_claim', 'status_repair',
    'customer_pt_name', // Nama customer PT ketika lokasi claim adalah Customer PT
    // PPC Head inputs
    'ppc_perlakuan_terhadap_part', 'ppc_perlakuan_terhadap_claim',
    // LKA fields
    'referensi_lka', 'tgl_terbit_lka',
    // approval fields
    'secthead_status','secthead_note','secthead_approver_id','secthead_approved_at',
    'depthead_status','depthead_note','depthead_approver_id','depthead_approved_at',
    'ppchead_status','ppchead_note','ppchead_approver_id','ppchead_approved_at',
];

    // Approver relationships
    public function sectheadApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secthead_approver_id');
    }

    public function deptheadApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'depthead_approver_id');
    }

    public function ppcheadApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ppchead_approver_id');
    }

    // Status label helper
    public function statusLabelFor($status)
    {
        $s = strtolower($status ?? 'pending');
        return $s === 'approved' ? 'disetujui' : ($s === 'rejected' ? 'ditolak' : 'menunggu');
    }

    // Per-role status label accessors
    public function getSectheadStatusLabelAttribute()
    {
        return $this->statusLabelFor($this->secthead_status);
    }

    public function getDeptheadStatusLabelAttribute()
    {
        return $this->statusLabelFor($this->depthead_status);
    }

    public function getPpcheadStatusLabelAttribute()
    {
        return $this->statusLabelFor($this->ppchead_status);
    }

    // Per-role approver name and formatted timestamp
    public function getSectheadApproverNameAttribute()
    {
        return $this->sectheadApprover ? $this->sectheadApprover->name : null;
    }

    public function getDeptheadApproverNameAttribute()
    {
        return $this->deptheadApprover ? $this->deptheadApprover->name : null;
    }

    public function getPpcheadApproverNameAttribute()
    {
        return $this->ppcheadApprover ? $this->ppcheadApprover->name : null;
    }

    public function getSectheadApprovedAtFormattedAttribute()
    {
        if (! $this->secthead_approved_at) return null;
        Carbon::setLocale('id');
        $dt = Carbon::make($this->secthead_approved_at)->setTimezone('Asia/Jakarta');
        $dt->locale('id');
        return $dt->translatedFormat('d F Y H:i');
    }

    public function getDeptheadApprovedAtFormattedAttribute()
    {
        if (! $this->depthead_approved_at) return null;
        Carbon::setLocale('id');
        $dt = Carbon::make($this->depthead_approved_at)->setTimezone('Asia/Jakarta');
        $dt->locale('id');
        return $dt->translatedFormat('d F Y H:i');
    }

    public function getPpcheadApprovedAtFormattedAttribute()
    {
        if (! $this->ppchead_approved_at) return null;
        Carbon::setLocale('id');
        $dt = Carbon::make($this->ppchead_approved_at)->setTimezone('Asia/Jakarta');
        $dt->locale('id');
        return $dt->translatedFormat('d F Y H:i');
    }

    /**
     * Canonicalized lokasi penemuan claim for display logic.
     * Returns one of: 'receiving', 'in-process', 'customer', or null if unknown.
     * Does NOT mutate the stored value — this is for views/PDF only.
     */
    public function getLokasiCanonicalAttribute(): ?string
    {
        $raw = strtolower(trim($this->lokasi_penemuan_claim ?? ''));
        if ($raw === '') return null;

        // Normalize separators (spaces, various dashes, underscores) for robust matching
        $collapsed = preg_replace('/[\s\x{2010}-\x{2015}_-]+/u', ' ', $raw);

        // Receiving
        if (strpos($collapsed, 'receiving') !== false) {
            return 'receiving';
        }

        // In-Process: match English 'process' and Indonesian 'proses'
        if ((strpos($collapsed, 'in process') !== false)
            || (strpos($collapsed, 'in proses') !== false)
            || (strpos(str_replace(' ', '', $collapsed), 'inprocess') !== false)
            || (strpos(str_replace(' ', '', $collapsed), 'inproses') !== false)) {
            return 'in-process';
        }

        // Customer
        if (strpos($collapsed, 'customer') !== false) {
            return 'customer';
        }

        return null;
    }

    /**
     * Canonicalized value for 'perlakuan_terhadap_part'.
     * Returns 'customer', 'supplier', or null if unknown.
     */
    public function getPerlakuanTerhadapPartCanonicalAttribute(): ?string
    {
        $raw = strtolower(trim($this->perlakuan_terhadap_part ?? ''));
        if ($raw === '') return null;

        if (strpos($raw, 'customer') !== false) {
            return 'customer';
        }
        if (strpos($raw, 'supplier') !== false) {
            return 'supplier';
        }
        if (strpos($raw, 'kybi') !== false) {
            return 'kybi';
        }
        if (strpos($raw, 'tetap') !== false || strpos($raw, 'dipakai') !== false) {
            return 'tetap';
        }
        return null;
    }

    /**
     * Canonicalized value for 'frekuensi_claim'.
     * Returns 'pertama' or 'berulang' or null.
     */
    public function getFrekuensiClaimCanonicalAttribute(): ?string
    {
        $raw = strtolower(trim($this->frekuensi_claim ?? ''));
        if ($raw === '') return null;

        if (strpos($raw, 'pertama') !== false || strpos($raw, 'sekali') !== false) {
            return 'pertama';
        }
        if (strpos($raw, 'berulang') !== false || strpos($raw, 'rutin') !== false || strpos($raw, 'berkala') !== false) {
            return 'berulang';
        }
        return null;
    }
}
