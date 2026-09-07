<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternacionesFotos extends Model
{
    protected $fillable = ['paciente_id','consulta_id', 'internaciones_id', 'numero', 'foto', 'activo'];
}
