<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpkSequence extends Model
{
    protected $table = 'lpk_sequences';
    protected $fillable = ['year', 'current'];
}
