<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntecedentesNeonatales extends Model
{
    protected $fillable = ['paciente_id','consulta_id', 'nota', 'fechaSolicitud','activo'];
}
