<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamenesComplementariosFotos extends Model
{
     protected $fillable = ['paciente_id','consulta_id', 'examen_complementario_id', 'numero' ,'foto', 'activo'];
}
