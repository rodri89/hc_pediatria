<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuxPendiente extends Model
{
    protected $fillable = ['paciente_id','consulta_id', 'pendientes','activo'];
}
