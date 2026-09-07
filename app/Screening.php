<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    protected $fillable = ['paciente_id', 'numero', 'evaluacion', 'fechaSolicitud', 'respuesta','activo'];
}
