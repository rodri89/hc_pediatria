<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntecedentesNeonatalesFotos extends Model
{
    protected $fillable = ['paciente_id', 'consulta_id', 'antecedentes_neonatales_patologicos_id', 'numero','foto','activo'];

}
