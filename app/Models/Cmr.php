<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cmr extends Model
{
    use HasFactory;

    // human-readable status_approval constants
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_REJECTED_BY_PROCUREMENT = 'Rejected by Procurement';

    protected $table = 'cmrs';
    protected $fillable = [
        // existing/general
        'no_reg',
        'nama',
        'deskripsi',
        'created_by',
        'updated_by',

        // dates
        'tgl_terbit_cmr',
        'tgl_terbit_nqr',
        'tgl_delivery',
    'bl_date',
    'ar_date',

        // supplier/part/order
        'nama_supplier',
        'nama_part',
        // 'nomor_po' is input-only (mapped to order_no) and not stored as separate column
        'nomor_part',
        'invoice_no',
        'order_no',

        // product/location/dispositions
        'product',
    'model',
        'location_claim_occur',
        'location_claim_occurrence',
        'disposition_inventory',
        'disposition_inventory_type',
        'disposition_inventory_detail',
        'disposition_inventory_choice',

        // claim/frequency/dispatch
        'claim_frequency',
        'claim_occurrence_frequency',
        'dispatch_defective_parts',
        'disposition_defect_part',
        'disposition_defect_parts',

        // quantities and images
        'total_del',
        'total_claim',
        'qty_deliv',
    'qty_order',
        'qty_problem',
        'gambar',
        'detail_gambar',
        'input_problem',
    'crate_number',

        // statuses
        'secthead_status',
        'secthead_note',
        'secthead_approver_id',
        'secthead_approved_at',
        'depthead_status',
        'depthead_note',
        'depthead_approver_id',
        'depthead_approved_at',
        'ppchead_status',
        'ppchead_note',
        'ppchead_approver_id',
        'ppchead_approved_at',
        'vdd_status',
        'vdd_note',
        'vdd_approver_id',
        'vdd_approved_at',
        'ppc_currency',
        'ppc_currency_symbol',
            'agm_status',
            'agm_note',
            'agm_approver_id',
            'agm_approved_at',
            'procurement_status',
            'procurement_note',
            'procurement_approver_id',
            'procurement_approved_at',
        'requested_at_qc',
    'found_date',
        'status_approval',
        'status_nqr',
    ];

    protected $casts = [
        'tgl_terbit_cmr' => 'date',
        'tgl_terbit_nqr' => 'date',
        'tgl_delivery' => 'date',
        'bl_date' => 'date',
        'ar_date' => 'date',
        'qty_deliv' => 'integer',
        'qty_order' => 'integer',
        'qty_problem' => 'integer',
        'requested_at_qc' => 'datetime',
        'secthead_approved_at' => 'datetime',
        'depthead_approved_at' => 'datetime',
        'ppchead_approved_at' => 'datetime',
            'vdd_approved_at' => 'datetime',
            'agm_approved_at' => 'datetime',
            'procurement_approved_at' => 'datetime',
    'found_date' => 'date',
    ];
}
