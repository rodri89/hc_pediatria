<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosSubjetivos extends Model
{
	protected $fillable = ['paciente_id','consulta_id', 'descripcion','activo'];
}
