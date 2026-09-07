<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $table = 'observaciones';
    
    protected $fillable = ['paciente_id','consulta_id', 'descripcion','activo'];
}

