<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamenesComplementarios extends Model
{
     protected $fillable = ['paciente_id','consulta_id', 'numero_consulta','numero', 'solicito', 'fechaSolicitud', 'respuesta', 'consulta_respuesta','activo'];
}
           