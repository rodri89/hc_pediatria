<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VacunasPacientes extends Model
{
   	protected $fillable = ['paciente_id', 'vacuna_id', 'nombre', 'edad_meses','estado','vacuna_view_id','activo'];
}
