<?php

namespace App\Models\All;

use Illuminate\Database\Eloquent\Model;

class Extrato extends Model
{
    protected $table = 'extrato';

    protected $fillable = ['conta_id', 'valor','transacao'];
    
}
