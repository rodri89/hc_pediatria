<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Internacione extends Model
{
    protected $fillable = ['paciente_id','consulta_id', 'antecedentes_personales_id', 'numero', 'motivo', 'lugar', 'duracion', 'indicacion_alta', 'activo'];
}
            