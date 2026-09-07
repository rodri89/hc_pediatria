<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesarrolloMadurativoPaciente extends Model
{
    protected $fillable = ['consulta_id', 'paciente_id', 'desarrollo_madurativo_id', 'checked', 'observacion','activo'];
}
