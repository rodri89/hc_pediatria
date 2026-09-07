<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = ['paciente_id', 'medico_id', 'tipo_consulta','es_foto', 'edad_meses','edad_mostrar','activo'];
}
