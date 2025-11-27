<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NqrSequence extends Model
{
    protected $table = 'nqr_sequences';
    protected $fillable = ['year', 'current'];
}
