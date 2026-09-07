<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interconsulta extends Model
{
    protected $fillable = ['paciente_id','consulta_id', 'numero_consulta','numero','especialista', 'solicito', 'respuesta', 'consulta_respuesta', 'fechaSolicitud','activo'];
}
        