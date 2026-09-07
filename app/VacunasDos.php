<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VacunasDos extends Model
{
    protected $fillable = ['paciente_id','consulta_id', 'descripcion','activo'];
}
