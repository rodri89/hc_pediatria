<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsultaFoto extends Model
{
   protected $fillable = ['consulta_id', 'paciente_id', 'numero', 'foto','activo'];
}
