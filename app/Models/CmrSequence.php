<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmrSequence extends Model
{
    use HasFactory;

    protected $table = 'cmr_sequences';

    protected $fillable = [
        'year',
        'current',
    ];
}
