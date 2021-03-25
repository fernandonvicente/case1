<?php

namespace App\Models\All;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    protected $table = 'conta';

    protected $fillable = ['nome', 'conta','saldo'];
    
}
