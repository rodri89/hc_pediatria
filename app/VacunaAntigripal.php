<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VacunaAntigripal extends Model
{
    protected $fillable = ['consulta_id', 'paciente_id', 'fecha', 'dosis','activo'];
}
